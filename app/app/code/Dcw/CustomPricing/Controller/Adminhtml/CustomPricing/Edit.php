<?php
//use Dcw\CreditForm\Model\ResourceModel\Post\CollectionFactory;

namespace Dcw\CustomPricing\Controller\Adminhtml\CustomPricing;

class Edit extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        //CollectionFactory $postCollectionFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        //$this->collection = $postCollectionFactory->create();

        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        // $resultRedirect = $this->resultRedirectFactory->create();
   //       $data = $this->getRequest()->getParam('id');
   //        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
   // $contact = $objectManager->create('\Dcw\CustomPricing\Model\CustomPricing')->load($data);
         //print_r(json_encode($contact->getData()));exit;
         //$//items = $this->collection->getItems();
         //print_r(json_encode($items));exit;
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Dcw_CustomPricing::edit');
        $resultPage->getConfig()->getTitle()->prepend((__('Edit Pricing Rule')));
        return $resultPage;
    }
}