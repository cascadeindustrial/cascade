<?php
namespace MageMaclean\MyShipping\Model;

use Magento\Framework\App\State;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Backend\Model\Session\Quote as QuoteSession;
use Magento\Quote\Model\Quote\Address\RateRequest;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use MageMaclean\MyShipping\Model\Repository\CourierListRepository;
use MageMaclean\MyShipping\Model\Myshipping\ResultFactory;
use MageMaclean\MyShipping\Api\Data\MyshippingResultInterface;
use Magento\Quote\Api\Data\ShippingMethodExtensionFactory;

class Carrier extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    const CODE = 'myshipping';
    const CODE_METHOD_NEW = 'new';
    const CODE_NEW = self::CODE . '_' . self::CODE_METHOD_NEW;

    protected $_code = self::CODE;
    protected $_rateResultFactory;
    protected $_rateMethodFactory;
    protected $_customerSession;
    protected $_checkoutSession;
    protected $_quoteSession;
    protected $_helper;
    protected $_state;
    protected $_accountRepository;
    protected $_courierListRepository;
    protected $_resultFactory;
    protected $_shippingMethodExtension;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        State $state,
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        QuoteSession $quoteSession,
        Helper $helper,
        AccountRepository $accountRepository,
        CourierListRepository $courierListRepository,
        ResultFactory $resultFactory,
        ShippingMethodExtensionFactory $shippingMethodExtensionFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_state = $state;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_quoteSession = $quoteSession;
        $this->_helper = $helper;
        $this->_accountRepository = $accountRepository;
        $this->_courierListRepository = $courierListRepository;
        $this->_resultFactory = $resultFactory;
        $this->_shippingMethodExtension = $shippingMethodExtensionFactory;

        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    public function getAllowedMethods()
    {
        if(!$this->isEnabled()) return [];

        return ['myshipping' => $this->_helper->getConfigData('name')];
    }

    public function getQuote() {
        if($this->_isAdmin()) {
            return $this->_quoteSession->getQuote();
        } else {
            return $this->_checkoutSession->getQuote();
        }
    }

    private function _isAdmin() {
        return ($this->_state->getAreaCode() === \Magento\Framework\App\Area::AREA_ADMINHTML) ? true : false;
    }

    protected function _hasStoreCouriers() {
        return $this->_courierListRepository->getHasStoreCouriers($this->getStoreId());
    }

    public function isEnabled() {
        if(!$this->_helper->isEnabled()) return false;
        
        if($this->_isAdmin()) {
            if(!$this->_helper->getSystemConfigData("myshipping/sales/enabled", $this->getStoreId())) return false;

            return true;
        } else {
            if(!$this->_helper->getSystemConfigData("myshipping/checkout/enabled", $this->getStoreId())) return false;
            
            return true;
        }
    }

    public function isNewEnabled() {
        if(!$this->_helper->isEnabled()) return false;
        
        if($this->_isAdmin()) {
            if(!$this->_helper->getSystemConfigData("myshipping/sales/enabled", $this->getStoreId())) return false;
            if(!$this->_helper->getSystemConfigData("myshipping/sales/new_enabled", $this->getStoreId())) return false;

            return $this->getCustomerId() ? true : $this->_helper->getSystemConfigData("myshipping/sales/guest_new_enabled", $this->getStoreId());
        } else {
            if(!$this->_helper->getSystemConfigData("myshipping/checkout/enabled", $this->getStoreId())) return false;
            if(!$this->_helper->getSystemConfigData("myshipping/checkout/new_enabled", $this->getStoreId())) return false;
            
            return $this->getCustomerId() ? true : $this->_helper->getSystemConfigData("myshipping/checkout/guest_new_enabled", $this->getStoreId());
        }
    }

    public function getStoreId() {
        return $this->getQuote()->getStoreId();
    }

    public function getCustomerId() {
        if($this->_isAdmin()) {
            if(!$this->_quoteSession->hasCustomerId()) {
                return false;
            }
            $customerId = $this->_quoteSession->getCustomerId();
        } else {
            if(!$this->_customerSession->hasCustomerId()) {
                return false;
            }
            $customerId = $this->_customerSession->getCustomerId();
        }

        return $customerId;
    }

    public function getAccounts() {
        if(!$this->getCustomerId()) return false;

        return $this->_accountRepository->getListByCustomerId($this->getCustomerId());
    }

    public function collectRates(RateRequest $request)
    {
        $result = $this->_rateResultFactory->create();
        if($this->isEnabled()) {
            if(!$this->_hasStoreCouriers()) {
                if($this->getConfigData("showmethod")) {
                    /** @var \Magento\Quote\Model\Quote\Address\RateResult\Error $error */
                    $error = $this->_rateErrorFactory->create(
                        [
                            'data' => [
                                'carrier' => $this->_code,
                                'carrier_title' => $this->getConfigData('new_title'),
                                'error_message' => $this->getConfigData('specificerrmsg'),
                            ],
                        ]
                    );
                    $result->append($error);
                    return $result;
                } else {
                    return false;
                }
            }

            
            if($accounts = $this->getAccounts()) {
                foreach($accounts as $account) {
                    if($this->_helper->canUseCourier($account->getCourier(), $request->getDestCountryId())) {
                        $myshippingResult = $this->_resultFactory->create();
                        $myshippingResult->setMyshippingAccountId($account->getId());
                        $myshippingResult->setMyshippingCourierId($account->getMyshippingCourierId());
                        $myshippingResult->setMyshippingAccount($account->getMyshippingAccount());
                        $myshippingResult->setItems($request->getAllItems());

                        $method = $this->_createResultMethod($myshippingResult);
                        $result->append($method);
                    }
                }
            }
        }

        if($this->isNewEnabled()) {
            $myshippingResult = $this->_resultFactory->create();
            $myshippingResult->setItems($request->getAllItems());
            $method = $this->_createResultMethod($myshippingResult);
            
            $result->append($method);
        }

        return $result;
    }

    protected function _createResultMethod(MyshippingResultInterface $myshippingResult) {
        $shippingPrice = $myshippingResult->getShippingPrice();

        $method = $this->_rateMethodFactory->create();
        $method->setCarrier($myshippingResult->getCarrier());
        $method->setCarrierTitle($myshippingResult->getCarrierTitle());
        $method->setMethod($myshippingResult->getMethod());
        $method->setMethodTitle($myshippingResult->getMethodTitle());
        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);

        return $method;
    }
}
