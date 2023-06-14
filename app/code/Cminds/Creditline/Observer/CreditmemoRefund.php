<?php


namespace Cminds\Creditline\Observer;

use Magento\Framework\Event\ObserverInterface;
use Cminds\Creditline\Model\BalanceFactory;
use Cminds\Creditline\Helper\Data;
use Magento\Framework\Event\Observer;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CreditmemoRefund implements ObserverInterface
{
    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var Data
     */
    protected $creditData;

    /**
     * @param BalanceFactory $balanceFactory
     * @param Data          $creditData
     */
    public function __construct(
        BalanceFactory $balanceFactory,
        Data $creditData
    ) {
        $this->balanceFactory = $balanceFactory;
        $this->creditData = $creditData;
    }

    /**
     * @param Observer $observer
     * @throws LocalizedException
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();

        /** @var Order $order */
        $order = $creditmemo->getOrder();

        if ($creditmemo->getBaseCreditlineAmount()) {
            if ($creditmemo->getRefundCredit()) {
                $baseAmount = $creditmemo->getBaseCreditlineAmount();
                $amount = $creditmemo->getCreditlineAmount();

                if (!$creditmemo->getDonotReturnAppliedCredits()) {
                    $creditmemo->setBaseCreditlineTotalRefunded($creditmemo->getBaseCreditlineTotalRefunded() + $baseAmount);
                    $creditmemo->setCreditlineTotalRefunded($creditmemo->getCreditlineTotalRefunded() + $amount);
                }
            }

            if (!$creditmemo->getDonotReturnAppliedCredits()) {
                $order->setBaseCreditlineRefunded(
                    $order->getBaseCreditlineRefunded() + $creditmemo->getBaseCreditlineAmount()
                );

                $order->setCreditlineRefunded(
                    $order->getCreditlineRefunded() + $creditmemo->getCreditlineAmount()
                );
            }

            if ($order->getCreditlineInvoiced() > 0
                && $order->getCreditlineInvoiced() == $order->getCreditlineRefunded()
                && $order->getTotalInvoiced() == $order->getTotalRefunded()
            ) {
                $order->setForcedCanCreditmemo(false);
            }
        }
    }
}
