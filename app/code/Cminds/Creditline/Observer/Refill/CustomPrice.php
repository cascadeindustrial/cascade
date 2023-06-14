<?php
namespace Cminds\Creditline\Observer\Refill;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Cminds\Creditline\Model\Config;
use Magento\Framework\App\Request\Http;
use Magento\Checkout\Model\Cart as CustomerCart;


class CustomPrice implements ObserverInterface
{
    /**
     * @var Http
     */
    protected $request;
    protected $objectmanager;
    protected $messageManager;
    protected $cart;
    protected $cartHelper;

    /**
     * @param CurrencyHelper  $currencyHelper
     * @param Calculation     $calculationHelper
     * @param Data            $creditData
     * @param Http            $request
     */
    public function __construct(
        Http $request,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        CustomerCart $cart,
        \Magento\Checkout\Helper\Cart $cartHelper
    ) {
        $this->request = $request;
        $this->_objectManager = $objectmanager;
        $this->messageManager = $messageManager;
        $this->cart = $cart;
        $this->cartHelper = $cartHelper;
    }

    public function execute(Observer $observer) {
         //printLog("test");
        $requstData = $this->request->getParams();

        $item = $observer->getEvent()->getData('quote_item');
      // printLog("summary count");
      // printLog($this->cartHelper->getSummaryCount());
      $summaryCount = $this->cartHelper->getSummaryCount();
        $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
        if($item->getSku() == Config::REFILL_PRODUCT_SKU){
          //$cartObject = $this->_objectManager->get('Magento\Checkout\Model\Cart');
          $checkoutSession = $this->_objectManager->get('Magento\Checkout\Model\Session');
          $quote = $checkoutSession->getQuote();
          $quoteItems = $quote->getItemsCollection();
          $alertNotice = 0;
          // if($summaryCount)
          // {
          //   $this->cart->truncate()->save();
          // }
          foreach($quoteItems as $quoteItem)
          {
              //if()
              //echo $item->getProduct()->getSku();exit;
              if($quoteItem->getProduct()->getSku()!='creditline')
              {
                    $this->cart->removeItem($quoteItem->getId());//->save();
                    $alertNotice = 1;
              }
          }

          if($summaryCount)
          {
            $this->messageManager->addNotice( __('Credit Line Payment has been added to your cart. Credit Line payments are processed separately, so all other cart items have been removed.') );
          }

          if(isset($requstData['total']) && !empty($requstData['total'])){
            $price = ($requstData['total'] != 'other')? $requstData['total'] : $requstData['riflllamount'];
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);
            $this->cart->getQuote()->setTotalsCollectedFlag(false);
            //$this->cart->save();
            $this->cart->getQuote()->setTriggerRecollect(1);
            $this->cart->getQuote()->collectTotals()->save();
            return;
          }else{
            return;
          }
          //$quote->save();
        }



            // echo "12";

            // exit;
            //$resultPage = $this->_modelCustomPricingFactory->create();
        /*$collection = $resultPage->getCollection(); //Get Collection of module data
        var_dump($collection->getData());
        exit;*/


           $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/valid.log');
           $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);

           // $module = $this->_request->getRouteName() . "/" .$this->_request->getControllerName() . "/" .$this->_request->getActionName();

           // if ($module == "creditline/account/refill")
           // {
           //  return;
           // }
           //$logger->info("outside of if condition");

          // printLog("outside of if condition");

            //$logger->info(json_encode((array)$this->_request->getPost()));
            $postData = $this->request->getPost();
            if($postData['delivery_options']){
                $item = $observer->getEvent()->getData('quote_item');
                $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
                $finalPrice = $postData['delivery_options'];
                $logger->info($finalPrice);
                $priceCurrencyObject = $this->_objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface');
                /*$store  = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
                $currencyCode = $store->getStore()->getCurrentCurrencyCode();
                $rate = $priceCurrencyObject->convert($finalPrice, 1, $currencyCode);
                $customprice = round($rate,2);*/
                //$logger->info($customprice);
                $price = $finalPrice;

                $shippingOption =2;
                $item->setShippingOption($shippingOption);
                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price);
                $item->getProduct()->setIsSuperMode(true);
            }
            if(!isset($postData['shipping_preference']))
                            return;

          /*if(isset($postData['pdppage_delivery_options']) && $postData['pdppage_delivery_options']==2 )
            {
                    return true;
            }*/



            /*$customerGroupId = $this->getCustomerGroupId();
            $expedicted = $customerGroupId[0]['discount_percentage'];
            $standard = $customerGroupId[0]['standard_discount_percentage'];*/
            $shippingPreference = $postData['shipping_preference'];
            $item = $observer->getEvent()->getData('quote_item');
            //$customerGroupId = $this->dataHelper->getPercentageCalculation($item);
            $customerGroupId = $this->_objectManager->get('Dcw\CustomPricing\Helper\Data')->getPercentageCalculation($item->getProduct());
            //printLog($customerGroupId);
            $expedicted = $customerGroupId['discount_percentage'];
            // printLog("expedicted");
            // printLog($expedicted);
            $standard = $customerGroupId['standard_discount_percentage'];
            $item = ( $item->getParentItem() ? $item->getParentItem() : $item );

                        //$customprice = $postData['custom_price'];


                        $finalPrice = $item->getProduct()->getFinalPrice();



                        $priceCurrencyObject = $this->_objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface'); //instance of PriceCurrencyInterface
                        $store  = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
                        $currencyCode = $store->getStore()->getCurrentCurrencyCode();

                        //$store = $this->_storeManager->getStore()->getStoreId(); //get current store id if store id not get passed
                        $rate = $priceCurrencyObject->convert($finalPrice, 1, $currencyCode);
                        $customprice = round($rate,2);



// printLog("shippingPreference");
// printLog($shippingPreference);
// printLog("customprice:".$customprice);
// printLog("expedited:".$expedicted);
                        //echo $customprice;exit;
            if ($shippingPreference == 1) {
                $price = $customprice - (($customprice/100)*$standard);
            } elseif($shippingPreference == 2) {
                $price = $customprice - (($customprice/100)*$expedicted);
            }

// printLog("price:");
// printLog($price);
            $item->setShippingOption($shippingPreference);
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);


    }
}
