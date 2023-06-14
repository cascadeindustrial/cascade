<?php

namespace Cminds\Creditline\Block\Adminhtml\Transaction;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Cminds\Creditline\Model\ResourceModel\Balance\CollectionFactory as BalanceCollectionFactory;
use Cminds\Creditline\Model\ResourceModel\Transaction\CollectionFactory as TransactionCollectionFactory;
use Magento\Framework\Locale\CurrencyInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class QuickReport extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Cminds_Creditline::transaction/quick_report.phtml';
    /**
     * @var BalanceCollectionFactory
     */
    private $balanceCollectionFactory;

    /**
     * @var TransactionCollectionFactory
     */
    private $transactionCollectionFactory;

    /**
     * @var CurrencyInterface
     */
    private $currency;

    /**
     * @var Context
     */
    private $context;

    public function __construct(
        BalanceCollectionFactory $balanceCollectionFactory,
        TransactionCollectionFactory $transactionCollectionFactory,
        CurrencyInterface $currency,
        Context $context
    ) {
        $this->balanceCollectionFactory = $balanceCollectionFactory;
        $this->transactionCollectionFactory = $transactionCollectionFactory;
        $this->currency = $currency;
        $this->context = $context;

        parent::__construct($context);
    }

    /**
     * @return float
     */
    public function getTotalBalance()
    {
        return $this->balanceCollectionFactory->create()->getTotalBalance();
    }

    /**
     * @return float
     */
    public function getTotalUsedBalance()
    {
        return $this->transactionCollectionFactory->create()->getTotalUsedAmount();
    }

    /**
     * @param number $amount
     * @return string
     */
    public function getCurrency($amount)
    {
        return $this->currency->getCurrency($this->context->getStoreManager()->getStore()->getBaseCurrencyCode())
            ->toCurrency($amount);
    }
}