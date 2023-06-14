<?php
namespace MageMaclean\MyShipping\Model\Checkout;

use MageMaclean\MyShipping\Model\Myshipping\ItemPriceCalculator;
use \Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use MageMaclean\MyShipping\Model\Repository\CourierListRepository;
use MageMaclean\MyShipping\Model\Myshipping\ResultFactory;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CustomerSession
     */
    protected $_customerSession;

    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;

    /**
     * @var PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @var \Magento\Tax\Helper\Data
     */
    protected $_taxData;

    protected $_helper;
    protected $_accountRepository;
    protected $_courierListRepository;
    protected $_resultFactory;
    protected $_itemPriceCalculator;

    protected $_accounts;
    protected $_courierCollection;
    protected $_courierMethodOptions;
    protected $_courierOptions;


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
        $this->_itemPriceCalculator = $itemPriceCalculator;
    }

    public function getStoreId()
    {
        return $this->storeManager->getStore()->getStoreId();
    }

    public function getQuote() {
        return $this->_checkoutSession->getQuote();
    }

    public function getConfig() {
        $config = [
            'myshipping' => [
                'allCourierOptions' => $this->getCourierOptions(),
                'allCourierMethodOptions' => $this->getAllCourierMethodOptions()
            ]
        ];

        return $config;
    }

    public function getAccounts() {
        if(is_null($this->_accounts)) {
            $accounts = [];
            if($this->_customerSession->hasCustomerId()) {
                $accountCollection = $this->_accountRepository->getListByCustomerId($this->_customerSession->getCustomerId());
                if($accountCollection) {
                    foreach($accountCollection as $account) {
                        $accountData = $account->getData();
                        $accounts[$account->getMethodCode()] = $accountData;
                    }
                }
            }
            $this->_accounts = $accounts;
        }
        return $this->_accounts;
    }

    public function getCourierCollection() {
        if(is_null($this->_courierCollection)) {
            $this->_courierCollection = $this->_courierListRepository->getStoreList($this->getStoreId());
        }
        return $this->_courierCollection;
    }

    public function getCourierOptions() {
        if(is_null($this->_courierOptions)) {
            $options = array();

            $courierCollection = $this->getCourierCollection();
            if($courierCollection) {
                foreach($courierCollection as $courier) {
                    $options[] = array(
                        'value' => $courier->getId(),
                        'label' => $courier->getTitle(),
                        'title' => $courier->getTitle()
                    );
                }
            } else {
                $options[] = array(
                    'value' => 0,
                    'label' => 'No couriers have been configured.',
                    'title' => ''
                );
            }
            $this->_courierOptions = $options;
        }

        return $this->_courierOptions;
    }

    public function getAllCourierMethodOptions() {
        if(is_null($this->_courierMethodOptions)) {
            $courierCollection = $this->getCourierCollection();
            $methodOptions = array();

            if($courierCollection) {
                foreach($courierCollection as $courier) {
                    $methodOptions[$courier->getId()] = array();
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
                            $methodOptions[$courier->getId()][] = $methodOption;
                        }
                    }
                }
            }
            $this->_courierMethodOptions = $methodOptions;
        }

        return $this->_courierMethodOptions;
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
