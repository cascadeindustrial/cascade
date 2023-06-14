<?php

namespace Cminds\Creditline\Observer;

use Cminds\Creditline\Model\Product\Type;
use Magento\Sales\Model\Order;
use Cminds\Creditline\Api\Data\ProductOptionCreditInterface;
use Cminds\Creditline\Observer\AbstractObserver;
use Cminds\Creditline\Helper\CreditOption;
use Cminds\Creditline\Helper\Calculation;
use Cminds\Creditline\Helper\Data;
use Cminds\Creditline\Model\ProductOptionCreditFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Model\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Event\Observer;
use Cminds\Creditline\Model\Transaction;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class OrderSaveCommitAfterObserver extends AbstractObserver
{

    public function __construct(
        CreditOption $optionHelper,
        Calculation $culculationHelper,
        Data $creditHelper,
        ProductOptionCreditFactory $productOptionCreditFactory,
        ProductRepository $productRepository,
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($creditHelper, $context, $storeManager);

        $this->optionHelper               = $optionHelper;
        $this->culculationHelper          = $culculationHelper;
        $this->productRepository          = $productRepository;
        $this->productOptionCreditFactory = $productOptionCreditFactory;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        /* @var $order Order */
        $order = $observer->getEvent()->getOrder();

        if (!$order->getId()) {
            //order not saved in the database
            return;
        }
        if (
            $order->getCustomerId() &&
            ($order->getState() == Order::STATE_CLOSED || $order->getState() == Order::STATE_COMPLETE)
        ) {
            $this->processCreditProduct($order);
        }
    }

    /**
     * @param Order $order
     * @return void
     */
    public function processCreditProduct($order)
    {
        $credits = 0;
        /** @var Order\Item $item */
        foreach ($order->getAllItems() as $item) {
            if ($item->getProductType() == Type::TYPE_CREDITPOINTS ||
                $item->getRealProductType() == Type::TYPE_CREDITPOINTS
            ) {
                $productOptions = $item->getProductOptions();
                if (!isset($productOptions['info_buyRequest'])) {
                    continue;
                }
                $requestOptions = $productOptions['info_buyRequest'];
                $value  = 0;
                $option = $this->productOptionCreditFactory->create();
                if (!isset($requestOptions['creditOption'])) {
                    $option->getResource()->load(
                        $option, $requestOptions['product'], ProductOptionCreditInterface::KEY_OPTION_PRODUCT_ID
                    );
                } else {
                    $optionId = $requestOptions['creditOption'];
                    if (isset($requestOptions['creditOptionId'])) {
                        $value    = $requestOptions['creditOption'];
                        $optionId = $requestOptions['creditOptionId'];
                    }

                    $option->getResource()->load($option, $optionId);
                }

                $credits += $this->optionHelper->getOptionCredits($option, $value) * $item->getQtyInvoiced();
            }
        }
        if ($credits) {
            $balance = $this->creditHelper->getBalance($order->getCustomerId(), $order->getOrderCurrencyCode());
            $balance->setTransactionCurrencyCode($order->getOrderCurrencyCode());

            $baseCredits = $this->culculationHelper->convertToCurrency(
                $credits, $order->getOrderCurrencyCode(), $balance->getCurrencyCode(), $order->getStore()
            );
            $balance->addTransaction(
                $credits,
                $baseCredits,
                Transaction::ACTION_PURCHASED,
                ['order' => $order]
             );
        }
    }
}
