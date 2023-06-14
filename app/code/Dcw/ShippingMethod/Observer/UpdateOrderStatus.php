<?php

	namespace Dcw\ShippingMethod\Observer;

	use Magento\Framework\Event\ObserverInterface;
	use Magento\Framework\App\RequestInterface;
	use Dcw\CustomPricing\Model\CustomPricingFactory;


	class UpdateOrderStatus implements ObserverInterface
	{

		public function execute(\Magento\Framework\Event\Observer $observer) {

					 $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
           $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/shipment.log');
           $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);
           $logger->info("in UpdateOrderStatus method");

           $shipment = $observer->getEvent()->getShipment();

           $orderId = $shipment->getOrderId();

					 // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
					 // $order = $objectManager->create('\Magento\Sales\Model\Order')->load($orderId);

					 $logger->info($shipment->getId());
					 $shipmentId = $shipment->getId();

					 //SELECT *  FROM `sales_invoice` WHERE `order_id` = 291;

					 $resource=$objectManager->create('\Magento\Framework\App\ResourceConnection');
					 $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

					 $sql = "SELECT * FROM `sales_invoice` where order_id = ".$orderId;
					 $invoiceItems = $connection->fetchAll($sql);

					 $logger->info($invoiceItems);

					 if(count($invoiceItems)>0)
					 {
						 $logger->info("in if loop");

						 $sqlOrders = "UPDATE sales_order SET status='complete',state='complete' WHERE entity_id=$orderId";
						 //echo $sqlOrders;
						 $connection->query($sqlOrders);
					 }

           // $logger->info("OrderId:");
           // $logger->info($orderId);
					 return;

		}

	}
?>
