<?php
namespace Dcw\CustomPricing\Observer;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\ObserverInterface;
use Dcw\CustomPricing\Model\CustomPricingFactory;
/**
 * Class UpdateItemsAfter
 * @package VendorName\Changeprice\Observer
 */
class UpdateItemsAfter implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;
    protected $cart;
    /**
     * Updatecart constructor.
     * @param CheckoutSession $checkoutSession
     */
    public function __construct (
        CheckoutSession $checkoutSession,
        \Magento\Framework\App\RequestInterface $request,
        CustomPricingFactory $modelCustomPricingFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_request = $request;
        $this->_modelCustomPricingFactory = $modelCustomPricingFactory;
        $this->groupRepository = $groupRepository;
        $this->_objectManager = $objectmanager;
        $this->cart = $cart;
    }
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /*$cart = $observer->getData('cart');
        $quote = $cart->getData('quote');
        $items = $quote->getAllItems();*/
        $logFile='/var/log/validation.log';
        $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info("IN CustomPrice.php file");
        //$quote = $this->_checkoutSession->getQuote();

        $postData = $this->_request->getPost();
        if(!isset($postData['shipping_preference']) && ($postData['pdppage_delivery_options']))
                        throw new \Magento\Framework\Exception\LocalizedException(__("Please select one of the Delivery options"));
        if(!isset($postData['shipping_preference']))
                return;

        $quoteItem = $observer->getEvent()->getQuoteItem();

            //$customerGroupId = $this->getCustomerGroupId();
            $customerGroupId = $this->_objectManager->get('Dcw\CustomPricing\Helper\Data')->getPercentageCalculation($quoteItem->getProduct());
            $expedicted = $customerGroupId['discount_percentage'];
            $standard = $customerGroupId['standard_discount_percentage'];
            $shippingPreference = $postData['shipping_preference'];
            //$customprice = $postData['custom_price'];
            //$categoryId = $customerGroupId['category'];
             $categoryId = explode(',',$customerGroupId['category']);
            // $brandName = $customerGroupId['brand'];
            $brandName = (isset($customerGroupId['brand'])) ? $customerGroupId['brand'] : "";
            $categories = $quoteItem->getProduct()->getCategoryIds();
            $brand = $quoteItem->getProduct()->getData('brand');

            $finalPrice = $quoteItem->getProduct()->getFinalPrice();

            $logger->info($finalPrice);

            $priceCurrencyObject = $this->_objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface'); //instance of PriceCurrencyInterface
            $store  = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
            $currencyCode = $store->getStore()->getCurrentCurrencyCode();

            $rate = $priceCurrencyObject->convert($finalPrice, 1, $currencyCode);
            $customprice = round($rate,2);

            $logger->info($customprice);

            //if((strpos($brandName, $brand) !== false) || (in_array($categoryId,$categories))) {
            //if((strpos($brandName, $brand) !== false) || (count(array_intersect($categoryId,$categories)))!=0) {
                    if ($shippingPreference == 1) {
                        $price = $customprice - (($customprice/100)*$standard);
                    } elseif($shippingPreference == 2) {
                        $price = $customprice - (($customprice/100)*$expedicted);
                    }
               // }
            // printCustomLog("custom price");
            // printCustomLog($price);

            $quoteItem->setShippingOption($shippingPreference);
            $quoteItem->setCustomPrice($price);
            $quoteItem->setOriginalCustomPrice($price);
            $quoteItem->getProduct()->setIsSuperMode(true);
            $quoteItem->calcRowTotal();
            $quoteItem->save();
            //$quote->setTotalsCollectedFlag(false)->collectTotals()->save();

        //$this->_checkoutSession->getQuote()->collectTotals()->save();
    }
    // public function getCustomerGroupId(){
    //               $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
    //               $customerGroupId = $customerSession->getCustomer()->getGroupId();
    //               $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
    //            $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
    //            $customerid = $connection->fetchAll("SELECT discount_percentage,standard_discount_percentage,category,brand FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1");
    //             return $customerid;
    //         }
}
