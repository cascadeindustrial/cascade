<?php


namespace Cminds\Creditline\Observer;

use Magento\Framework\Event\ObserverInterface;
use Cminds\Creditline\Helper\CreditOption;
use Cminds\Creditline\Helper\Calculation;
use Cminds\Creditline\Model\ProductOptionCreditFactory;
use Cminds\Creditline\Helper\Data;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Cminds\Creditline\Model\Transaction;
use Cminds\Creditline\Model\Product\Type;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CreditmemoSaveAfter implements ObserverInterface
{
    public function __construct(
        CreditOption $optionHelper,
        Calculation $culculationHelper,
        ProductOptionCreditFactory $productOptionCredit,
        Data $creditData
    ) {
        $this->optionHelper        = $optionHelper;
        $this->culculationHelper   = $culculationHelper;
        $this->productOptionCredit = $productOptionCredit;
        $this->creditData          = $creditData;
    }

    /**
     * @param Observer $observer
     * @throws LocalizedException
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Creditmemo $creditmemo */
        $creditmemo = $observer->getEvent()->getData('creditmemo');
        $order      = $creditmemo->getOrder();

        $this->refundCreditProduct($creditmemo);

        if ($creditmemo->getAutomaticallyCreated()) {
            if ($this->creditData->isAutoRefundEnabled()) {
                $creditmemo->setCreditRefundFlag(true)
                    ->setCreditlineTotalRefunded($creditmemo->getCreditlineAmount())
                    ->setBaseCreditlineTotalRefunded($creditmemo->getBaseCreditlineAmount());
            } else {
                return;
            }
        }

        $baseCreditReturnMax = floatval($creditmemo->getBaseCreditReturnMax());

        $refunded = round($creditmemo->getBaseCreditlineTotalRefunded(), 2);
        $used     = round($baseCreditReturnMax + $order->getBaseCreditlineAmount(), 2);

        if ($refunded > $used) {
            throw new LocalizedException(
                __('Store credit amount cannot exceed order amount.')
            );
        }
        if ($creditmemo->getData('creditline_refund_flag')
            && $creditmemo->getData('base_creditline_total_refunded')
            && !$creditmemo->getDonotReturnAppliedCredits()
        ) {

            $order->setBaseCreditlineTotalRefunded(
                $order->getBaseCreditlineTotalRefunded() + $creditmemo->getBaseCreditlineTotalRefunded()
            );
            $order->setCreditlineTotalRefunded(
                $order->getCreditlineTotalRefunded() + $creditmemo->getCreditlineTotalRefunded()
            );

            $balance = $this->creditData->getBalance($order->getCustomerId(), $order->getOrderCurrencyCode());
            $balance->setTransactionCurrencyCode($order->getOrderCurrencyCode());
            $balance->addTransaction(
                $creditmemo->getData('creditline_total_refunded'),
                $creditmemo->getData('base_creditline_total_refunded'),
                Transaction::ACTION_REFUNDED,
                ['order' => $order, 'creditmemo' => $creditmemo]
            );
        }

        if ($order->getBaseCreditlineTotalRefunded() > 0) {
            $order->setTotalRefunded(
                $order->getTotalRefunded() - ($order->getCreditlineTotalRefunded() - $order->getCreditlineRefunded())
            );
            $order->setBaseTotalRefunded(
                $order->getBaseTotalRefunded() - (
                    $order->getBaseCreditlineTotalRefunded() - $order->getBaseCreditlineRefunded()
                )
            );
        }
    }

    /**
     * @param Creditmemo $creditmemo
     * @return void
     */
    public function refundCreditProduct($creditmemo)
    {
        $order = $creditmemo->getOrder();

        $credits = 0;
        /** @var Creditmemo\Item $item */
        foreach ($creditmemo->getAllItems() as $item) {
            /** @var Item $orderItem */
            $orderItem = $order->getItemById($item->getOrderItemId());
            if ($orderItem->getProductType() == Type::TYPE_CREDITPOINTS
                || $orderItem->getRealProductType() == Type::TYPE_CREDITPOINTS
            ) {
                $options = $orderItem->getProductOptionByCode('info_buyRequest');
                $option  = $this->productOptionCredit->create();
                $value   = !empty($options['creditOption']) ? $options['creditOption'] : 0;
                $data    = !empty($options['creditOptionData']) ? $options['creditOptionData'] : [];
                $option->setData($data);
                $productCredits = $this->optionHelper->getOptionCredits($option, $value);
                $credits        += $productCredits * $item->getQty();
            }
        }
        if ($credits) {
            $balance = $this->creditData->getBalance($order->getCustomerId(), $order->getOrderCurrencyCode());
            $balance->setTransactionCurrencyCode($order->getOrderCurrencyCode());

            $baseCredits = $this->culculationHelper->convertToCurrency(
                $credits, $order->getOrderCurrencyCode(), $balance->getCurrencyCode(), $order->getStore()
            );
            $balance->addTransaction(
                -1 * $credits,
                -1 * $baseCredits,
                Transaction::ACTION_REFUNDED,
                ['order' => $order, 'creditmemo' => $creditmemo]
            );
        }
    }
}
