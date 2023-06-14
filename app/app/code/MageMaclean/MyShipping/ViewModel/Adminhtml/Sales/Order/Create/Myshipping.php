<?php
namespace MageMaclean\MyShipping\ViewModel\Adminhtml\Sales\Order\Create;

use MageMaclean\MyShipping\Model\Myshipping\ItemPriceCalculator;
use Magento\Backend\Model\Session\Quote as AdminQuote;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Carrier;
use MageMaclean\MyShipping\Model\Repository\CourierListRepository;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use MageMaclean\MyShipping\Model\Myshipping\ResultFactory;
use MageMaclean\MyShipping\Model\Myshipping\ResultProcessor;

class Myshipping implements ArgumentInterface
{
    protected $_quote;
    protected $_priceCurrency;
    protected $_taxData;

    protected $_helper;
    protected $_accountRepository;
    protected $_courierListRepository;
    protected $_resultFactory;
    protected $_resultProcessor;
    protected $_itemPriceCalculator;

    protected $_courierCollection;
    protected $_courierOptions;
    protected $_courierMethodOptions;

    protected $_myshippingNewData;
    protected $_myshippingAccountData;

    public function __construct(
        AdminQuote $quote,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Tax\Helper\Data $taxData,
        Helper $helper,
        AccountRepository $accountRepository,
        CourierListRepository $courierListRepository,
        ResultFactory $resultFactory,
        ResultProcessor $resultProcessor,
        ItemPriceCalculator $itemPriceCalculator
    )
    {
        $this->_quote = $quote;
        $this->_priceCurrency = $priceCurrency;
        $this->_taxData = $taxData;

        $this->_helper = $helper;
        $this->_accountRepository = $accountRepository;
        $this->_courierListRepository = $courierListRepository;
        $this->_resultFactory = $resultFactory;
        $this->_resultProcessor = $resultProcessor;
        $this->_itemPriceCalculator = $itemPriceCalculator;
    }

    public function canUseMyshipping() {
        return $this->_helper->isEnabled();
    }

    public function getQuote() {
        return $this->_quote->getQuote();
    }

    public function getStoreId() {
        return $this->getQuote()->getStoreId();
    }

    public function getAccounts() {
        if(!$customerId = $this->getQuote()->getCustomerId()) return false;

        return $this->_accountRepository->getListByCustomerId($customerId);
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
                    if($this->_helper->canUseCourier($courier, $this->getQuote()->getShippingAddress()->getCountryId())) {
                        $options[] = array(
                            'value' => $courier->getId(),
                            'label' => $courier->getTitle()
                        );
                    }
                }
            }

            if(!$options || !sizeof($options)) {
                $options[] = array(
                    'value' => 0,
                    'label' => __('No couriers available for this country.')
                );
            }

            $this->_courierOptions = $options;
        }

        return $this->_courierOptions;
    }

    public function getCourierOptionsJson() {
        return json_encode($this->getCourierOptions());
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
                        foreach($methods as $methodData) {
                            $methodOption = $methodData;
                            $methodPrice = $this->_itemPriceCalculator->getShippingPriceByMethodData($this->getQuote()->getShippingAddress()->getAllVisibleItems(), $methodData);
                            $methodOption['value'] = $methodData['method_code'];
                            $methodOption['price'] = $methodPrice;
                            $methodOption['label'] = $methodData['method_name'];
                            if($this->_helper->getSystemConfigData('myshipping/sales/show_method_prices')) {
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

    public function getAllCourierMethodOptionsJson() {
        return json_encode($this->getAllCourierMethodOptions());
    }

    public function getCourierMethodOptions($courierId)
    {
        $allCourierMethodOptions = $this->getAllCourierMethodOptions($this->getStoreId());
        if(isset($allCourierMethodOptions[$courierId]))
            return $allCourierMethodOptions[$courierId];
        else
            return [];
    }

    public function getCourierMethodOptionsJson($courierId) {
        return json_encode($this->getCourierMethodOptions($courierId));
    }

    public function getShippingMethod() {
        if($this->getQuote() && $this->getQuote()->getShippingAddress()) {
            return $this->getQuote()->getShippingAddress()->getShippingMethod();
        } else {
            return "";
        }
    }

    protected function myshippingResultToData($myshippingResult) {
        $data = $myshippingResult->getData();
        $data['carrier_title'] = $myshippingResult->getCarrierTitle();
        $data['method_title'] = $myshippingResult->getMethodTitle();
        $data['price'] = $this->_getFormattedPrice($myshippingResult->getShippingPrice());

        return $data;
    }

    public function getMyshippingNewData() {
        if(is_null($this->_myshippingNewData)) {
            $this->_myshippingNewData = false;
            if($this->getShippingMethod() == Carrier::CODE_NEW) {
                $myshippingResult = $this->_resultProcessor->create($this->getShippingMethod(), $this->getQuote()->getShippingAddress(), $this->getQuote()->getAllVisibleItems());
                $this->_myshippingNewData = $this->myshippingResultToData($myshippingResult);
            }
        }
        return $this->_myshippingNewData;
    }

    public function getMyshippingAccountData(\MageMaclean\MyShipping\Model\Account $account) {
        if(is_null($this->_myshippingAccountData)) {
            $this->_myshippingAccountData = [];
        }

        if(!isset($this->_myshippingAccountData[$account->getId()])) {
            $this->_myshippingAccountData[$account->getId()] = false;
            if($this->getShippingMethod() == $account->getCode()) {
                $myshippingResult = $this->_resultProcessor->create($this->getShippingMethod(), $this->getQuote()->getShippingAddress(), $this->getQuote()->getAllVisibleItems());
                $this->_myshippingAccountData[$account->getId()] = $this->myshippingResultToData($myshippingResult);
            }
        }

        return $this->_myshippingAccountData[$account->getId()];
    }

    public function getMyshippingActiveData() {
        if($myshippingNewData = $this->getMyshippingNewData()) {
            return $myshippingNewData;
        } else if($this->getAccounts()) {
            foreach($this->getAccounts() as $account) {
                if($myshippingAccountData = $this->getMyshippingAccountData($account)) {
                    return $myshippingAccountData;
                }
            }
        }
        return false;
    }

    public function getAccountValidationClasses() {
        return $this->_helper->getAccountValidationClasses();
    }

    public function getAccountValidationConfigJson() {
        return json_encode($this->_helper->getAccountValidationConfig());
    }

    public function canShowMyshippingSave() {
        return (!$this->getQuote()->getCustomerIsGuest() && $this->getQuote()->getCustomerId());
    }
}
