<?php

namespace Cminds\Creditline\Observer;

use Cminds\Creditline\Observer\AbstractObserver;
use Magento\Framework\Event\Observer;
use Cminds\Creditline\Model\Transaction;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class OrderCancelAfter extends AbstractObserver
{
    /**
     * @param Observer $observer
     *
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$observer->getEvent()->getItem()) {
            return;
        }

        $order = $observer->getEvent()->getItem()->getOrder();

        if ($order && $order->getBaseCreditlineAmount() > 0 && $order->getBaseCreditlineRefunded() == 0) {
            $balance = $this->creditHelper->getBalance($order->getCustomerId(), $order->getOrderCurrencyCode());
            $balance->addTransaction(
                $order->getCreditlineAmount(),
                $order->getBaseCreditlineAmount(),
                Transaction::ACTION_REFUNDED,
                ['order' => $order]
            );

            $order->setBaseCreditlineRefunded($order->getBaseCreditlineAmount())
                ->setCreditlineRefunded($order->getCreditlineAmount())
                ->setBaseCreditlineTotalRefunded($order->getBaseCreditlineAmount())
                ->setCreditlineTotalRefunded($order->getCreditlineAmount())
                ->save();
        }
    }
}
