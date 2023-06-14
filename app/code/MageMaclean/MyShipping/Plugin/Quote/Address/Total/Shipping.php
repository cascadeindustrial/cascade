<?php

namespace MageMaclean\MyShipping\Plugin\Quote\Address\Total;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\StoreManagerInterface;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Myshipping\ResultProcessor;

class Shipping
{
    protected $_quoteHelper;
    protected $storeManager;
    protected $_scopeConfig;
    protected $priceCurrency;

    protected $_helper;
    protected $_resultProcessor;

    public function __construct(
        StoreManagerInterface $storeManager = null,
        ScopeConfigInterface $scopeConfig,
        PriceCurrencyInterface $priceCurrency,
        Helper $helper,
        ResultProcessor $resultProcessor
    ) {
        $this->storeManager = $storeManager ?: ObjectManager::getInstance()->get(StoreManagerInterface::class);
        $this->_scopeConfig = $scopeConfig;
        $this->priceCurrency = $priceCurrency;

        $this->_helper = $helper;
        $this->_resultProcessor = $resultProcessor;
    }


    public function afterCollect(
        \Magento\Quote\Model\Quote\Address\Total\Shipping $subject,
        $result,
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        $store = $quote->getStore();
        $address = $shippingAssignment->getShipping()->getAddress();
        $method = $shippingAssignment->getShipping()->getMethod();
        $items = $shippingAssignment->getItems();

        if ($method && $this->_helper->isMyshippingMethod($method)) {
            foreach ($address->getAllShippingRates() as $rate) {
                if ($rate->getCode() == $method) {
                    $total->setTotalAmount($subject->getCode(), 0);
                    $total->setBaseTotalAmount($subject->getCode(), 0);

                    $myshippingResult = $this->_resultProcessor->create($method, $address, $items);
                    $shippingPrice = $myshippingResult->getShippingPrice();
                    $amountPrice = $this->priceCurrency->convert(
                        $shippingPrice,
                        $store
                    );

                    $total->setTotalAmount($subject->getCode(), $amountPrice);
                    $total->setBaseTotalAmount($subject->getCode(), $shippingPrice);
                    
                    #$shippingDescription = $rate->getCarrierTitle() . ' - ' . $rate->getMethodTitle();
                    $shippingDescription = $myshippingResult->getShippingDescription();

                    $address->setShippingDescription($shippingDescription);
                    $address->setBaseShippingAmount($shippingPrice);
                    $address->setShippingAmount($amountPrice);
                    $total->setBaseShippingAmount($shippingPrice);
                    $total->setShippingAmount($amountPrice);
                    $total->setShippingDescription($shippingDescription);

                    $shipping = $shippingAssignment->getShipping();
                    $shipping->setAddress($address);
                    $shippingAssignment->setShipping($shipping);
                    break;
                }
            }
        }
        return $result;
    }
}
