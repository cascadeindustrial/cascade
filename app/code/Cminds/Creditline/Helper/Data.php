<?php


namespace Cminds\Creditline\Helper;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Cminds\Creditline\Model\BalanceFactory;
use Cminds\Creditline\Model\ResourceModel\Balance\CollectionFactory as BalanceCollectionFactory;
use Cminds\Creditline\Model\Config;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Data extends AbstractHelper
{
    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var BalanceCollectionFactory
     */
    protected $balanceCollectionFactory;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param CustomerSession          $customerSession
     * @param CheckoutSession          $checkoutSession
     * @param BalanceFactory           $balanceFactory
     * @param BalanceCollectionFactory $balanceCollectionFactory
     * @param Config                   $config
     * @param Context                  $context
     */
    public function __construct(
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        BalanceFactory $balanceFactory,
        BalanceCollectionFactory $balanceCollectionFactory,
        StoreManagerInterface $storeManager,
        Config $config,
        Context $context
    ) {
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->balanceFactory = $balanceFactory;
        $this->balanceCollectionFactory = $balanceCollectionFactory;
        $this->storeManager = $storeManager;
        $this->config = $config;

        parent::__construct($context);
    }

    /**
     * @param int    $customerId
     * @param string $currencyCode
     * @return Balance
     */
    public function getBalance($customerId = null, $currencyCode = null)
    {
        if (!$customerId) {
            $customerId = $this->customerSession->getCustomerId();
        }
        if (!$currencyCode) {
            $currencyCode = $this->storeManager->getStore()->getCurrentCurrencyCode();
        }

        return $this->balanceFactory->create()
            ->loadByCustomer($customerId, $currencyCode);
    }

    /**
     * @param int $customerId
     * @return Collection
     */
    public function getCustomerBalances($customerId)
    {
        return $this->balanceCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId);
    }

    /**
     * @return Quote
     */
    public function getQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    /**
     * @return bool
     */
    public function isAutoRefundEnabled()
    {
        return $this->config->isAutoRefundEnabled();
    }

    /**
     * Check that shopping cart not contains store credit products
     *
     * @return bool
     */
    public function isAllowedForQuote()
    {
		if(!$this->config->isEnabled()){
            return false;
        }
        
        if (!$this->customerSession->getCustomerId()) {
            return false;
        }
        
        if(!$this->config->isExistGroup()){
            return false;
        }
        /** @var CartItemInterface $item */
        foreach ($this->getQuote()->getItemsCollection() as $item) {
            if ($this->config->getRefillProduct() &&
                $item->getProductId() == $this->config->getRefillProduct()->getId()
            ) {
                return false;
            }
        }

        return true;
    }
}
