<?php
namespace Dcw\CustomPricing\Controller\Adminhtml\CustomPricing;
use Dcw\CustomPricing\Model\CustomPricing as Post;


class Delete extends \Magento\Backend\App\Action
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
        // $resultRedirect = $this->resultRedirectFactory->create();
        // $data = $this->getRequest()->getPostValue();
//         $id = $data['id'];
// print_r($id);exit;
        $id = $this->getRequest()->getParam('id');
       //print_r($id);exit;
        //$contact = $this->postFactory->create()->load($id);

               $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
               $contact = $objectManager->create('\Dcw\CustomPricing\Model\CustomPricing')->load($id);
               // $contact->setData($data);
               // $contact->save(); 

        if(!$contact)
        {
            $this->messageManager->addError(__('Unable to process. please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/', array('_current' => true));
        }

        try{
            $contact->delete();
            $this->messageManager->addSuccess(__('Your Rule has been deleted !'));
        }
        catch(\Exception $e)
        {
            $this->messageManager->addError(__('Error while trying to delete contact'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
