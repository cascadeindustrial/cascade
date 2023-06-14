<?php


namespace Cminds\Creditline\Observer\Refill;

use Magento\Framework\Event\Observer;
use Cminds\Creditline\Model\Transaction;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CreditmemoSaveAfter extends AbstractObserver
{
    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $order = $creditmemo->getOrder();

        $amount = $this->getRefillAmount($order);

        if ($amount > 0) {
            $this->getBalance($order)->addTransaction(
                -1 * $amount,
                Transaction::ACTION_REFUNDED,
                ['order' => $order, 'creditmemo' => $creditmemo]
            );
        }
    }
}
