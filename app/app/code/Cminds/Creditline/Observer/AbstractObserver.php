<?php

namespace Cminds\Creditline\Observer;

use Magento\Framework\Event\ObserverInterface;
use Cminds\Creditline\Model\Config;
use Cminds\Creditline\Helper\Data;
use Magento\Framework\Model\Context;
use Magento\Store\Model\StoreManagerInterface;

abstract class AbstractObserver implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $creditHelper;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Data               $creditHelper
     * @param Context           $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Data $creditHelper,
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->creditHelper = $creditHelper;
        $this->context = $context;
        $this->storeManager = $storeManager;
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
                $quote->setBalanceInstance($balance);
                if (!$payment->getMethod()) {
                    $payment->setMethod(Config::FREE_METHOD);
                }
            } else {
                $quote->setUseCredit(Config::USE_CREDIT_NO);
            }
        }
    }
}
