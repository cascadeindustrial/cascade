<?php

namespace Dcw\Override\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;


class ConvertQuoteToOrder extends \Amasty\Orderattr\Observer\ConvertQuoteToOrder
{
    /**
     * @var \Magento\Sales\Api\Data\OrderExtensionFactory
     */
    private $orderExtensionFactory;

    protected $_session;

    public function __construct(\Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory,
    \Magento\Customer\Model\Session $session,
    \Magento\Customer\Model\Customer $customer,
    \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory,
    CustomerRepositoryInterface $customerRepository
    )
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->customer = $customer;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        //$this->customer = $customer;
        $this->_session = $session;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
      $logFile='/var/log/ConvertQuoteToOrder.log';
    $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    $logger->info("inside override file");
        /**
         * @var \Magento\Quote\Model\Quote $quote
         * @var \Magento\Sales\Model\Order $order
         */
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();

        //printLog(json_encode((array)$order));
        //printLog("order data");
        //printLog($order->getIncrementId());

        $orderIncrementId = "INC".$order->getIncrementId();

        $quoteAttributes = $quote->getExtensionAttributes();
        if ($quoteAttributes && $quoteAttributes->getAmastyOrderAttributes()) {
            $customAttributes = $quoteAttributes->getAmastyOrderAttributes();
            $orderAttributes = $order->getExtensionAttributes();
            if (empty($orderAttributes)) {
                $orderAttributes = $this->orderExtensionFactory->create();
            }
            $orderAttributes->setAmastyOrderAttributes($customAttributes);
            $order->setExtensionAttributes($orderAttributes);
            $quoteAttributes->setAmastyOrderAttributes([]);
            $quote->setExtensionAttributes($quoteAttributes);
            //printLog("custom attrs");
            //printLog($customAttributes);
            foreach($customAttributes as $customAttr)
            {
              //printLog($customAttr);
              $attrCode[] = $customAttr->getAttributeCode();
              $attrValue[] = $customAttr->getValue();
              // printLog($attrCode);
              // printLog($attrValue);
            }
            // echo "<pre>";
            // echo "teststtttsteste";
            // printLog($attrCode);
            // printLog($attrValue);
            //exit;
            $canSaveFlag =1;
            $totalAccountNos = '';
            if(is_array($attrCode) && is_array($attrValue))
            {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
                $resource=$objectManager->create('\Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
                $storedFedexAccountNo = $storedUpsAccountNo = 0;
                $accountInfo = array_combine($attrCode,$attrValue);
                $customerId = $this->_session->getCustomer()->getId();
                $customer = $this->customer->load($customerId);
                if(array_key_exists("fedex_save_for_future_usepm",$accountInfo) || array_key_exists("ups_save_for_future_usepm",$accountInfo))
                {
                  if(array_key_exists("fedex_accountnopm",$accountInfo))
                  {
                      $customAttr = 'fedexaccountno';
                      $fedexAccountNo = $accountInfo['fedex_accountnopm'];
                      $savedFedexAccountNos = $customer->getFedexaccountno();
                      if($savedFedexAccountNos)
                      {
                            $newfedexAccountNos = $savedFedexAccountNos.','.$fedexAccountNo;
                            $totalAccountNos = $newfedexAccountNos;
                            $totalFedexAccountNos =  explode(',',$newfedexAccountNos);
                            if(count($totalFedexAccountNos)>5)
                                $canSaveFlag = 0;
                      }
                      else{
                            $totalAccountNos = $fedexAccountNo;
                      }
                      //printLog("in fedex accountno");
                  }
                  else if(array_key_exists("ups_shipping_accountpm",$accountInfo)){
                         $savedUpsAccountNo = $customer->getUpsaccountno();
                         $customAttr = 'upsaccountno';
                         $upsAccountNo = $accountInfo['ups_shipping_accountpm'];
                         if($savedUpsAccountNo)
                         {
                               $newUpsAccountNos = $savedUpsAccountNo.','.$upsAccountNo;
                               $totalAccountNos = $newUpsAccountNos;
                               $newUpsAccountNos = explode(',',$newUpsAccountNos);
                               if(count($newUpsAccountNos)>5)
                                  $canSaveFlag = 0;
                         }
                         else{
                           $totalAccountNos = $upsAccountNo;
                         }
                         //printLog("in ups shipping acount");
                  }
                }
                // printLog($totalAccountNos);
                // printLog($customAttr);
                // exit;
                //printLog($accountInfo);
                if($totalAccountNos && $canSaveFlag)
                {
                  $customerData = $customer->getDataModel();
                  $customerData->setCustomAttribute($customAttr,$totalAccountNos);
                  $customer->updateData($customerData);
                  $customerResource = $this->customerFactory->create();
                  $customerResource->saveAttribute($customer, $customAttr);
                }
                if(array_key_exists("stored_account_values_upspm",$accountInfo))
                {
                  $storedUpsAccountNo = $accountInfo['stored_account_values_upspm'];
                  //printLog($accountInfo['stored_account_values_ups']);
                  $insertUpsQuery="INSERT INTO `order_storedaccount_values` (`increment_id`, `ups_account_no`, `fedex_account_no`) VALUES ('$orderIncrementId', $storedUpsAccountNo, $storedFedexAccountNo)";

                  $connection->query($insertUpsQuery);
                }
                else if(array_key_exists("stored_account_values_pm",$accountInfo))
                {
                  $storedFedexAccountNo = $accountInfo['stored_account_values_pm'];

                  $insertFedexQuery="INSERT INTO `order_storedaccount_values` (`increment_id`, `ups_account_no`, `fedex_account_no`) VALUES ('$orderIncrementId', $storedUpsAccountNo, $storedFedexAccountNo)";

                  $connection->query($insertFedexQuery);

                }
          }

            //$logger->info("custom attrs");
            //$logger->info($customAttributes);
        }
    }
}
