<?php
namespace Dcw\CustomPricing\Controller\Price;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Variable\Model\VariableFactory;
use Psr\Log\LoggerInterface;
class Customprices extends \Magento\Framework\App\Action\Action
{
    protected $logger;
    protected $resultPageFactory;
    protected $variableFactory;
    protected  $_productloader;
    protected $checkoutSession;
    protected $quoteFactory;
    protected $_productRepository;
    protected $objectmanager;
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        VariableFactory $variableFactory,
        ResultFactory $resultPageFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->logger = $logger;
        $this->resultPageFactory = $resultPageFactory;
        $this->_productloader = $_productloader;
        $this->variableFactory = $variableFactory;
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;
        $this->_productRepository = $productRepository;
        $this->_objectManager = $objectmanager;
        $this->_request = $request;
        parent::__construct($context);
    }
     public function execute() {
        $quote = $this->checkoutSession->getQuote();
        $quoteItems= $quote->getAllVisibleItems();
        $shippingOption = $this->getRequest()->getParam('shippingOption');

        $logFile='/var/log/validation.log';
        $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        //$logger->info($shippingOption);
        //$customerGroupId = $this->getCustomerGroupId();


        $productRepository = $this->_objectManager->get('\Magento\Catalog\Model\ProductRepository');
        $priceCurrencyObject = $this->_objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface'); //instance of PriceCurrencyInterface
        $store  = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currencyCode = $store->getStore()->getCurrentCurrencyCode();
        foreach ($quoteItems as $item )
        {
            $logger->info(json_encode((array)$item['shipping_option']));
            if ($item->getCustomPrice()!='') {

                /*if(empty($item->getShippingOption()))
                      break;*/
                $product = $item->getProduct();
                $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
                $customerGroupId = $this->_objectManager->get('Dcw\CustomPricing\Helper\Data')->getPercentageCalculation($item->getProduct());

                $expedicted = $customerGroupId['discount_percentage'];
                $standard = $customerGroupId['standard_discount_percentage'];
                //$categoryId = $customerGroupId['category'];
                $categoryId = explode(',',$customerGroupId['category']);
                $brandName = (isset($customerGroupId['brand'])) ? $customerGroupId['brand'] : "";
                //$customprice = $product->getFinalPrice();
                $categories = $item->getProduct()->getCategoryIds();
                //print_r($expedicted);exit;

                 $productObj = $productRepository->get($item->getProduct()->getSku());
                $brand = $productObj->getData('brand');
                $finalPrice = $item->getProduct()->getFinalPrice();
                $rate = $priceCurrencyObject->convert($finalPrice, 1, $currencyCode);
                $customprice = round($rate,2);

                /*$logger->info("in foreach loop");
                $logger->info("Product type:".$product->getTypeId());
                $logger->info("final Price:".$customprice);
                $logger->info($product->getSku());
                $logger->info($item->getShippingOption());*/
                //if(((strpos($brandName, $brand) !== false)) || (in_array($categoryId,$categories))) {
                 //if((strpos($brandName, $brand) !== false) || (count(array_intersect($categoryId,$categories)))!=0) {
                    if ($item->getShippingOption()==2) {
                       $price = $customprice - (($customprice/100)*$standard);
                    } else if($item->getShippingOption()==1){
                        $price = $customprice - (($customprice/100)*$expedicted);
                    }

                    if ($shippingOption==2) {
                       $price = $customprice - (($customprice/100)*$expedicted);
                    } else if($shippingOption==1){
                        $price = $customprice - (($customprice/100)*$standard);
                    }
                //}
                //$logger->info("Price:".$price);
                if ($shippingOption) {
                   $item->setShippingOption($shippingOption);
                } else {
                    $item->setShippingOption($item->getShippingOption());
                }

                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price);
                $item->save();
            }
        }
        // $quote->save();
        // $quote->collectTotals()->save();
        $quote->setTotalsCollectedFlag(false)->collectTotals()->save();
        return $this->resultRedirectFactory->create()->setPath('checkout/cart');
    }
    /*public function getCustomerGroupId(){
         $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
          $customerGroupId = $customerSession->getCustomer()->getGroupId();
          $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
               $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
               $customerid = $connection->fetchAll("SELECT discount_percentage,standard_discount_percentage,category,brand FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1");
                return $customerid;
    }*/
}
