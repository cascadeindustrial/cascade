<?php

namespace Dcw\ShippingMethod\Controller\Adminhtml\Order\Invoice;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Email\Sender\ShipmentSender;
use Magento\Sales\Model\Order\ShipmentFactory;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Helper\Data as SalesData;

//require 'vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

/**
 * Save invoice controller.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends \Magento\Sales\Controller\Adminhtml\Order\Invoice\Save
{
    const ADMIN_RESOURCE = 'Magento_Sales::sales_invoice';

    /**
     * @var InvoiceSender
     */
    protected $invoiceSender;

    /**
     * @var ShipmentSender
     */
    protected $shipmentSender;

    /**
     * @var ShipmentFactory
     */
    protected $shipmentFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var InvoiceService
     */
    private $invoiceService;

    /**
     * @var SalesData
     */
    private $salesData;

    public function __construct(
        Action\Context $context,
        Registry $registry,
        InvoiceSender $invoiceSender,
        ShipmentSender $shipmentSender,
        ShipmentFactory $shipmentFactory,
        InvoiceService $invoiceService,
        SalesData $salesData = null
    ) {
        $this->registry = $registry;
        $this->invoiceSender = $invoiceSender;
        $this->shipmentSender = $shipmentSender;
        $this->shipmentFactory = $shipmentFactory;
        $this->invoiceService = $invoiceService;
        parent::__construct($context, $registry, $invoiceSender, $shipmentSender, $shipmentFactory, $invoiceService);
        $this->salesData = $salesData ?? $this->_objectManager->get(SalesData::class);
    }

    /**
     * Save invoice
     *
     * We can save only new invoice. Existing invoices are not editable
     *
     * @return \Magento\Framework\Controller\ResultInterface
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function execute()
    {
      $logFile='/var/log/capture.log';
      $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
      $logger = new \Zend\Log\Logger();
      $logger->addWriter($writer);
      $logger->info("overridednew");

      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $resource=$objectManager->create('\Magento\Framework\App\ResourceConnection');
      $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

      $orderId = $this->getRequest()->getParam('order_id');
      $order = $this->_objectManager->create(\Magento\Sales\Model\Order::class)->load($orderId);
      echo $orderPaymentId = $order->getPayment()->getId(); echo '<br>';
      $grandTotal = $order->getGrandTotal();


      $manualCapture = 0;
      $tranSql = "SELECT transaction_id FROM `sales_payment_transaction` WHERE txn_type='authorization' and order_id=$orderId";
      $transDetails = $connection->fetchRow($tranSql);
      if(is_array($transDetails))
      {
        $transId = $transDetails['transaction_id'];

        $transactionRepository = $objectManager->create('\Magento\Sales\Api\TransactionRepositoryInterface');
        $transactionInfo = $transactionRepository->get($transId);

        $transactionData = $transactionInfo->getData();

        //print_r($transactionData->getData()); exit;
        // foreach($transactionData as $transactionDat)
        // {
        //   print_r($transactionDat->getData()); exit;
        // }

        //echo '<pre>'; print_r($transAdditnlInfo = $transactionData['additional_information']);

        $transAdditnlInfo = $transactionData['additional_information'];
        if($transAdditnlInfo['raw_details_info']['transaction_type']=='auth_only')
        {
          $authAmount = $transAdditnlInfo['raw_details_info']['amount'];
        }
        //$sql = "select gateway_token from vault_payment_token vpt JOIN vault_payment_token_order_payment_link vopl on vpt.entity_id=vopl.payment_token_id where vopl.order_payment_id=$orderPaymentId";
        $paymentTokenItems = array('gateway_token'=>$transAdditnlInfo['raw_details_info']['profile_id'].':'.$transAdditnlInfo['raw_details_info']['payment_id']);
        //echo $grandTotal; echo '<br>'; echo $authAmount; echo '<br>';
        //echo '<pre>'; print_r($paymentTokenItems); exit;
        if(is_array($paymentTokenItems))
        {
          if(!count($paymentTokenItems) || $grandTotal<=$authAmount)
            $manualCapture = 0;
          else
            $manualCapture = 1;

          if(!count($paymentTokenItems) && $grandTotal>$authAmount)
          {
            $this->messageManager->addErrorMessage(__('No existing credit card profile was found'));
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
          }
        }
      }

      //$logger->info($paymentTokenItems);
      $logger->info("manual capture");
      $logger->info($manualCapture);

      if($manualCapture)
      {
              $resultRedirect = $this->resultRedirectFactory->create();
              // void a payment [starts here]
                  $tranSql = "SELECT transaction_id FROM `sales_payment_transaction` WHERE txn_type='void' and order_id=$orderId";
                  $transDetails = $connection->fetchRow($tranSql);
                  if(!is_array($transDetails))
                  {
                    $order->getPayment()->void(new \Magento\Framework\DataObject());
                    $order->save();
                  }
              // void a payment [ends here]

              //capture the card [starts here]
                $response = $this->chargeCreditCard($orderId, $transAdditnlInfo['raw_details_info']['profile_id'],$transAdditnlInfo['raw_details_info']['payment_id']);
              //capture the card [ends here]

              //create an invoice [starts here]
                  if($order->canInvoice()) {
                          $invoice = $this->invoiceService->prepareInvoice($order);
                          $invoice->register();
                          $invoice->save();
                          // $transactionSave = $this->_transaction->addObject(
                          //     $invoice
                          // )->addObject(
                          //     $invoice->getOrder()
                          // );
                          $transactionSave = $this->_objectManager->create(
                              \Magento\Framework\DB\Transaction::class
                          )->addObject(
                              $invoice
                          )->addObject(
                              $invoice->getOrder()
                          );
                          $transactionSave->save();
                          // $this->invoiceSender->send($invoice);
                          //send notification code
                          $order->addStatusHistoryComment(
                              __('Notified customer about invoice #%1.', $invoice->getId())
                          )
                          ->setIsCustomerNotified(true)
                          ->save();
                  }
              //create an invoice [ends here]

              // send invoice/shipment emails
              try {
                  if (!empty($data['send_email']) || $this->salesData->canSendNewInvoiceEmail()) {
                      $this->invoiceSender->send($invoice);
                  }
              } catch (\Exception $e) {
                  $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                  $this->messageManager->addErrorMessage(__('We can\'t send the invoice email right now.'));
              }

              $invoiceId = $invoice->getId();

              $sqlInvoice = "UPDATE sales_invoice_grid SET state=2 WHERE entity_id=$invoiceId";
              //echo $sqlOrders;
              $connection->query($sqlInvoice);

              // if ($shipment) {
              //     try {
              //         if (!empty($data['send_email']) || $this->salesData->canSendNewShipmentEmail()) {
              //             $this->shipmentSender->send($shipment);
              //         }
              //     } catch (\Exception $e) {
              //         $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
              //         $this->messageManager->addErrorMessage(__('We can\'t send the shipment right now.'));
              //     }
              // }
              // if (!empty($data['do_shipment'])) {
              //     $this->messageManager->addSuccessMessage(__('You created the invoice and shipment.'));
              // } else {
              //     $this->messageManager->addSuccessMessage(__('The invoice has been created.'));
              // }
              $this->messageManager->addSuccessMessage(__('The invoice has been created.'));
              $this->_objectManager->get(\Magento\Backend\Model\Session::class)->getCommentText(true);
              return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
      }
      else{

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $formKeyIsValid = $this->_formKeyValidator->validate($this->getRequest());
        $isPost = $this->getRequest()->isPost();
        if (!$formKeyIsValid || !$isPost) {
            $this->messageManager
                ->addErrorMessage(__("The invoice can't be saved at this time. Please try again later."));
            return $resultRedirect->setPath('sales/order/index');
        }

        $data = $this->getRequest()->getPost('invoice');
        $orderId = $this->getRequest()->getParam('order_id');

        if (!empty($data['comment_text'])) {
            $this->_objectManager->get(\Magento\Backend\Model\Session::class)->setCommentText($data['comment_text']);
        }

        try {
            $invoiceData = $this->getRequest()->getParam('invoice', []);
            $invoiceItems = isset($invoiceData['items']) ? $invoiceData['items'] : [];
            /** @var \Magento\Sales\Model\Order $order */
            //$order = $this->_objectManager->create(\Magento\Sales\Model\Order::class)->load($orderId);
            if (!$order->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(__('The order no longer exists.'));
            }

            if (!$order->canInvoice()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('The order does not allow an invoice to be created.')
                );
            }

            $invoice = $this->invoiceService->prepareInvoice($order, $invoiceItems);

            if (!$invoice) {
                throw new LocalizedException(__("The invoice can't be saved at this time. Please try again later."));
            }

            if (!$invoice->getTotalQty()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __("The invoice can't be created without products. Add products and try again.")
                );
            }
            $this->registry->register('current_invoice', $invoice);
            if (!empty($data['capture_case'])) {
                $invoice->setRequestedCaptureCase($data['capture_case']);
            }

            if (!empty($data['comment_text'])) {
                $invoice->addComment(
                    $data['comment_text'],
                    isset($data['comment_customer_notify']),
                    isset($data['is_visible_on_front'])
                );

                $invoice->setCustomerNote($data['comment_text']);
                $invoice->setCustomerNoteNotify(isset($data['comment_customer_notify']));
            }

            $invoice->register();

            $invoice->getOrder()->setCustomerNoteNotify(!empty($data['send_email']));
            $invoice->getOrder()->setIsInProcess(true);

            $transactionSave = $this->_objectManager->create(
                \Magento\Framework\DB\Transaction::class
            )->addObject(
                $invoice
            )->addObject(
                $invoice->getOrder()
            );
            $shipment = false;
            if (!empty($data['do_shipment']) || (int)$invoice->getOrder()->getForcedShipmentWithInvoice()) {
                $shipment = $this->_prepareShipment($invoice);
                if ($shipment) {
                    $transactionSave->addObject($shipment);
                }
            }
            $transactionSave->save();

            // send invoice/shipment emails
            try {
                if (!empty($data['send_email']) || $this->salesData->canSendNewInvoiceEmail()) {
                    $this->invoiceSender->send($invoice);
                }
            } catch (\Exception $e) {
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                $this->messageManager->addErrorMessage(__('We can\'t send the invoice email right now.'));
            }
            if ($shipment) {
                try {
                    if (!empty($data['send_email']) || $this->salesData->canSendNewShipmentEmail()) {
                        $this->shipmentSender->send($shipment);
                    }
                } catch (\Exception $e) {
                    $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
                    $this->messageManager->addErrorMessage(__('We can\'t send the shipment right now.'));
                }
            }
            if (!empty($data['do_shipment'])) {
                $this->messageManager->addSuccessMessage(__('You created the invoice and shipment.'));
            } else {
                $this->messageManager->addSuccessMessage(__('The invoice has been created.'));
            }
            $this->_objectManager->get(\Magento\Backend\Model\Session::class)->getCommentText(true);
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __("The invoice can't be saved at this time. Please try again later.")
            );
            $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
        }
        return $resultRedirect->setPath('sales/*/new', ['order_id' => $orderId]);
    }
  }

    public function chargeCreditCard($orderId,$customerProfileId,$paymentProfileId)
    {
      // echo "in chargeCreditCard method";
      // exit;
      /* Create a merchantAuthenticationType object with authentication details
         retrieved from the constants file */

         $logFile='/var/log/capture.log';
         $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
         $logger = new \Zend\Log\Logger();
         $logger->addWriter($writer);
         $logger->info("overridednew");

         $authCode = $transId = '';

      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $order = $objectManager->create('\Magento\Sales\Model\Order')
                                ->load($orderId);

      $orderPaymentId = $order->getPayment()->getId();

      $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
      $merchantAuthentication->setName("2528zPBNdDS");
      $merchantAuthentication->setTransactionKey("965Y8nh444nYE4j4");

      $resource=$objectManager->create('\Magento\Framework\App\ResourceConnection');
      $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

      $tranSql = "SELECT transaction_id FROM `sales_payment_transaction` WHERE txn_type='authorization' and order_id=$orderId";
      $transDetails = $connection->fetchRow($tranSql);

      $transId = $transDetails['transaction_id'];

      //echo $transId."<br>";

      $sql = "select gateway_token from vault_payment_token vpt JOIN vault_payment_token_order_payment_link vopl on vpt.entity_id=vopl.payment_token_id where vopl.order_payment_id=$orderPaymentId";
      $paymentTokenItems = $connection->fetchRow($sql);

      $transactionRepository = $objectManager->create('\Magento\Sales\Api\TransactionRepositoryInterface');
      $transactionInfo = $transactionRepository->get($transId);

      $transactionData = $transactionInfo->getData();

      if(isset($transactionData['additional_information']))
      {
        $transAdditnlInfo = $transactionData['additional_information'];
        if($transAdditnlInfo['raw_details_info']['transaction_type']=='auth_only')
        {
          $authAmount = $transAdditnlInfo['raw_details_info']['amount'];
        }
      }

      //$gatewayTokenList = explode(":",$paymentTokenItems['gateway_token']);

      //$customerProfileId = $gatewayTokenList[0];
      //$paymentProfileId = $gatewayTokenList[1];

      $grandTotal = $order->getGrandTotal();

      // Set the transaction's refId
      $refId = 'ref' . time();

      $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
      $profileToCharge->setCustomerProfileId($customerProfileId);
      $paymentProfile = new AnetAPI\PaymentProfileType();
      $paymentProfile->setPaymentProfileId($paymentProfileId);
      $profileToCharge->setPaymentProfile($paymentProfile);

      $invoiceType = new AnetAPI\OrderType();
      $invoiceType->setInvoiceNumber($order->getIncrementId());

      $transactionRequestType = new AnetAPI\TransactionRequestType();
      $transactionRequestType->setTransactionType( "authCaptureTransaction");
      $transactionRequestType->setAmount($grandTotal);
      $transactionRequestType->setProfile($profileToCharge);
      $transactionRequestType->setOrder($invoiceType);


      $request = new AnetAPI\CreateTransactionRequest();
      $request->setMerchantAuthentication($merchantAuthentication);
      $request->setRefId($refId);
      $request->setTransactionRequest( $transactionRequestType);
      $controller = new AnetController\CreateTransactionController($request);
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

      if ($response != null)
      {
        if($response->getMessages()->getResultCode() == "Ok")
        {
          $tresponse = $response->getTransactionResponse();
          if ($tresponse != null && $tresponse->getMessages() != null)
          {

            $authCode = $tresponse->getAuthCode();
            $transId = $tresponse->getTransId();

            $logger->info(" Transaction Response code : " . $tresponse->getResponseCode());
            $logger->info("Charge Customer Profile APPROVED  :");
            $logger->info(" Charge Customer Profile AUTH CODE : " . $tresponse->getAuthCode());
            $logger->info(" Charge Customer Profile TRANS ID : " . $tresponse->getTransId());
            $logger->info(" Code : " . $tresponse->getMessages()[0]->getCode());
            $logger->info(" Description : " . $tresponse->getMessages()[0]->getDescription());
          }
          else
          {
            $logger->info("Transaction Failed");
            if($tresponse->getErrors() != null)
            {
              $logger->info(" Error code  : " . $tresponse->getErrors()[0]->getErrorCode() );
              $logger->info(" Error message : " . $tresponse->getErrors()[0]->getErrorText());
            }
          }
        }
        else
        {
          $logger->info("Transaction Failed");
          $tresponse = $response->getTransactionResponse();
          if($tresponse != null && $tresponse->getErrors() != null)
          {
            $logger->info(" Error code  : " . $tresponse->getErrors()[0]->getErrorCode());
            $logger->info(" Error message : " . $tresponse->getErrors()[0]->getErrorText());
          }
          else
          {
            $logger->info(" Error code  : " . $response->getMessages()->getMessage()[0]->getCode());
            $logger->info(" Error message : " . $response->getMessages()->getMessage()[0]->getText());
          }
        }
      }
      else
      {
        $logger->info("No response returned ");
      }
      if($authCode && $transId)
      {
          $transactionBuilder = $objectManager->create('\Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface');
          $paymentData = array('transId' => $transId,'authCode' => $authCode);
                         //get payment object from order object
          $payment = $order->getPayment();
          $payment->setLastTransId($paymentData['transId']);
          $payment->setTransactionId($paymentData['transId']);
          $payment->setAdditionalInformation(
                                             [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $paymentData]
          );
          $formatedPrice = $order->getBaseCurrency()->formatTxt($order->getGrandTotal());

          $message = __('The capured amount is %1.', $formatedPrice);
          //get the object of builder class
          $trans = $transactionBuilder;
          $transaction = $trans->setPayment($payment)->setOrder($order)->setTransactionId($paymentData['transId'])
                                         ->setAdditionalInformation(
                                             [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $paymentData]
                                         )
                                         ->setFailSafe(true)
                                         //build method creates the transaction and returns the object
                                         ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);

        $payment->addTransactionCommentsToOrder(
                                             $transaction,
                                             $message
        );
        $payment->setParentTransactionId(null);
        $payment->save();
        $order->save();

        $transactionId = $transaction->save()->getTransactionId();

        $tranSql = "SELECT txn_id  FROM `sales_payment_transaction` WHERE `order_id` = $orderId and txn_type='authorization'";
        $transDetails = $connection->fetchRow($tranSql);
        $parentTxnId = $transDetails['txn_id'];

        $updateParentTxnId="update sales_payment_transaction set parent_txn_id=$parentTxnId where transaction_id=$transactionId";
        $connection->query($updateParentTxnId);

      }
      return $response;
    }
}
