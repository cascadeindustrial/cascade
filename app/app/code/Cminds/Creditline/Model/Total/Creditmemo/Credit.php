<?php


namespace Cminds\Creditline\Model\Total\Creditmemo;

use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Magento\Framework\App\Request\Http;
use Magento\Sales\Model\Order\Creditmemo;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Credit extends AbstractTotal
{
    public function __construct(
        Http $request,
        array $data = []
    ) {
        parent::__construct($data);

        $this->request = $request;
    }
    /**
     * @param Creditmemo $creditmemo
     *
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
        $input = $this->request->getParam('creditmemo');
        if (isset($input['donot_return_applied_credits'])) {
            $creditmemo->setDonotReturnAppliedCredits(1);
        }

        $creditmemo->setBaseCreditlineTotalRefunded(0)
            ->setCreditlineTotalRefunded(0)
            ->setBaseCreditReturnMax(0)
            ->setCreditReturnMax(0);

        $order = $creditmemo->getOrder();

        if ($order->getBaseCreditlineAmount() && $order->getBaseCreditlineInvoiced()) {
            $left = $order->getBaseCreditlineInvoiced() - $order->getBaseCreditlineRefunded();

            $baseUsed = 0;
            $used = 0;
            if ($left >= $creditmemo->getBaseGrandTotal()) {
                if (!$creditmemo->getDonotReturnAppliedCredits()) {
                    $baseUsed = $creditmemo->getBaseGrandTotal();
                    $used = $creditmemo->getGrandTotal();

                    $creditmemo->setBaseGrandTotal(0);
                    $creditmemo->setGrandTotal(0);

                    $creditmemo->setAllowZeroGrandTotal(true);
                }
            } else {
                if (!$creditmemo->getDonotReturnAppliedCredits()) {
                    $baseUsed = $order->getBaseCreditlineInvoiced() - $order->getBaseCreditlineRefunded();
                    $used = $order->getCreditlineInvoiced() - $order->getCreditlineRefunded();
                }

                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $baseUsed);
                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $used);
            }

            $creditmemo->setBaseCreditlineAmount($baseUsed);
            $creditmemo->setCreditlineAmount($used);
        }

        $creditmemo->setBaseCreditReturnMax($creditmemo->getBaseGrandTotal());
        $creditmemo->setCreditReturnMax($creditmemo->getGrandTotal());

        return $this;
    }
}
