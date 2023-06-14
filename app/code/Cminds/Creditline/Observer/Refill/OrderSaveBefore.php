<?php
namespace Cminds\Creditline\Observer\Refill;

use Cminds\Creditline\Observer\Refill\AbstractObserver;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use Cminds\Creditline\Model\Transaction;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class OrderSaveBefore extends AbstractObserver
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();

        if (!$this->config->getRefillProduct()) {
            return;
        }

        if ($order->getState() != $order->getOrigData('state')) {
            if ($order->getState() == Order::STATE_COMPLETE) {
                $amount = $this->getRefillAmount($order);

                if ($amount > 0) {
                    $this->getBalance($order)->addTransaction(
                        $amount,
                        $amount,
                        Transaction::ACTION_REFILL,
                        ['order' => $order]
                    );
                }
            }
        }
    }
}
