<?php

namespace Cminds\Creditline\Plugin\Order\Handler;

use Magento\Sales\Model\ResourceModel\Order\Handler\State as StateHandler;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class State
{

    /**
     * @param StateHandler     $subject
     * @param callable  $proceed
     * @param Order     $order
     * @return StateHandler
     */
    public function aroundCheck(
        StateHandler $subject, $proceed, $order
    ) {
        $isModified = false;
        if (!$order->isCanceled() && !$order->canUnhold() && !$order->canInvoice() && !$order->canShip()) {
            if (0 == $order->getBaseGrandTotal() && $order->getSubtotalInvoiced() <= $order->getCreditlineInvoiced()) {
                $isModified = true;
                $order->setBaseGrandTotal(0.0001);
            }
        }
        $result = $proceed($order);
        if ($isModified) {
            $order->setBaseGrandTotal(0);
        }

        return $result;
    }
}