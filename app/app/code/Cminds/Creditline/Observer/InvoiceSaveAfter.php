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
class InvoiceSaveAfter extends AbstractObserver
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();

        // is new invoice
        if ($invoice->getOrigData() === null && $invoice->getBaseCreditlineAmount()) {
            $order->setBaseCreditlineInvoiced($order->getBaseCreditlineInvoiced() + $invoice->getBaseCreditlineAmount())
                ->setCreditlineInvoiced($order->getCreditlineInvoiced() + $invoice->getCreditlineAmount());
        }

        $order->getResource()->saveAttribute($order, 'base_creditline_invoiced');
        $order->getResource()->saveAttribute($order, 'creditline_invoiced');
    }
}
