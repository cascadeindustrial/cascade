<?php
namespace Dcw\CreditForm\Block;

use Magento\Store\Model\StoreManagerInterface;
class Index extends \Magento\Framework\View\Element\Template
{
  protected $storeManager;
  private $redirectFactory;
  protected $postFactory;
      protected $directoryBlock;
      protected $_isScopePrivate;
      protected $_messageManager;
      protected $customerSession;
  public function __construct(\Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
                 StoreManagerInterface $storeManager,
                \Magento\Framework\View\Element\Template\Context $context,\Dcw\CreditForm\Model\PostFactory $postFactory,
                \Magento\Framework\Message\ManagerInterface $messageManager,
                \Magento\Customer\Model\Session $customerSession,
                \Magento\Directory\Block\Data $directoryBlock, array $data = [])
  {
        $this->storeManager = $storeManager;
        $this->redirectFactory = $redirectFactory;
        $this->postFactory = $postFactory;
        $this->_isScopePrivate = true;
        $this->directoryBlock = $directoryBlock;
        $this->_messageManager = $messageManager;
         $this->customerSession = $customerSession;
    parent::__construct($context);
  }
  public function _prepareLayout()
  {
    $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
      $breadcrumbs->addCrumb('home', array('label'=>'Home', 'title'=>'Go to Home Page','link'=>$baseUrl));
      $breadcrumbs->addCrumb('title', array('label'=>'Credit Application Form', 'title'=>'applicationform'));
    }
  }
  public function getCustomerId()
  {
      return $this->customerSession->getCustomer()->getId();
  }
  public function getFormAction()
  {
      $baseurl = $this->getBaseUrl();
      $baseurl .= "creditform/Form/Save";
      return $baseurl ;
  }
  public function getStatus()
  {

    $collection = $this->postFactory->create();
    $id = $this->customerSession->getCustomer()->getId();
    $status = $collection->getCollection()->addFieldToFilter('customer_id', $id)->getData();
    if(!count($status))
    return "";
    switch($status[0]['status'])
     {
         case "0" : $str="Your credit application has been submitted successfully. Pending 
approval.";
                    break;
         case "1" : $str="Your credit application has been approved.";
                    break;
         default  : $str="";
     }
     return $str ;


  }
  public function getCustomerEmail()
  {
      return $this->customerSession->getCustomer()->getEmail();
  }
}
