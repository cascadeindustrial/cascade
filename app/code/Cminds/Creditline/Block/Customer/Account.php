<?php
namespace Cminds\Creditline\Block\Customer;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;
use Cminds\Creditline\Model\Config;
use Cminds\Creditline\Helper\Data;
use Cminds\Creditline\Model\BalanceFactory;
use Cminds\Creditline\Model\ResourceModel\Transaction\CollectionFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\DataObject;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Account extends Template
{
    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var CollectionFactory
     */
    protected $transactionCollectionFactory;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Context
     */
    protected $context;

    protected $creditHelper;

    public function __construct(
        Data $creditHelper,
        BalanceFactory $balanceFactory,
        CollectionFactory $transactionCollectionFactory,
        CustomerFactory $customerFactory,
        Session $customerSession,
        Config $config,
        PriceCurrencyInterface $priceCurrencyHelper,
        Context $context
    ) {
        $this->creditHelper                 = $creditHelper;
        $this->balanceFactory               = $balanceFactory;
        $this->transactionCollectionFactory = $transactionCollectionFactory;
        $this->customerFactory              = $customerFactory;
        $this->customerSession              = $customerSession;
        $this->config                       = $config;
        $this->priceCurrencyHelper          = $priceCurrencyHelper;
        $this->context                      = $context;

        parent::__construct($context);

        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle(__('Credit Line'));
        }
    }

    /**
     * @return Customer
     */
    protected function getCustomer()
    {
        return $this->customerFactory->create()->load($this->customerSession->getCustomerId());
    }

    /**
     * @return Balance
     */
    public function getBalance()
    {
        return $this->creditHelper->getBalance($this->getCustomer()->getId());
    }

    /**
     * @return bool
     */
    public function hasSeveralBalances()
    {
        return $this->creditHelper->getCustomerBalances($this->getCustomer()->getId())->count() > 1;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionCollection()
    {
        return $this->transactionCollectionFactory->create()
            ->addFieldToFilter('main_table.balance_id', $this->getBalance()->getId())
            ->setOrder('main_table.created_at', 'desc')
            ->setOrder('main_table.transaction_id', 'desc');
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getLastTransaction()
    {
        return $this->transactionCollectionFactory->create()
            ->addFieldToFilter('main_table.balance_id', $this->getBalance()->getId())
            ->addFieldToFilter('main_table.action', array('neq' => 'used'))
            ->setOrder('main_table.balance_id', 'desc')
            ->getLastItem();
    }

    /**
     * @return string
     */
    public function getSend2FriendUrl()
    {
        return $this->context->getUrlBuilder()->getUrl('creditline/account/send2friend');
    }

    /**
     * @return DataObject
     */
    public function getSend2FriendFormData()
    {
        return new DataObject((array)$this->customerSession->getSend2FriendFormData());
    }

    /**
     * @return bool
     */
    public function isSendBalanceUpdate()
    {
        return $this->config->isSendBalanceUpdate();
    }

    /**
     * @return bool
     */
    public function isEnableShareCredit()
    {
        return $this->config->isEnableSendFriend();
    }    

    /**
     * @return string
     */
    public function getStoreCurrencyCode()
    {
        return $this->context->getStoreManager()->getStore()->getCurrentCurrencyCode();
    }

    /**
     * @param float  $price
     * @param string $currencyCode
     * @param string $baseCurrencyCode
     * @return string
     */
    public function formatPrice($price, $currencyCode, $baseCurrencyCode = null)
    {
        if ($baseCurrencyCode && $baseCurrencyCode != $currencyCode) {
            return $this->priceCurrencyHelper->convertAndFormat(
                $price, false, PriceCurrencyInterface::DEFAULT_PRECISION, null, $currencyCode
            );
        } else {
            return $this->priceCurrencyHelper->format(
                $price, false, PriceCurrencyInterface::DEFAULT_PRECISION, null, $currencyCode
            );
        }
    }
}
