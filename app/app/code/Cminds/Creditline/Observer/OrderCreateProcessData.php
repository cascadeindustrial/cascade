<?php

namespace Cminds\Creditline\Observer;

use Cminds\Creditline\Helper\Data;
use Cminds\Creditline\Model\Config;
use Cminds\Creditline\Helper\Calculation;
use Magento\Framework\Event\Observer;
use Magento\Framework\Model\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class OrderCreateProcessData extends AbstractObserver
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var Calculation
     */
    private $calculationHelper;

    /**
     * @var array
     */
    private $processedShippingAddresses = [];

    /**
     * @var int
     */
    private $amountUsed = 0;

    /**
     * @var int
     */
    private $baseAmountUsed = 0;

    /**
     * OrderCreateProcessData constructor.
     * @param Data $creditHelper
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        Data $creditHelper,
        Calculation $calculationHelper,
        Context $context,
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
        $this->calculationHelper = $calculationHelper;
        parent::__construct($creditHelper, $context, $storeManager);
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $quote = $observer->getEvent()->getOrderCreateModel()->getQuote();
        $request = $observer->getEvent()->getRequest();

        if (isset($request['payment']) && isset($request['payment']['use_credit'])) {
            $this->_importPaymentData($quote, $quote->getPayment(), (bool) $request['payment']['use_credit']);
        }
    }

    /**
     * @param Quote   $quote
     * @param Payment $payment
     * @param bool    $isUseCredit
     * @return void
     */
    protected function _importPaymentData($quote, $payment, $isUseCredit)
    {
        if (!$quote || !$quote->getCustomerId()) {
            return;
        }

        $quote->setUseCredit($isUseCredit ? Config::USE_CREDIT_YES : Config::USE_CREDIT_NO);

        if ($isUseCredit) {
            $balance = $this->creditHelper->getBalance($quote->getCustomerId(), $quote->getQuoteCurrencyCode());
            if ($balance) {
                $quote->setBalanceInstance($balance)->save();

                $this->_recollectBalance($quote, $balance);

                if (!$payment->getMethod() || $payment !== Config::FREE_METHOD) {
                    $payment->setMethod(Config::FREE_METHOD);
                    $request['payment']['method'] = Config::FREE_METHOD;
                    $this->request->setPostValue('payment', $request['payment']);
                }
            } else {
                $quote->setUseCredit(Config::USE_CREDIT_NO);
            }
        }
    }

    /**
     * @param $quote
     * @param $balance
     */
    protected function _recollectBalance($quote, $balance)
    {
        $quote->setBaseCreditlineAmountUsed(0)
            ->setCreditlineAmountUsed(0)
            ->save();
        $address = $quote->getShippingAddress();
        $this->resetMultishippingTotalsOnRecollection($quote, $address->getId());

        $restBalance = $balance->getAmount();
        if ($quote->getManualUsedCredit() > 0 && $restBalance >= $quote->getManualUsedCredit()) {
            $restBalance = $quote->getManualUsedCredit();
        }

        $balance->setTransactionCurrencyCode($quote->getQuoteCurrencyCode());
        $baseRestBalance = $this->calculationHelper->convertToCurrency(
            $restBalance, $quote->getQuoteCurrencyCode(), $balance->getCurrencyCode(), $quote->getStore()
        );

        $customerUsed = $restBalance;
        $customerBaseUsed = $baseRestBalance;
        if ($quote->getIsMultiShipping()) {
            $restBalance -= $this->amountUsed;
            $baseRestBalance -= $this->baseAmountUsed;
        } else {
            $this->amountUsed = $restBalance;
            $this->baseAmountUsed = $baseRestBalance;
        }

        $quoteTotal = $address->getGrandTotal();
        $quoteBaseTotal = $address->getBaseGrandTotal();

        if ($restBalance > $quoteTotal && $quoteTotal >= 0) {
            $restBalance     = $quoteTotal;
            $baseRestBalance = $quoteBaseTotal;
        }

        $maxUsed = $this->calculationHelper->calc(
            $quoteTotal, $address->getTaxAmount(), $address->getShippingAmount()
        );
        if ($maxUsed < $restBalance) {
            $restBalance = $maxUsed;
        }
        $maxBaseUsed = $this->calculationHelper->calc(
            $quoteBaseTotal, $address->getBaseTaxAmount(), $address->getBaseShippingAmount()
        );
        if ($maxBaseUsed < $baseRestBalance) {
            $baseRestBalance = $maxBaseUsed;
        }

        if ($quote->getIsMultiShipping()) {
            $this->amountUsed += $restBalance;
            $this->baseAmountUsed += $baseRestBalance;

            if ($this->amountUsed > $customerUsed) {
                $this->amountUsed = $customerUsed;
                $this->baseAmountUsed = $customerBaseUsed;
            }

            $this->processedShippingAddresses[$address->getId()] = $restBalance;
        } else {
            $this->amountUsed = $restBalance;
            $this->baseAmountUsed = $baseRestBalance;
        }

        $quote->setBaseCreditlineAmountUsed($this->baseAmountUsed)
            ->setCreditlineAmountUsed($this->amountUsed);

        $delta = $quoteTotal - $restBalance;
        $baseDelta = $quoteBaseTotal - $baseRestBalance;

        $address->setBaseGrandTotal($baseDelta)
            ->setGrandTotal($delta)
            ->setBaseCreditlineAmount($baseRestBalance)
            ->setCreditlineAmount($restBalance)
            ->save();

        $quote->setUseCredit(Config::USE_CREDIT_YES);

        $quote->setBaseGrandTotal($baseDelta)
            ->setGrandTotal($delta);

        // skip QuoteCollectTotalsBefore observer
        $quote->setCreditCollected(true)
            ->save();
    }

    /**
     * @param $quote
     * @param $addressId
     */
    protected function resetMultishippingTotalsOnRecollection($quote, $addressId)
    {
        if (
            $quote->getIsMultiShipping()
            && !empty($this->processedShippingAddresses[$addressId])
            && $this->amountUsed
        ) {
            $this->amountUsed = 0;
            $this->processedShippingAddresses = [];
        }
    }
}
