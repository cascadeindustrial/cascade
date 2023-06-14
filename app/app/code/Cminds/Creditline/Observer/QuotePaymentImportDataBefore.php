<?php

namespace Cminds\Creditline\Observer;

use Cminds\Creditline\Observer\AbstractObserver;
use Magento\Framework\Event\Observer;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\State;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class QuotePaymentImportDataBefore extends AbstractObserver
{
    protected $state;

    public function __construct(State $state)
    {
     $this->state = $state;
    }
    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {

        if ($this->state->getAreaCode() == FrontNameResolver::AREA_CODE) {
            $input = $observer->getEvent()->getInput();
            $payment = $observer->getEvent()->getPayment();
            $this->_importPaymentData($payment->getQuote(), $input, $input->getUseCredit());
        }
    }
}
