<?php

namespace Cminds\Creditline\Observer;

use Cminds\Creditline\Observer\AbstractObserver;
use Cminds\Creditline\Model\Transaction;
use Cminds\Creditline\Model\Config;
use Magento\Framework\Event\Observer;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\State;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class OrderSubmitAfter extends AbstractObserver
{
    protected $state;

    public function __construct(
        \Cminds\Creditline\Helper\Data $creditHelper,
        \Magento\Framework\Model\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        State $state
    ) {
        parent::__construct(
            $creditHelper,
            $context,
            $storeManager
        );
        $this->state = $state;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();
        if ($order->getBaseCreditlineAmount() > 0) {
            $balance = $this->creditHelper->getBalance($order->getCustomerId(), $order->getOrderCurrencyCode());
            $balance->setTransactionCurrencyCode($order->getOrderCurrencyCode());

            $balance->addTransaction(
                -1 * $order->getCreditlineAmount(),
                -1 * $order->getBaseCreditlineAmount(),
                Transaction::ACTION_USED,
                ['order' => $order]
            );
        }

        if ($this->state->getAreaCode() == FrontNameResolver::AREA_CODE) {
            $quote = $observer->getEvent()->getQuote();
            $address = $quote->getShippingAddress();
            $address->setBaseGrandTotal(0)
                ->setGrandTotal(0)
                ->save();
            $quote->setUseCredit(Config::USE_CREDIT_YES)
                ->setBaseCreditlineAmountUsed(0)
                ->setCreditlineAmountUsed(0)
                ->setBaseGrandTotal(0)
                ->setGrandTotal(0)
                ->save();
            $order->setBaseGrandTotal(0)
                ->setGrandTotal(0)
                ->save();
        }
    }
}
