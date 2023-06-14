<?php
namespace Dcw\CustomPricing\Controller\Adminhtml\CustomPricing;

class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Index';

    protected $resultPageFactory;
    protected $postFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Dcw\CustomPricing\Model\CustomPricingFactory $postFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->postFactory = $postFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        // $logFile='/var/log/saved.log';
        // $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
        // $logger = new \Zend\Log\Logger();
        // $logger->addWriter($writer);

        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource=$objectManager->create('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        // echo "<pre>";
        // print_r($data);
        // exit;
        // $logger->info("firstdata");
        //         $logger->info($data);
        // echo "<pre>";
        // print_r($this->getRequest()->getParams());
        // print_r($data);
        //$requestParams = $this->getRequest()->getParams();
        $categoryList = $data['category'];
        foreach($categoryList as $catId)
        {
          $sql = "SELECT value FROM `catalog_category_entity_varchar` WHERE `attribute_id` = 45 AND `entity_id` = ".$catId ;
          $catName = $connection->fetchRow($sql);
          $catNames[] = $catName['value'];
        }

        $data['customer_group'] = implode(',',$data['customer_group']);
        $data['category'] = implode(',',$data['category']);
        $data['category_name'] = implode(',',$catNames);


        //print_r($data);exit;

        if($data)
        {
            try{

                $contact = $this->postFactory->create();

                //$data = array_filter($data, function($value) {return $value !== ''; });
                // $logger->info("data");
                // $logger->info($data);

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                $contact = $objectManager->create('\Dcw\CustomPricing\Model\CustomPricing');
                // $contact->load($id);
                $contact->setData($data);
                $contact->save();

                $this->messageManager->addSuccess(__('Successfully saved the item.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

            //      if ($this->getRequest()->getParam('back')) {
            //     $resultRedirect->setPath('*/*/edit', ['id' => $data->getId()]);
            // }
                return $resultRedirect->setPath('*/*/');
            }
            catch(Exception $e)
            {
                $this->messageManager->addError($e->getMessage());
                echo $e->getMessage();exit;
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                return $resultRedirect->setPath('*/*/newform', ['id' => $contact->getId()]);
            }
        }

         return $resultRedirect->setPath('*/*/');
    }
}
