<?php

namespace Cminds\Creditline\Observer;

use Cminds\Creditline\Observer\AbstractObserver;
use Magento\Framework\Event\Observer;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class OrderLoadAfter extends AbstractObserver
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->canUnhold() || $order->isCanceled()) {
            return;
        }

        if ($order->getCreditlineInvoiced() - $order->getCreditlineRefunded() > 0) {
            $order->setForcedCanCreditmemo(true);
        }
    }
}
