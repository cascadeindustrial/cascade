<?php


namespace Cminds\Creditline\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface as CurrencyHelper;
use Cminds\Creditline\Helper\Calculation;
use Cminds\Creditline\Helper\Data;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CreditmemoRegisterBefore implements ObserverInterface
{
    /**
     * @var CurrencyHelper
     */
    protected $currencyHelper;

    /**
     * @var Data
     */
    protected $creditData;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @param CurrencyHelper  $currencyHelper
     * @param Calculation     $calculationHelper
     * @param Data            $creditData
     * @param Http            $request
     */
    public function __construct(
        CurrencyHelper $currencyHelper,
        Calculation $calculationHelper,
        Data $creditData,
        Http $request
    ) {
        $this->currencyHelper = $currencyHelper;
        $this->calculationHelper = $calculationHelper;
        $this->creditData = $creditData;
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     *
     * @throws LocalizedException
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();

        $input = $this->request->getParam('creditmemo');

        $amount = 0;
        $baseAmount = 0;
        if (isset($input['refund_to_creditline_amount'])) {
            $baseAmount = floatval($input['refund_to_creditline_amount']);
        }
        if (isset($input['donot_return_applied_credits'])) {
            $creditmemo->setDonotReturnAppliedCredits(1);
        }

        if ($creditmemo->getBaseCreditlineAmount() + $creditmemo->getBaseCreditReturnMax() > 0) {
            if ($creditmemo->getBaseCreditReturnMax() <= 0) {
                $baseAmount = $creditmemo->getBaseCreditlineAmount();
            } else {
                $baseAmount = min($creditmemo->getBaseCreditReturnMax(), $baseAmount);
                $baseAmount = $creditmemo->getBaseCreditlineAmount() + $baseAmount;
            }
        }

        if ($baseAmount > 0) {
            $baseAmount = $creditmemo->roundPrice($baseAmount);
            $amount = $this->calculationHelper->convertToCurrency(
                $baseAmount,
                $creditmemo->getBaseCurrencyCode(),
                $creditmemo->getOrderCurrencyCode(),
                $creditmemo->getStore()
            );
            $amount = round($amount, 2, PHP_ROUND_HALF_DOWN);


            if (!$creditmemo->getDonotReturnAppliedCredits() && !$creditmemo->getId()) {
                $creditmemo->setCreditlineTotalRefunded($amount);
                $creditmemo->setBaseCreditlineTotalRefunded($baseAmount);
            }

            $creditmemo->setCreditRefundFlag(true);
            $creditmemo->setPaymentRefundDisallowed(false);
        }
    }
}
