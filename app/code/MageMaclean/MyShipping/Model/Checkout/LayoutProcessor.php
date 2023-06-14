<?php

namespace MageMaclean\MyShipping\Model\Checkout;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Carrier;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use MageMaclean\MyShipping\Model\Repository\CourierListRepository;
use MageMaclean\MyShipping\Model\Myshipping\ResultFactory;
use MageMaclean\MyShipping\Model\Myshipping\ResultProcessor;
use MageMaclean\MyShipping\Model\Myshipping\ItemPriceCalculator;

class LayoutProcessor
{
    protected $storeManager;
    protected $scopeConfig;
    protected $_customerSession;
    protected $_checkoutSession;
    protected $_priceCurrency;
    protected $_taxData;

    protected $_helper;
    protected $_accountRepository;
    protected $_courierListRepository;
    protected $_resultFactory;
    protected $_resultProcessor;
    protected $_itemPriceCalculator;

    protected $_courierOptions;
    protected $_courierMethodOptions;

    protected $_myshippingResult;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Tax\Helper\Data $taxData,
        Helper $helper,
        CourierListRepository $courierListRepository,
        AccountRepository $accountRepository,
        ResultFactory $resultFactory,
        ResultProcessor $resultProcessor,
        ItemPriceCalculator $itemPriceCalculator
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_priceCurrency = $priceCurrency;
        $this->_taxData = $taxData;

        $this->_helper = $helper;
        $this->_courierListRepository = $courierListRepository;
        $this->_accountRepository = $accountRepository;
        $this->_resultFactory = $resultFactory;
        $this->_resultProcessor = $resultProcessor;
        $this->_itemPriceCalculator = $itemPriceCalculator;
    }

    public function merge($elements, array $fields = [])
    {
        return array_merge($elements, $fields);
    }

    public function getStoreId()
    {
        return $this->getQuote()->getStoreId();
    }

    public function getQuote() {
        return $this->_checkoutSession->getQuote();
    }

    protected function isActive($method) {

        if($this->getQuote() && $this->getQuote()->getShippingAddress()) {
            $shippingAddress = $this->getQuote()->getShippingAddress();
            $shippingMethod = $shippingAddress->getShippingMethod();
            $isActive = $shippingMethod == $method;
        } else {
            $isActive = false;
        }

        return $isActive;
    }

    protected function getMyshippingResult() {
        if(is_null($this->_myshippingResult)) {
            if ($this->getQuote() && $this->getQuote()->getShippingAddress()) {
                $shippingAddress = $this->getQuote()->getShippingAddress();
                $myshippingResult = $this->_resultProcessor->create($shippingAddress->getShippingMethod(), $shippingAddress, $shippingAddress->getAllVisibleItems());
            } else {
                $myshippingResult = false;
            }

            $this->_myshippingResult = $myshippingResult;
        }
        return $this->_myshippingResult;
    }


    public function createMyshippingNewComponent(string $componentTemplate, $hideOptionalFields = false, $hideInfo = false) {
        $elements = [];

        $isActive = $this->isActive(Carrier::CODE_NEW);
        $myshippingResult = $this->getMyshippingResult();

        $courierOptions = $this->getCourierOptions();
        $courierMethodOptions = [];
        if($isActive && $myshippingResult->getMyshippingCourierId()) {
            $courierMethodOptions = $this->getCourierMethodOptions($myshippingResult->getCourier());
        }

        $componentName = Carrier::CODE . '_' . Carrier::CODE_METHOD_NEW;
        $dataScope = "myshipping." . Carrier::CODE_METHOD_NEW;

        $componentChildren = [
            'myshipping_courier_id' => [
                'component' => "Magento_Ui/js/form/element/select",
                'config' => [
                    'customScope' => $dataScope,
                    'template' => 'ui/form/field',
                    'elementTmpl' => "ui/form/element/select",
                    'id' => "myshipping_courier_id"
                ],
                'dataScope' => 'myshipping_courier_id',
                'value' => $isActive ? $myshippingResult->getMyshippingCourierId() : 0,
                'initialValue' => true,
                'label' => __("Courier"),
                'options' => $courierOptions,
                'caption' => __('Please select'),
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => [ 'required-entry' => true ],
                'sortOrder' => 10,
                'id' => "myshipping_courier_id"
            ],
            'myshipping_account' => [
                'component' => "Magento_Ui/js/form/element/abstract",
                'config' => [
                    'customScope' => $dataScope,
                    'template' => 'ui/form/field',
                    'elementTmpl' => "MageMaclean_MyShipping/form/element/account",
                    'id' => "myshipping_account"
                ],
                'dataScope' => 'myshipping_account',
                'value' => $isActive ? $myshippingResult->getMyshippingAccount() : "",
                'label' => "Account",
                'provider' => 'checkoutProvider',
                'visible' => !$hideOptionalFields,
                'validation' => $this->_helper->getAccountValidationConfig(),
                'sortOrder' => 20
            ],
            'myshipping_courier_method' => [
                'component' => "Magento_Ui/js/form/element/select",
                'config' => [
                    'customScope' => $dataScope,
                    'template' => 'ui/form/field',
                    'elementTmpl' => "ui/form/element/select",
                    'id' => "myshipping_courier_method",
                ],
                'dataScope' => 'myshipping_courier_method',
                'value' => $isActive ? $myshippingResult->getMyshippingCourierMethod() : "",
                'initialValue' => true,
                'label' => __("Method"),
                'options' => $courierMethodOptions,
                'caption' => 'Please select',
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => [ 'required-entry' => true ],
                'sortOrder' => 30
            ]
        ];

        if($this->_customerSession->hasCustomerId()) {
            $componentChildren['myshipping_save'] = [
                'component' => "Magento_Ui/js/form/element/abstract",
                'config' => [
                    'customScope' => $dataScope,
                    'template' => 'ui/form/field',
                    'elementTmpl' => "ui/form/element/checkbox",
                    'id' => "myshipping_save"
                ],
                'dataScope' => 'myshipping_save',
                'value' => 1,
                'required' => false,
                'label' => "Save shipping account",
                'provider' => 'checkoutProvider',
                'visible' => !$hideOptionalFields,
                'validation' => null,
                'additionalClasses' => 'myshipping-save',
                'sortOrder' => 40
            ];
        }

        if(!$hideInfo && $myshippingInfo = $this->_helper->getSystemConfigData("myshipping/checkout/info_new")) {
            $componentChildren['myshipping_info'] = [
                'component' => "Magento_Ui/js/form/components/html",
                'config' => [
                    'elementTmpl' => "ui/form/element/html",
                    'id' => "myshipping_info"
                ],
                'content' => $myshippingInfo,
                'sortOrder' => 1,
                'additionalClasses' => 'myshipping-info'
            ];
        }

        $elements[$componentName] = [
            // 'component' => 'MageMaclean_MyShipping/js/view/checkout/myshipping-new',
            'component' => $componentTemplate,
            'config' => [
                'customScope' => $dataScope,
            ],
            'dataScope' => $dataScope,
            'provider' => 'checkoutProvider',
            'displayArea' => $componentName,
            'visible' => true,
            'children' => $componentChildren,
            
        ];

        return $elements;
    }


    public function createMyshippingAccountComponents(string $componentTemplate, $hideInfo = false) {
        $elements = [];
        if(!$this->_customerSession->hasCustomerId()) return $elements;

        $myshippingResult = $this->getMyshippingResult();
        $accounts = $this->_accountRepository->getListByCustomerId($this->_customerSession->getCustomerId());

        if($accounts) {
            foreach($accounts as $account) {
                $isActive = $this->isActive($account->getCode());
                $courierMethodOptions = $this->getCourierMethodOptions($account->getCourier());
                $dataScope = "myshipping." . $account->getMethodCode();
                $componentName = Carrier::CODE . '_' . $account->getMethodCode();
                $elements[$componentName] = [
                    // 'component' => 'MageMaclean_MyShipping/js/view/checkout/myshipping-account',
                    'component' => $componentTemplate,
                    'displayArea' => $componentName,
                    'dataScope' => $dataScope,
                    'config' => [
                        'customScope' => $dataScope
                    ],
                    'visible' => true,
                    'provider' => 'checkoutProvider',

                    'children' => [
                        'myshipping_account_id' => [
                            'component' => "Magento_Ui/js/form/element/abstract",
                            'config' => [
                                'customScope' => $dataScope,
                                // 'template' => 'ui/form/field',
                                'elementTmpl' => "ui/form/element/hidden",
                                'id' => "myshipping_account_id"
                            ],
                            'dataScope' => 'myshipping_account_id',
                            'value' => $account->getId(),
                            'initialValue' => $isActive,
                            'label' => __("Account Id"),
                            'provider' => 'checkoutProvider',
                            'visible' => true,
                            'validation' => [ 'required-entry' => true ],
                            'sortOrder' => 11
                        ],
                        'myshipping_courier_id' => [
                            'component' => "Magento_Ui/js/form/element/abstract",
                            'config' => [
                                'customScope' => $dataScope,
                                // 'template' => 'ui/form/field',
                                'elementTmpl' => "ui/form/element/hidden",
                                'id' => "myshipping_courier_id"
                            ],
                            'dataScope' => 'myshipping_courier_id',
                            'value' => $account->getMyshippingCourierId(),
                            'initialValue' => $isActive,
                            'label' => __("Courier"),
                            'provider' => 'checkoutProvider',
                            'visible' => true,
                            'validation' => [ 'required-entry' => true ],
                            'sortOrder' => 10
                        ],
                        'myshipping_account' => [
                            'component' => "Magento_Ui/js/form/element/abstract",
                            'config' => [
                                'customScope' => $dataScope,
                                // 'template' => 'ui/form/field',
                                'elementTmpl' => "ui/form/element/hidden",
                                'id' => "myshipping_account"
                            ],
                            'dataScope' => 'myshipping_account',
                            'value' => $account->getMyshippingAccount(),
                            'initialValue' => $isActive,
                            'label' => __("Account"),
                            'provider' => 'checkoutProvider',
                            'visible' => true,
                            'validation' => [ 'required-entry' => true ],
                            'sortOrder' => 11
                        ],
                        'myshipping_courier_method' => [
                            'component' => "Magento_Ui/js/form/element/select",
                            'config' => [
                                'customScope' => $dataScope,
                                'template' => 'ui/form/field',
                                'elementTmpl' => "ui/form/element/select",
                                'id' => $dataScope . ".myshipping_courier_method",
                            ],
                            'dataScope' => 'myshipping_courier_method',
                            'value' => $isActive ? $myshippingResult->getMyshippingCourierMethod() : "",
                            'initialValue' => $isActive,
                            'label' => __("Method"),
                            'options' => $courierMethodOptions,
                            'caption' => __('Please select'),
                            'provider' => 'checkoutProvider',
                            'visible' => true,
                            'validation' => [ 'required-entry' => true ],
                            'sortOrder' => 20
                        ]
                    ]
                ];

                if(!$hideInfo && $myshippingInfo = $this->_helper->getSystemConfigData("myshipping/checkout/info_accounts")) {
                    $elements[$componentName]['children']['myshipping_info'] = [
                        'component' => "Magento_Ui/js/form/components/html",
                        'config' => [
                            'customScope' => $dataScope,
                            // 'template' => 'ui/form/field',
                            'elementTmpl' => "ui/form/element/html",
                            'id' => "myshipping_info"
                        ],
                        'content' => $myshippingInfo,
                        'sortOrder' => 1,
                        'additionalClasses' => 'myshipping-info'
                    ];
                }
            }

        }
        return $elements;
    }

    public function getCourierOptions() {
        if(is_null($this->_courierOptions)) {
            $options = array();

            $courierCollection = $this->_courierListRepository->getStoreList($this->getStoreId());
            if($courierCollection) {
                foreach($courierCollection as $courier) {
                    if($this->_helper->canUseCourier($courier, $this->getQuote()->getShippingAddress()->getCountryId())) {
                        $options[] = array(
                            'value' => $courier->getId(),
                            'label' => $courier->getTitle()
                        );
                    }
                }
            }
            if(!sizeof($options)) {
                $options[] = array(
                    'value' => 0,
                    'label' => 'No couriers have been configured.'
                );
            }
            $this->_courierOptions = $options;
        }

        return $this->_courierOptions;
    }

    public function getCourierMethodOptions($courier) {
        $methodOptions = [];
        $methods = $courier->getMethods(true);
        if($methods && sizeof($methods)) {
            $methods = $courier->getMethods(true);
            foreach($methods as $methodData) {
                $methodOption = $methodData;
                $methodPrice = $this->_itemPriceCalculator->getShippingPriceByMethodData($this->getQuote()->getShippingAddress()->getAllVisibleItems(), $methodData);

                $methodOption['value'] = $methodData['method_code'];
                $methodOption['price'] = $methodPrice;
                $methodOption['label'] = $methodData['method_name'];
                if($this->_helper->getSystemConfigData('myshipping/checkout/show_method_prices')) {
                    $methodOption['label'] .= ' - ' . $this->_getFormattedPrice($methodPrice);
                }

                $methodOptions[] = $methodOption;
            }
        }
        return $methodOptions;
    }

    protected function _getFormattedPrice($methodPrice) {
        return $this->_priceCurrency->convertAndFormat(
            $this->_taxData->getShippingPrice(
                $methodPrice,
                $this->_taxData->displayShippingPriceIncludingTax($this->getStoreId()),
                $this->getQuote()->getAddress(),
                null,
                $this->getStoreId()
            ),
            false,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $this->getStoreId()
        );
    }
}
