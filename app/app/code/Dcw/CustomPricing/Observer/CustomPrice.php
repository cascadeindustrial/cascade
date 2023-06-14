<?php

	namespace Dcw\CustomPricing\Observer;

	use Magento\Framework\Event\ObserverInterface;
	use Magento\Framework\App\RequestInterface;
	use Dcw\CustomPricing\Model\CustomPricingFactory;


	class CustomPrice implements ObserverInterface
	{
		protected $_modelCustomPricingFactory;
		protected $groupRepository;
		protected $objectmanager;
		protected $dataHelper;
		public function __construct(
		    \Magento\Framework\App\RequestInterface $request,
		    CustomPricingFactory $modelCustomPricingFactory,
		    \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
		    \Magento\Framework\ObjectManagerInterface $objectmanager,
		    \Dcw\CustomPricing\Helper\Data $dataHelper

		)
		{
		    $this->_request = $request;
		    $this->_modelCustomPricingFactory = $modelCustomPricingFactory;
		    $this->groupRepository = $groupRepository;
		    $this->_objectManager = $objectmanager;
		     $this->dataHelper = $dataHelper;

		}
		public function execute(\Magento\Framework\Event\Observer $observer) {
			//$resultPage = $this->_modelCustomPricingFactory->create();
        /*$collection = $resultPage->getCollection(); //Get Collection of module data
        var_dump($collection->getData());
        exit;*/


           $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/valid.log');
           $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);
			
			//$logger->info(json_encode((array)$this->_request->getPost()));
			$postData = $this->_request->getPost();
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
			
			$expedicted = $customerGroupId['discount_percentage'];
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

						


						//echo $customprice;exit;
            if ($shippingPreference == 1) {
            	$price = $customprice - (($customprice/100)*$standard);
            } elseif($shippingPreference == 2) {
            	$price = $customprice - (($customprice/100)*$expedicted);
            }

			
			$item->setShippingOption($shippingPreference);
            $item->setCustomPrice($price);
			$item->setOriginalCustomPrice($price);
			$item->getProduct()->setIsSuperMode(true);
		}
		/*public function getCustomerGroupId(){
               	  $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
				  $customerGroupId = $customerSession->getCustomer()->getGroupId();
				  $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
               $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
               $item = $observer->getEvent()->getData('quote_item');
               $customerid = $connection->fetchAll("SELECT discount_percentage,standard_discount_percentage FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1");
               	return $customerid;

            }*/

	}
?>
