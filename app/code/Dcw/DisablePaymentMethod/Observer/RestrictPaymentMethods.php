<?php
namespace Dcw\DisablePaymentMethod\Observer;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Magento\Backend\Model\Session\Quote as adminQuoteSession;
class RestrictPaymentMethods implements ObserverInterface {
	protected $_state;
	protected $_session, $_quote;  //Fetch Codes of Payment Methods---
	public function __construct(\Magento\Framework\App\State $state,Session $checkoutSession,adminQuoteSession $adminQuoteSession)
	{
		$this->_state = $state;
		if ($state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML) {
			$this->_session = $adminQuoteSession;
		} else {
			$this->_session = $checkoutSession;
		}
	$this->_quote = $this->_session->getQuote();
}  /**  * payment_method_is_active event handler.  *  * @param \Magento\Framework\Event\Observer $observer  */
	public function execute(EventObserver $observer) {  //Code of Current Payment Method--
		$code = $observer->getEvent()->getMethodInstance()->getCode();  /*  * You can use $this->_quote object to apply conditions based on current quote--  * For instance -
		$totalItems = $this->_quote->getItemsCount(); //Total Items in cart  */
		$items = $this->_quote->getAllVisibleItems(); //Items in cart  */
		/*  * Now, you can check if current method code is as same as the code of payment method which  * you want to disable then apply the following condition  * Suppose you want to do for CHECKMO  * Following code will exclude the CHECKMO payment method from both admin panel and front-end  */
		if($code == 'anet_creditcard') {
			foreach($items as $item)
			{
					$productSku = $item->getSku();
					if($productSku == 'creditline')
					{
						$checkResult = $observer->getEvent()->getResult();  //this is disabling the payment method at both checkout page in front-end and admin panel  $checkResult->setData('is_available', false);  }  /*  * If payment method has to be excluded from Fron-end Only then do the following  * Suppose you want to do for COD  */  if ($this->_state->getAreaCode() != \Magento\Framework\App\Area::AREA_ADMINHTML && $code == self::COD) {  $checkResult = $observer->getEvent()->getResult();  //this is disabling the payment method at both checkout page in front-end only
						$checkResult->setData('is_available', false);
						break;
					}
			}
		}
	}
}
