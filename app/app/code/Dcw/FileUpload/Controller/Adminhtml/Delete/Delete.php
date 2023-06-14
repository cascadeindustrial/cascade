<?php

namespace Dcw\FileUpload\Controller\Adminhtml\Delete;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
//use Magento\Customer\Api\CustomerRepositoryInterface;
//use Magento\Framework\Filesystem;

class Delete extends \Magento\Backend\App\Action
 {
      protected $_resultPageFactory;
    protected $layoutFactory;
    protected $csvWriter;
    protected $fileFactory;
    protected $directoryList;
    protected $resultRawFactory;
    protected $_messageManager;

    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Framework\File\Csv $csvWriter, \Magento\Framework\App\Filesystem\DirectoryList $directoryList, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    , \Magento\Framework\Message\ManagerInterface $messageManager) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->layoutFactory = $layoutFactory;
        $this->layoutFactory = $layoutFactory;
        $this->_messageManager = $messageManager;
        $this->resultRawFactory = $resultRawFactory;
        $this->csvWriter = $csvWriter;
        $this->fileFactory = $fileFactory;
        $this->directoryList = $directoryList;
        parent::__construct(
                $context
        );
    }
      public function execute() {
//$customer_id=$this->getRequest()->getParam('');
        //echo $customer_id;
        //echo "test";exit;
        $objm = \Magento\Framework\App\ObjectManager::getInstance();
        $customerId = $this->getRequest()->getParam('id');
        $pdfPath = $this->getRequest()->getParam('attachment_name');
        //echo $pdfPath;
        $paramPdf = str_replace("@","/",$pdfPath);
        //echo $paramPdf;
        //echo $customerId;exit;
        $resource = $objm->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        //echo $customerId;exit;
       //  $query = "UPDATE {$table} SET sku = '{$sku}' WHERE entity_id = "
       // . (int)$productId;
        //$deleteQuery = "delete FROM customer_entity_text WHERE 'attribute_id' = 188 AND 'entity_id' = ".$customerId;
        $attribute = "SELECT attribute_id  FROM `eav_attribute` WHERE `attribute_code` LIKE '%uploadfile%'";
        //echo $attributeId;exit;
        $attributeData = $connection->fetchRow($attribute);
        //print_r($attributeData);exit;

        $attributeId = $attributeData['attribute_id'];

        $selectQuery = "SELECT * FROM `customer_entity_text` WHERE `attribute_id` = $attributeId AND `entity_id` = $customerId";
        $customerEntityData = $connection->fetchRow($selectQuery);

        $customerAttachmentData = explode(",",$customerEntityData['value']);

        // echo "<pre>";
        // print_r($customerAttachmentData);exit;

        if(count($customerAttachmentData)>1)
        {
          //echo "<pre>";

          //print_r($customerAttachmentData);//exit;

          foreach($customerAttachmentData as $key => $attachment)
          {
            if($attachment == $paramPdf)
            {
                unset($customerAttachmentData[$key]);
            }
          }

          // echo "<pre>";
          // print_r($customerAttachmentData);//exit;


          $test = array_values($customerAttachmentData);

          $finalListOfAttachment = implode(',',$test);

          //$finalAttachment = $test[0];

          //echo $finalListOfAttachment;exit;

          $updateQuery = "update `customer_entity_text` set value= '$finalListOfAttachment' WHERE `attribute_id` = $attributeId AND `entity_id` = $customerId";
          $connection->query($updateQuery);
        }
        else{

              $deleteQuery = "delete FROM `customer_entity_text` WHERE `attribute_id` = $attributeId AND `entity_id` = $customerId";
              $connection->query($deleteQuery);

        }


    //
    // echo $selectQuery;exit;
        //echo $deleteQuery;exit;

        $this->messageManager->addSuccess(__('Uploaded file deleted successfully'));

        $resultRedirect = $this->resultRedirectFactory->create();

        $url = $this->_redirect->getRefererUrl();

        $resultRedirect->setUrl($url);
        return $resultRedirect;

        //$this->_redirect('*/*/*');

    // $customerData = $objm->create('Magento\Customer\Model\Customer')->load(7)->getUploadfile();
    // echo $customerData;
    //     $resultPage = $this->_resultPageFactory->create();
    //             //echo "inside controller";
    //
    //
    //     return $resultPage;
    }
 }
?>
