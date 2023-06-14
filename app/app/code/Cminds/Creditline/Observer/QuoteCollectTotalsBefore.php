<?php


namespace Cminds\Creditline\Observer;

use Magento\Framework\Event\Observer;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class QuoteCollectTotalsBefore extends AbstractObserver
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Quote $quote */
        $quote = $observer->getEvent()->getQuote();

        // skip quote collect totals if CreditCollected == true
        if ($quote->getCreditCollected()) {
            return $this;
        }

        if ($quote->getId()) {
            $quote->setBaseCreditlineAmountUsed(0)
                ->setCreditlineAmountUsed(0)
                ->save();
            $quote->getShippingAddress()->setBaseCreditlineAmount(0)
                ->setCreditlineAmount(0)
                ->save();
        }
    }
}
