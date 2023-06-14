<?php


namespace Cminds\Creditline\Observer;

use Magento\Framework\Event\Observer;
use Cminds\Creditline\Model\Transaction;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class OrderSubmitBefore extends AbstractObserver
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();
        $address = $observer->getEvent()->getAddress();
        if (!$address) {
            $quote = $observer->getEvent()->getQuote();
            if ($quote->getIsVirtual()) {
                $address = $quote->getBillingAddress();
            } else {
                $address = $quote->getShippingAddress();
            }
        }

        if ($address) {
            $order->setBaseCreditlineAmount($address->getBaseCreditlineAmount())
                ->setCreditlineAmount($address->getCreditlineAmount());
        }
    }
}
