<?php
namespace Dcw\CustomPricing\Controller\Price;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Variable\Model\VariableFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;
class Indiproductprice extends \Magento\Framework\App\Action\Action
{
    protected $logger;
    protected $resultPageFactory;
    protected $variableFactory;
    protected  $_productloader;
    protected $checkoutSession;
    protected $quoteFactory;
    protected $_productRepository;
    protected $objectmanager;
    protected $quoteItemFactory;
    protected $itemResourceModel;
    public $quoteRepository;


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
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        \Magento\Quote\Model\ResourceModel\Quote\Item $itemResourceModel,
        CartRepositoryInterface $quoteRepository

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
        $this->quoteFactory = $quoteFactory;
        $this->quoteItemFactory = $quoteItemFactory;
        $this->itemResourceModel = $itemResourceModel;
         $this->quoteRepository = $quoteRepository;
        parent::__construct($context);
    }
     public function execute() {


        $logFile='/var/log/four.log';
        $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $quote = $this->checkoutSession->getQuote();
        $productRepository = $this->_objectManager->get('\Magento\Catalog\Model\ProductRepository');
         $deliveryOption = $this->getRequest()->getParam('deliveryOption');

        $id = (int)$this->getRequest()->getParam('id');
        $productId = (int)$this->getRequest()->getParam('product_id');
        $quoteItem = null;
        if ($id) {
            $quoteItem = $this->cart->getQuote()->getItemById($id);
        }
         $logger->info(json_encode((array)$quoteItem));
         //$customerGroupId = $this->getCustomerGroupId();
         $customerGroupId = $this->_objectManager->get('Dcw\CustomPricing\Helper\Data')->getPercentageCalculation($quoteItem->getProduct());
            $expedicted = $customerGroupId['discount_percentage'];
            $standard = $customerGroupId['standard_discount_percentage'];
            //$categoryId = $customerGroupId['category'];
              $categoryId = explode(',',$customerGroupId['category']);
            $brandName = (isset($customerGroupId['brand'])) ? $customerGroupId['brand'] : "";
            $categories = $quoteItem->getProduct()->getCategoryIds();
            $productObj = $productRepository->get($quoteItem->getProduct()->getSku());
                $brand = $productObj->getData('brand');


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
                if ($deliveryOption == 1) {
                    $price = $customprice - (($customprice/100)*$standard);
                } elseif($deliveryOption == 2) {
                    $price = $customprice - (($customprice/100)*$expedicted);
                }
            //}
            $quoteItem->setShippingOption($deliveryOption);
            $quoteItem->setCustomPrice($price);
            $quoteItem->setOriginalCustomPrice($price);
            $quoteItem->getProduct()->setIsSuperMode(true);
            $quoteItem->calcRowTotal();
            $quoteItem->save();
            $quote->setTotalsCollectedFlag(false)->collectTotals()->save();
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');

    }
    // public function getCustomerGroupId(){
    //      $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
    //       $customerGroupId = $customerSession->getCustomer()->getGroupId();
    //       $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
    //            $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
    //            $customerid = $connection->fetchAll("SELECT discount_percentage,standard_discount_percentage,category,brand FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1");
    //             return $customerid;
    // }

}
