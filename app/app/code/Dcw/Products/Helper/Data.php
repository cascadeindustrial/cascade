<?php
namespace Dcw\Products\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_currency;
    protected $storeConfig;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
       \Magento\Directory\Model\Currency $currency       
      
    ) {
        $this->customerSession = $customerSession;
       $this->_currency = $currency; 
        parent::__construct($context);
    }

    public function isLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }
    public function getCurrentCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }
}