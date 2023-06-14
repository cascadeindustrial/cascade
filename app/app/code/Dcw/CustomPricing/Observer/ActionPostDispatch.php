<?php
namespace Dcw\CustomPricing\Observer;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Variable\Model\VariableFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class ActionPostDispatch implements ObserverInterface
{
	protected $_checkoutSession;
    protected $cart;
    protected $objectmanager;
    /**
     * Updatecart constructor.
     * @param CheckoutSession $checkoutSession
     */
    public function __construct (
        CheckoutSession $checkoutSession,
        \Magento\Framework\ObjectManagerInterface $objectmanager

    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_objectManager = $objectmanager;
    }
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/cartpage.log');
           $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);

           	$request = $observer->getEvent()->getRequest();
	        $fullActionName= $request->getFullActionName();
            $productRepository = $this->_objectManager->get('\Magento\Catalog\Model\ProductRepository');
	        /*$controller = $request->getControllerName();
	        $action     = $request->getActionName();
	        $route      = $request->getRouteName();*/
	        /*echo $fullActionName."<br/>";
	        echo $controller."<br/>";
	        echo $action."<br/>";
	        echo $route."<br/>";exit;*/
             $quote = $this->_checkoutSession->getQuote();
               $quoteItems= $quote->getAllVisibleItems();
                $shippingOption = $request->getParam('shippingOption');
                 $store  = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
                $currencyCode = $store->getStore()->getCurrentCurrencyCode();
                $priceCurrencyObject = $this->_objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface');
                /*$customerGroupId = $this->getCustomerGroupId();
                if ($customerGroupId) {
                                   $expedicted = $customerGroupId[0]['discount_percentage'];
                $standard = $customerGroupId[0]['standard_discount_percentage'];*/


		if ($fullActionName == 'directory_currency_switch') {
               // $quote = Mage::getSingleton('checkout/session')->getQuote();
                if ($quote && $quote->hasItems()) {
                    foreach ($quote->getAllVisibleItems() as $item){
                    $customerGroupId = $this->_objectManager->get('Dcw\CustomPricing\Helper\Data')->getPercentageCalculation($item->getProduct());

                    if ($customerGroupId) {
                    $expedicted = $customerGroupId['discount_percentage'];
                    $standard = $customerGroupId['standard_discount_percentage'];


                    //$categoryId = $customerGroupId['category'];
                     $categoryId = explode(',',$customerGroupId['category']);

                    $brandName = (isset($customerGroupId['brand'])) ? $customerGroupId['brand'] : "";

                    $categories = $item->getProduct()->getCategoryIds();

                    $productObj = $productRepository->get($item->getProduct()->getSku());
                    $brand = $productObj->getData('brand');

                        $logger->info(json_encode((array)$item['shipping_option']));
                         if ($item->getCustomPrice()!='') {

                        //add condition for target item
                        /*$customPrice = $item->getFinalPrice();//use custom price logic
                        exit;*/
                        //$logger->info($customPrice);
                        /*$baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
                        $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
                        $customPrice = Mage::helper('directory')->currencyConvert($customPrice, $baseCurrencyCode, $currentCurrencyCode);*/
                        $product = $item->getProduct();
                        $finalPrice = $item->getProduct()->getFinalPrice();
                        $logger->info(json_encode((array)$finalPrice));
                        $rate = $priceCurrencyObject->convert($finalPrice, 1, $currencyCode);
                        $customprice = round($rate,2);
                       // if((strpos($brandName, $brand) !== false) || (in_array($categoryId,$categories))) {
                        if((strpos($brandName, $brand) !== false) || (count(array_intersect($categoryId,$categories)))!=0) {
                            if ($item->getShippingOption()==1) {
                               $price = $customprice - (($customprice/100)*$standard);
                            } else if($item->getShippingOption()==2){
                                $price = $customprice - (($customprice/100)*$expedicted);
                            }
                        }
                       // echo $price;exit;

                        $item->setCustomPrice($price);
                        $item->setOriginalCustomPrice($price);
                        $item->getProduct()->setIsSuperMode(true);
                        //$quote->collectTotals()->save();
                        }
                      }
                    }
                    $quote->setTotalsCollectedFlag(false)->collectTotals()->save();
                }
            }

	}
    //  public function getCustomerGroupId(){
    //      $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
    //       $customerGroupId = $customerSession->getCustomer()->getGroupId();
    //       $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
    //            $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
    //            $customerid = $connection->fetchAll("SELECT discount_percentage,standard_discount_percentage FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1");
    //             return $customerid;
    // }
}
