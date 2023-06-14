<?php
declare(strict_types=1);

namespace MageMaclean\MyShipping\Block\Multishipping\Checkout;

use MageMaclean\MyShipping\Model\Myshipping\ItemPriceCalculator;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\ScopeInterface;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Repository\CourierRepository;
use MageMaclean\MyShipping\Model\Repository\CourierListRepository;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use MageMaclean\MyShipping\Model\Myshipping\ResultFactory;

class Shipping extends \Magento\Multishipping\Block\Checkout\Shipping
{
    protected $_storeManager;
    protected $_helper;
    protected $_courierRepository;
    protected $_courierListRepository;
    protected $_accountRepository;
    protected $_resultFactory;
    protected $_itemPriceCalculator;

    protected $_courierOptions;
    protected $_courierMethodOptions;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Filter\DataObject\GridFactory $filterGridFactory,
        \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping,
        \Magento\Tax\Helper\Data $taxHelper,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Helper $helper,
        AccountRepository $accountRepository,
        CourierRepository $courierRepository,
        CourierListRepository $courierListRepository,
        ResultFactory $resultFactory,
        ItemPriceCalculator $itemPriceCalculator,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
        $this->_courierRepository = $courierRepository;
        $this->_courierListRepository = $courierListRepository;
        $this->_accountRepository = $accountRepository;
        $this->_resultFactory = $resultFactory;
        $this->_itemPriceCalculator = $itemPriceCalculator;

        parent::__construct($context, $filterGridFactory, $multishipping, $taxHelper, $priceCurrency, $data);
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getStoreId();
    }

    public function getCourierOptions($emptyLabel = null) {
        if(is_null($this->_courierOptions)) {
            $options = array();
            if($emptyLabel) {
                $option = array('value' => "", 'label' => $emptyLabel);
                $options[] = $option;
            }

            $courierCollection = $this->_courierListRepository->getStoreList($this->getStoreId());
            if($courierCollection) {
                foreach($courierCollection as $courier) {
                    $options[] = array(
                        'value' => $courier->getId(),
                        'label' => $courier->getTitle()
                    );
                }
            } else {
                $options[] = array(
                    'value' => 0,
                    'label' => 'No couriers have been configured.'
                );
            }
            $this->_courierOptions = $options;
        }

        return $this->_courierOptions;
    }

    public function getAllCourierMethodOptions($address, $emptyLabel = null) {
        // if(is_null($this->_courierMethodOptions)) {
            $courierCollection =  $this->_courierListRepository->getStoreList($this->getStoreId());
            $methodOptions = array();

            if($courierCollection) {
                foreach($courierCollection as $courier) {
                    $methodOptions[$courier->getId()] = array();
                    if($emptyLabel) {
                        $methodOption = array('value' => "", 'label' => $emptyLabel);
                        $methodOptions[$courier->getId()][] = $methodOption;
                    }

                    $methods = $courier->getMethods(true);
                    if($methods && sizeof($methods)) {
                        foreach($methods as $methodData) {
                            $methodOption = $methodData;

                            $methodPrice = $this->_itemPriceCalculator->getShippingPriceByMethodData($address->getAllVisibleItems(), $methodData);
                            $methodOption['value'] = $methodData['method_code'];
                            $methodOption['price'] = $methodPrice;
                            $methodOption['label'] = $methodData['method_name'];
                            
                            if($this->_helper->getSystemConfigData('myshipping/checkout/show_method_prices')) {
                                $methodOption['label'] .= ' - ' . $this->_getFormattedPrice($address, $methodPrice);
                            }
                            $methodOptions[$courier->getId()][] = $methodOption;
                        }
                    }
                }
            }
            $this->_courierMethodOptions = $methodOptions;
        // }

        return $this->_courierMethodOptions;
    }

    public function getCourierMethodOptions($address, $emptyLabel = null) {
        $courierId = $address->getMyshippingCourierId();
        $allCourierMethodOptions = $this->getAllCourierMethodOptions($address, $emptyLabel);
        if(isset($allCourierMethodOptions[$courierId])) {
            return $allCourierMethodOptions[$courierId];
        } else {
            return false;
        }
    }

    protected function _getFormattedPrice($address, $methodPrice) {
        return $this->priceCurrency->convertAndFormat(
            $this->_taxHelper->getShippingPrice(
                $methodPrice,
                $this->_taxHelper->displayShippingPriceIncludingTax($this->getStoreId()),
                $address,
                null,
                $this->getStoreId()
            ),
            false,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $this->getStoreId()
        );
    }

    public function getMyshippingMethodsByRate($address, $rate)
    {
        $methodCode = $rate->getMethod();
        $accountId = str_replace("account_", "", $methodCode);
        $myshippingAccount = $this->_accountRepository->getById($accountId);

        $methodOptions = [];
        $methods = $myshippingAccount->getCourier()->getMethods(true);
        if($methods && sizeof($methods)) {
            foreach($methods as $methodData) {
                $methodOption = $methodData;

                $methodPrice = $this->_itemPriceCalculator->getShippingPriceByMethodData($address->getAllVisibleItems(), $methodData);
                $methodOption['value'] = $methodData['method_code'];
                $methodOption['price'] = $methodPrice;
                $methodOption['label'] = $methodData['method_name'];
                
                if($this->_helper->getSystemConfigData('myshipping/checkout/show_method_prices')) {
                    $methodOption['label'] .= ' - ' . $this->_getFormattedPrice($address, $methodPrice);
                }
                $methodOptions[] = $methodOption;
            }
        }
        
        return $methodOptions;
    }

    public function getAccountValidationClasses() {
        return $this->_helper->getAccountValidationClasses();
    }
}
