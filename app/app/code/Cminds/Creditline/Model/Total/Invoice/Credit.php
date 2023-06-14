<?php


namespace Cminds\Creditline\Model\Total\Invoice;

use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Magento\Sales\Model\Order\Invoice;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Credit extends AbstractTotal
{
    /**
     * @param Invoice $invoice
     * @return $this
     */
    public function collect(Invoice $invoice)
    {
        $order = $invoice->getOrder();

        #credit amount AND credit amount not set for invoice
        if ($order->getBaseCreditlineAmount()
            && floatval($order->getBaseCreditlineInvoiced()) == 0
        ) {
            $baseUsed = $order->getBaseCreditlineAmount();
            $used = $order->getCreditlineAmount();

            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseUsed)
                ->setGrandTotal($invoice->getGrandTotal() - $used)
                ->setBaseCreditlineAmount($baseUsed)
                ->setCreditlineAmount($used);
        }

        return $this;
    }
}
