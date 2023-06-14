<?php

namespace Cminds\Creditline\Block\Adminhtml\Sales\Order\Create;

use Cminds\Creditline\Model\Config;
use Magento\Framework\View\Element\Template;
use Cminds\Creditline\Model\BalanceFactory;
use Magento\Sales\Model\AdminOrder\Create;
use Magento\Backend\Model\Session\Quote;
use Magento\Backend\Block\Widget\Context;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Payment extends Template
{
    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var Create
     */
    protected $salesOrderCreate;

    /**
     * @var Quote
     */
    protected $sessionQuote;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param BalanceFactory  $balanceFactory
     * @param Create          $salesOrderCreate
     * @param Quote           $sessionQuote
     * @param Context         $context
     * @param array           $data
     */
    public function __construct(
        BalanceFactory $balanceFactory,
        Create $salesOrderCreate,
        Quote $sessionQuote,
        Context $context,
        array $data = []
    ) {
        $this->balanceFactory = $balanceFactory;
        $this->salesOrderCreate = $salesOrderCreate;
        $this->sessionQuote = $sessionQuote;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @var Balance
     */
    protected $balance;

    /**
     * @return Create
     */
    protected function _getOrderCreateModel()
    {
        return $this->salesOrderCreate;
    }

    /**
     * @param float $value
     * @return string
     */
    public function formatPrice($value)
    {
        return $this->sessionQuote->getOrder()->formatPrice($value);
    }

    /**
     * @return bool|float
     */
    public function getBalance()
    {
        if (!$this->_getBalance()) {
            return false;
        }

        return $this->_getBalance()->getAmount();
    }

    /**
     * @return bool|int
     */
    public function getUseCredit()
    {
        return $this->_getOrderCreateModel()->getQuote()->getUseCredit() == Config::USE_CREDIT_YES;
    }

    /**
     * @return bool
     */
    public function isFullyPaid()
    {
        if (!$this->_getBalance()) {
            return false;
        }

        return $this->_getBalance()->isFullAmountCovered($this->_getOrderCreateModel()->getQuote());
    }

    /**
     * @return bool
     */
    public function canUseCredit()
    {
        return $this->getBalance() > 0;
    }

    /**
     * @return bool|Balance
     */
    protected function _getBalance()
    {
        if (!$this->balance) {
            $quote = $this->_getOrderCreateModel()->getQuote();

            if (!$quote || !$quote->getCustomerId()) {
                return false;
            }

            $this->balance = $this->balanceFactory->create()->loadByCustomer(
                $quote->getCustomerId(), $quote->getQuoteCurrencyCode()
            );
        }

        return $this->balance;
    }

    /**
     * @return bool
     */
    public function canUseCustomerBalance()
    {
        $quote = $this->_getOrderCreateModel()->getQuote();

        return $this->getBalance() && ($this->getBalance() >= $quote->getBaseGrandTotal());
    }

    /**
     * @return string
     */
    public function getFreePaymentMethod()
    {
        return Config::FREE_METHOD;
    }
}
