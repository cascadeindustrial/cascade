<?php
namespace MageMaclean\MyShipping\Plugin\Sales;

use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Carrier;

class OrderRepository
{
    protected $_helper;
    protected $orderExtensionFactory;

    public function __construct(
        Helper $helper,
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->_helper = $helper;
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {
        return $this->processOrder($order);
    }

    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $result
    ) {
        foreach ($result->getItems() as $item) {
            $this->processOrder($item);
        }

        return $result;
    }

    /**
     * @param OrderInterface $order
     * @return OrderInterface
     */
    private function processOrder(OrderInterface $order): OrderInterface
    {
        $orderExtension = $order->getExtensionAttributes();
        if(!$orderExtension) {
            $orderExtension = $this->orderExtensionFactory->create();
        }

        if($this->_helper->isMyshippingMethod($order->getShippingMethod())) {
            if ($order->getShippingMethod() == Carrier::CODE_NEW) {
                $orderExtension->setMyshippingCourierId((int)$order->getData('myshipping_courier_id'));
                $orderExtension->setMyshippingAccount((string)$order->getData('myshipping_account'));
            } else {
                $orderExtension->setMyshippingAccountId((int)$order->getData('myshipping_account_id'));
            }
            $orderExtension->setMyshippingCourierMethod((string)$order->getData('myshipping_courier_method'));
        }
        $order->setExtensionAttributes($orderExtension);

        return $order;
    }

    public function beforeSave(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {
        $orderExtension = $order->getExtensionAttributes();

        if($this->_helper->isMyshippingMethod($order->getShippingMethod()) && $orderExtension) {
            if ($order->getShippingMethod() == Carrier::CODE_NEW) {
                $order->setData('myshipping_courier_id', $orderExtension->getMyshippingCourierId());
                $order->setData('myshipping_account', $orderExtension->getMyshippingAccount());
            } else {
                $order->setData('myshipping_account_id', $orderExtension->getMyshippingAccountId());
            }
            $order->setData('myshipping_courier_method', $orderExtension->getMyshippingCourierMethod());
        }
    }
}
