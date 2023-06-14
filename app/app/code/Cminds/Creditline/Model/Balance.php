<?php


namespace Cminds\Creditline\Model;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Cminds\Creditline\Helper\Email as EmailHelper;
use Cminds\Creditline\Helper\Message as MessageHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
/**
 * @method int getBalanceId()
 * @method int getCustomerId()
 * @method $this setCustomerId(int $id)
 * @method float getAmount()
 * @method $this setAmount(float $amount)
 * @method string getCurrencyCode()
 * @method $this setCurrencyCode(string $code)
 * @method string getTransactionCurrencyCode()
 * @method $this setTransactionCurrencyCode(string $code)
 * @method bool getIsSubscribed()
 * @method $this setIsSubscribed(bool $status)
 */
/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Balance extends AbstractModel
{
    /**
     * string
     */
    const SHARE_BALANCE_GLOBAL = 'balance_global';

    /**
     * string
     */
    const SHARE_BALANCE_CURRENCY = 'balance_currency';

    /**
     * @var Collection
     */
    protected $transactionCollection;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var CurrencyFactory
     */
    protected $dirCurrencyFactory;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var TransactionFactory
     */
    protected $transactionFactory;

    /**
     * @var CollectionFactory
     */
    protected $transactionCollectionFactory;

    /**
     * @var CollectionFactory
     */
    protected $balanceCollectionFactory;

    /**
     * @var EmailHelper
     */
    protected $emailHelper;

    /**
     * @var MessageHelper
     */
    protected $messageHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Config $config
     * @param CurrencyFactory $dirCurrencyFactory
     * @param CustomerFactory $customerFactory
     * @param TransactionFactory $transactionFactory
     * @param CollectionFactory $transactionCollectionFactory
     * @param CollectionFactory $balanceCollectionFactory
     * @param EmailHelper $emailHelper
     * @param MessageHelper $messageHelper
     * @param PriceCurrencyInterface $priceCurrencyHelper
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Config $config,
        CurrencyFactory $dirCurrencyFactory,
        CustomerFactory $customerFactory,
        TransactionFactory $transactionFactory,
        ResourceModel\Transaction\CollectionFactory $transactionCollectionFactory,
        ResourceModel\Balance\CollectionFactory $balanceCollectionFactory,
        ScopeConfigInterface $scopeConfig,
        EmailHelper $emailHelper,
        MessageHelper $messageHelper,
        StoreManagerInterface $storeManager,
        PriceCurrencyInterface $priceCurrencyHelper,
        Context $context,
        Registry $registry
    ) {
        $this->config                       = $config;
        $this->dirCurrencyFactory           = $dirCurrencyFactory;
        $this->customerFactory              = $customerFactory;
        $this->transactionFactory           = $transactionFactory;
        $this->transactionCollectionFactory = $transactionCollectionFactory;
        $this->balanceCollectionFactory     = $balanceCollectionFactory;
        $this->scopeConfig                  = $scopeConfig;
        $this->emailHelper                  = $emailHelper;
        $this->messageHelper                = $messageHelper;
        $this->storeManager                 = $storeManager;
        $this->priceCurrencyHelper          = $priceCurrencyHelper;

        parent::__construct($context, $registry);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cminds\Creditline\Model\ResourceModel\Balance');
    }

    /**
     * @param bool $emptyOption
     * @return array
     */
    public function toOptionArray($emptyOption = false)
    {
        return $this->getCollection()->toOptionArray($emptyOption);
    }

    /**
     * @return Collection
     */
    public function getTransactionCollection()
    {
        if (!$this->transactionCollection) {
            $this->transactionCollection = $this->transactionCollectionFactory->create()
                ->addFieldToFilter('main_table.balance_id', $this->getBalanceId());
        }

        return $this->transactionCollection;
    }

    /**
     * @return Customer|false
     */
    public function getCustomer()
    {
        if (!$this->getCustomerId()) {
            return false;
        }

        if ($this->customer === null) {
            $this->customer = $this->customerFactory->create()->load($this->getCustomerId());
        }

        return $this->customer;
    }

    /**
     * @param int|Customer $customer
     * @param string       $currencyCode
     * @return Balance
     */
    public function loadByCustomer($customer, $currencyCode)
    {
        if ($customer instanceof Customer) {
            $customer = $customer->getId();
        }

        // if is new customer
        if (!$customer) {
            return $this;
        }

        $baseCurrencyCode = $this->storeManager->getStore()->getBaseCurrencyCode();
        if ($this->config->getShareBalance() != self::SHARE_BALANCE_CURRENCY) {
            $currencyCode = $baseCurrencyCode;
        }
        /** @var Balance $balance */
        $balance = $this->balanceCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customer)
            ->addFieldToFilter('currency_code', $currencyCode)
            ->getFirstItem();

        if ($balance->getBalanceId()) {
            return $balance;
        }

        $limitamount = $this->scopeConfig->getValue('creditline/general/creditline_default',ScopeInterface::SCOPE_STORE);
        $amount = $limitamount;
        $creditterm = $this->scopeConfig->getValue('creditline/general/credit_term',ScopeInterface::SCOPE_STORE);
        $pmtreminder = $this->scopeConfig->getValue('creditline/general/creditline_select',ScopeInterface::SCOPE_STORE);
        $reminders = $this->scopeConfig->getValue('creditline/general/number_of_days',ScopeInterface::SCOPE_STORE);
        $this->setCustomerId($customer)
            ->setIsSubscribed(true)
            ->setAmount($amount)
            ->setLimitAmount($limitamount)
            ->setCreditTerm($creditterm)
            ->setReminders($reminders)
            ->setPaymentType($pmtreminder)
            ->setCurrencyCode($currencyCode)
            ->save();

        return $this;
    }

    /**
     * @param float  $balanceDelta
     * @param float  $baseBalanceDelta
     * @param string $action
     * @param string $message
     * @param bool   $forceEmail
     * @return $this|bool
     */
    public function addTransaction($balanceDelta, $baseBalanceDelta, $action = null, $message = null, $forceEmail = false)
    {
        $balanceDelta = floatval($balanceDelta);

        if ($balanceDelta == 0) {
            return false;
        }

        if ($action == null) {
            $action = Transaction::ACTION_MANUAL;
        }

        if (is_array($message)) {
            $message = $this->messageHelper->createTransactionMessage($message);
        }

        if ($this->config->getShareBalance() == self::SHARE_BALANCE_CURRENCY) {
            $this->setAmount($this->getAmount() + $balanceDelta);
        } else {
            $this->setAmount($this->getAmount() + $baseBalanceDelta);
        }

        /** @var Transaction $transaction */
        $transaction = $this->transactionFactory->create()
            ->setBalanceId($this->getId())
            ->setBalanceDelta($balanceDelta)
            ->setBalanceAmount($this->getAmount())
            ->setAction($action)
            ->setMessage($message)
            ->setCurrencyCode($this->getTransactionCurrencyCode())
            ->save();

        $this->save();
        if ($this->emailHelper->sendBalanceUpdateEmail($transaction, $forceEmail)) {
            $transaction->setIsNotified(true)
                ->save();
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function recalculateBalance()
    {
        if (!$this->getId()) {
            return false;
        }
        $select = $this->transactionCollectionFactory->create()->getSelect();
        $select->reset(Select::ORDER)
            ->reset(Select::LIMIT_COUNT)
            ->reset(Select::LIMIT_OFFSET)
            ->reset(Select::COLUMNS)
            ->columns('SUM(balance_delta)')
            ->where('main_table.balance_id=?', $this->getId());

        $balance = abs($select->query()->fetchColumn());
        $this->setAmount($balance)->save();

        return true;
    }
}
