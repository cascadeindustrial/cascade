<?php
namespace MageMaclean\MyShipping\Plugin\Quote;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\ShippingMethodExtensionFactory;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Carrier;
use MageMaclean\MyShipping\Model\Repository\CourierListRepository;
use MageMaclean\MyShipping\Model\Myshipping\ItemPriceCalculator;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class ShippingMethodManagement
{
    protected $_helper;
    protected $quoteRepository;
    protected $addressRepository;
    protected $_shippingMethodExtension;
    protected $_courierListRepository;
    protected $_accountRepository;
    protected $_itemPriceCalculator;

    protected $storeManager;
    protected $scopeConfig;
    protected $_priceCurrency;
    protected $_taxData;

    public function __construct(
        Helper $helper,
        CartRepositoryInterface $quoteRepository,
        AddressRepositoryInterface $addressRepository,
        ShippingMethodExtensionFactory $shippingMethodExtensionFactory,
        CourierListRepository $courierListRepository,
        AccountRepository $accountRepository,
        ItemPriceCalculator $itemPriceCalculator,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Tax\Helper\Data $taxData
    )
    {
        $this->_helper = $helper;
        $this->quoteRepository = $quoteRepository;
        $this->addressRepository = $addressRepository;
        $this->_shippingMethodExtension = $shippingMethodExtensionFactory;
        $this->_courierListRepository = $courierListRepository;
        $this->_accountRepository = $accountRepository;
        $this->_itemPriceCalculator = $itemPriceCalculator;

        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->_priceCurrency = $priceCurrency;
        $this->_taxData = $taxData;
    }

    public function afterEstimateByExtendedAddress(\Magento\Quote\Model\ShippingMethodManagement $subject, array $result, $cartId, AddressInterface $address) {
        $quote = $this->quoteRepository->getActive($cartId);
        return $this->_processResult($result, $quote, $address);
    }

    public function afterEstimateByAddressId(\Magento\Quote\Model\ShippingMethodManagement $subject, array $result, $cartId, $addressId)
    {
        $quote = $this->quoteRepository->getActive($cartId);
        $address = $this->addressRepository->getById($addressId);
        return $this->_processResult($result, $quote, $address);
    }

    protected function _processResult($result, $quote, $address) {
        if(!$result || !sizeof($result)) return $result;

        $output = [];
        foreach($result as $method) {
            if($method->getCarrierCode() == Carrier::CODE) {
                $extensionAttributes = $method->getExtensionAttributes() ? $method->getExtensionAttributes() : $this->_shippingMethodExtension->create();
                if($method->getMethodCode() == Carrier::CODE_METHOD_NEW) {
                    $courierOptions = [];
                    $courierCollection = $this->_courierListRepository->getStoreList($quote->getStoreId());
                    if($courierCollection) {
                        foreach($courierCollection as $courier) {
                            if($this->_helper->canUseCourier($courier, $address->getCountryId())) {
                                $courierOption = $courier->getData();
                                $courierOption["label"] = $courier->getTitle();
                                $courierOption["value"] = $courier->getId();
                                $courierOption["methods"] = $this->_getMethodOptions($quote, $courier);
                                $courierOptions[] = $courierOption;
                            }
                        }
                    }
                    
                    $extensionAttributes->setMyshippingCouriers($courierOptions);
                    
                } else {
                    $accountId = str_replace("account_", "", $method->getMethodCode());
                    $_account = $this->_accountRepository->getCustomerAccountById($accountId);
                    if($_account->getId()) {
                        $methodOptions = $this->_getMethodOptions($quote, $_account->getCourier());
                        $extensionAttributes->setMyshippingCourierMethods($methodOptions);
                    }
                }
                $method->setExtensionAttributes($extensionAttributes);
            }
            $output[] = $method;
        }
        return $output;
    }

    protected function _getMethodOptions($quote, $courier) {
        $methodOptions = [];
        
        $methods = $courier->getMethods(true);
        if($methods && sizeof($methods)) {
            $methods = $courier->getMethods(true);
            foreach($methods as $methodData) {
                $methodOption = $methodData;
                $methodPrice = $this->_itemPriceCalculator->getShippingPriceByMethodData($quote->getShippingAddress()->getAllVisibleItems(), $methodData);

                $methodOption['value'] = $methodData['method_code'];
                $methodOption['price'] = $methodPrice;
                $methodOption['label'] = $methodData['method_name'];
                $methodOption['courier_id'] = $courier->getId();
                $methodOption['courier_title'] = $courier->getTitle();

                if($this->_helper->getSystemConfigData('myshipping/checkout/show_method_prices')) {
                    $methodOption['label'] .= ' - ' . $this->_getFormattedPrice($quote, $methodPrice);
                }

                $methodOptions[] = $methodOption;
            }
        }
        return $methodOptions;
    }

    protected function _getFormattedPrice($quote, $methodPrice) {
        return $this->_priceCurrency->convertAndFormat(
            $this->_taxData->getShippingPrice(
                $methodPrice,
                $this->_taxData->displayShippingPriceIncludingTax($quote->getStoreId()),
                null,
                null,
                $quote->getStoreId()
            ),
            false,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $quote->getStoreId()
        );
    }
}
