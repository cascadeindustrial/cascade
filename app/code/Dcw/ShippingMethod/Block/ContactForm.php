<?php
namespace Dcw\ShippingMethod\Block;
use Magento\Framework\View\Element\Template;
class ContactForm extends \Magento\Contact\Block\ContactForm
{
 
public function _prepareLayout()
  {
    $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
      $breadcrumbs->addCrumb('home', array('label'=>'Home', 'title'=>'Go to Home Page','link'=>$baseUrl));
      $breadcrumbs->addCrumb('title', array('label'=>'Contact us', 'title'=>'contactus'));
    }
  }

}