<?php


namespace Cminds\Creditline\Observer\Refill;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Cminds\Creditline\Model\Config;
use Cminds\Creditline\Model\BalanceFactory;
use Magento\Sales\Model\Order;

abstract class AbstractObserver implements ObserverInterface
{
    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param BalanceFactory $balanceFactory
     * @param Config         $config
     */
    public function __construct(
        BalanceFactory $balanceFactory,
        Config $config
    ) {
        $this->balanceFactory = $balanceFactory;
        $this->config = $config;
    }

    /**
     * @param Order $order
     * @return float
     */
    protected function getRefillAmount($order)
    {
        $amount = 0;

        foreach ($order->getItems() as $item) {
            if ($this->config->getRefillProduct() &&
                $item->getProductId() == $this->config->getRefillProduct()->getId()
            ) {
                $amount += $item->getBaseRowTotal();
            }
        }

        return $amount;
    }

    /**
     * @param Order $order
     * @return Balance
     */
    protected function getBalance($order)
    {
        return $this->balanceFactory->create()
            ->loadByCustomer($order->getCustomerId(), $order->getOrderCurrencyCode());
    }
}