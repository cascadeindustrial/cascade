<?php

namespace Dcw\CustomPricing\Plugin;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;

class DefaultItem
{

    protected $productRepo;
    protected $objectmanager;
    protected $checkoutSession;

    public function __construct(ProductRepositoryInterface $productRepository,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
    \Magento\Checkout\Model\Session $checkoutSession
    )
    {
        $this->productRepo = $productRepository;
        $this->_objectManager = $objectmanager;
        $this->checkoutSession = $checkoutSession;
    }

    public function aroundGetItemData($subject, \Closure $proceed, Item $item)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/custrate.log');
           $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);

        $data = $proceed($item);
        $product = $this->productRepo->getById($item->getProduct()->getId());
        $attributes = $product->getAttributes();
        $deliveryOption = $item->getBuyRequest();
         $logger->info(json_encode((array)$item->getData('shipping_option')));
        $shippingOptions = $item->getData('shipping_option');
        $productsku = $item->getData('sku');
        $productData = $this->_objectManager->get('Magento\Catalog\Api\ProductRepositoryInterface')->get($productsku);

       // $hydraulicCategoryId = $this->getHydralicCategoryId();
        $categories = $product->getCategoryIds();
        $customerSession = $this->_objectManager->create('Magento\Customer\Model\Session');
        $postData = 0;
       // if (in_array($hydraulicCategoryId,$categories)) {
            if($customerSession->isLoggedIn()) {

               //if ($deliveryOption['delivery_options']) {
               if ($shippingOptions) {
                    $postData = $shippingOptions;
                } else {
                     $postData = $deliveryOption['shipping_preference'];
                }
            }
       // }
     /* $modelNo= $productData->getModelNo();
        if ($modelNo) {
          $modelNo = $modelNo;
        } else {
          $modelNo = "";
        }*/
        $atts = [
            //"model_no" => $attributes['model_no']->getFrontend()->getValue($product),
            "model_no" => $productData->getModelNo(),
            "standard_delivery_time" => $productData->getAttributeText('standard_delivery_time'),
            "expedited_delivery_time" => $productData->getAttributeText('expedited_delivery_time'),
            "shippingOption" => $postData,


            //"product_part_number" => $attributes['part_number']->getFrontend()->getValue($product)
        ];
        return array_merge($data, $atts);
    }
    // public function getHydralicCategoryId(){
    //
    //               $customerSession = $this->_objectManager->create('Magento\Customer\Model\Session');
    //               $loggedCustomerGroupId = $customerSession->getCustomer()->getGroupId();
    //               $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
    //               $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
    //               $customerid = $connection->fetchRow("SELECT * FROM dcw_custom_price_rules WHERE customer_group = '".$loggedCustomerGroupId."' and status = 1");
    //                $hydralicCategoryId = $customerid['category'];
    //                /*$categories = $_item->getCategoryIds();*/
    //             return $hydralicCategoryId;
    //
    //         }

}
