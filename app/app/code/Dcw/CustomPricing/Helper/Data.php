<?php

namespace Dcw\CustomPricing\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\ObserverInterface;
use Dcw\CustomPricing\Model\CustomPricingFactory;

/**
 * Class Data
 * @package Fisha\Checkout\Helper
 */
class Data extends AbstractHelper
{
   protected $context;

   protected $_checkoutSession;

    protected $registry;
    protected $_objectManager;

   public function __construct (
        Context $context,
        CheckoutSession $checkoutSession,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        CustomPricingFactory $modelCustomPricingFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\ObjectManagerInterface $objectmanager
    ) {
        $this->context = $context;
        $this->registry = $registry;
        $this->_checkoutSession = $checkoutSession;
        $this->_request = $request;
        $this->_modelCustomPricingFactory = $modelCustomPricingFactory;
        $this->groupRepository = $groupRepository;
        $this->_objectManager = $objectmanager;
        parent::__construct($context);
    }

    public function getPercentageCalculation($product)
    {
        $productRepository = $this->_objectManager->get('\Magento\Catalog\Model\ProductRepository');
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/discountprice.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
        $categories = $product->getCategoryIds();
        // printLog("categories");
        // printLog($categories);
        // printLog("categories count:".count($categories));
        // printLog("is customer logged in".$customerSession->isLoggedIn());
        if(!$customerSession->isLoggedIn() || count($categories)==0)
                return false;

        //printLog("after if loop");
        $discountData = $this->getPricingData($product);

        if($discountData==0)
              return '';

        // echo "<pre>";
        // print_r($discountData);
        // echo "before submitting";
        // exit;
        // echo "<pre>";
        // echo "testests";
        // print_r($discountData);
        //exit;
        // printLog("discountData");
        // printLog($discountData);

        $expediteddeli = $product->getAttributeText('expedited_delivery_time');
        $standarddeli = $product->getAttributeText('standard_delivery_time');
        $productObj = $productRepository->get($product->getSku());
        //$deliOption = $productObj->getData('enable_delivery_options');
        $_product = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($product->getId());
        $deliOption = $_product->getData('enable_delivery_options');

       //$categoryId = $discountData['category'];
         //$categoryId = explode(',',$discountData['category']);
        $brandName = (isset($discountData['brand'])) ? $discountData['brand'] : "";
        //$categories = $product->getCategoryIds();
        $brand = $productObj->getData('brand');
        // $logger->info($product->getId());
        // $logger->info($productObj->getId());

        //printLog("brandvalue".$brand);

        // if(!isset($brand))
        //         return false;


        if ($discountData) {
          if((strpos($brandName, $brand) !== false) || ($deliOption == 1 && ($expediteddeli != '' && $standarddeli != '')))
                      return $discountData;
          }
    }
    public function getPricingData($product)
    {
          $productRepository = $this->_objectManager->get('\Magento\Catalog\Model\ProductRepository');
          $productObj = $productRepository->get($product->getSku());
          $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
          $customerGroupId = $customerSession->getCustomer()->getGroupId();
          $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
          $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
          $categories = $product->getCategoryIds();
          //printLog($categories);
          //$catIds = implode(',',$categories);
          $brandId = $productObj->getData('brand');
          // echo "<pre>";
          // print_r($categories);
          $rulesCatIds = array();
          $finalResult = $tempResult = 0;
          $finalResult1 = array();
          if ($customerSession->isLoggedIn()) {
            if($brandId)
            {
              //$sql="SELECT * FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1 and brand = '".$brandId."' ORDER BY id DESC";
              $sql="SELECT * FROM dcw_custom_price_rules WHERE status = 1 and brand = '".$brandId."' and FIND_IN_SET($customerGroupId,customer_group) > 0 ORDER BY id DESC";
              $customPrice = $connection->fetchAll($sql);
              if(!$customPrice){
               //$sql="SELECT * FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1 and brand = '' ORDER BY id DESC";
               $sql="SELECT * FROM dcw_custom_price_rules WHERE status = 1 and brand = '' and FIND_IN_SET($customerGroupId,customer_group) > 0 ORDER BY id DESC";
               $customPrice = $connection->fetchAll($sql);
              }
            }else{
              //$sql="SELECT * FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1 and brand = '' ORDER BY id DESC";
              $sql="SELECT * FROM dcw_custom_price_rules WHERE status = 1 and brand = '' and FIND_IN_SET($customerGroupId,customer_group) > 0 ORDER BY id DESC";
              $customPrice = $connection->fetchAll($sql);
            }
            // printLog($sql);
            // printLog("customprice");
            // printLog($customPrice);
            $finalResult = $customPrice;
            if(count($finalResult)==0)
                return $tempResult;
            //print_r($finalResult);
            if(isset($customPrice[0]) && is_array($customPrice[0]))
            {
              foreach($customPrice as $res)
              {
                $rulesCatId = $res['category'];
                $rulesCatIdsOriginal = explode(',',$rulesCatId);
                // foreach($rulesCatIdsOriginal as $ruleCatId)
                // {
                //   preg_match_all('!\d+!', $ruleCatId, $matches);
                //   if(is_array($matches))
                //   {
                //     $rulesCatIds[]=$matches[0][0];
                //   }
                // }
                // foreach($rulesCatIdsOriginal as $ruleCatId)
                // {
                //   preg_match('#\((.*?)\)#', $ruleCatId, $match);
                //   if($match)
                //   {
                //     $rulesCatIds[] = $match[1];
                //   }
                // }

                $c = array_intersect($rulesCatIdsOriginal, $categories);
                if (count($c) > 0) {
                    $finalResult1 = $res;
                    break;
                }
              }
              if(count($finalResult1)==0)
                  $finalResult = 0;
              else
                  $finalResult = $finalResult1;

              // print_r($finalResult1);
              // echo "123123123";
              // print_r($finalResult);
           }else{
             $rulesCatId = $finalResult['category'];
             $rulesCatIdsOriginal = explode(',',$rulesCatId);
             // foreach($rulesCatIdsOriginal as $ruleCatId)
             // {
             //   preg_match_all('!\d+!', $ruleCatId, $matches);
             //   if(is_array($matches))
             //   {
             //     $rulesCatIds[]=$matches[0][0];
             //   }
             // }
             // foreach($rulesCatIdsOriginal as $ruleCatId)
             // {
             //   preg_match('#\((.*?)\)#', $ruleCatId, $match);
             //   if($match)
             //   {
             //     $rulesCatIds[] = $match[1];
             //   }
             // }
             $c = array_intersect($rulesCatIdsOriginal, $categories);
             if(count($c) == 0)
             {
               $finalResult = 0;
             }
          }
          if($finalResult==0){
            //echo "<br>in nesteed if condition<br>";
            //$sql="SELECT * FROM dcw_custom_price_rules WHERE customer_group = '".$loggedCustomerGroupId."' and status = 1 and  brand = '' ORDER BY id DESC";
            $sql="SELECT * FROM dcw_custom_price_rules WHERE status = 1 and brand = '' and FIND_IN_SET($customerGroupId,customer_group) > 0 ORDER BY id DESC";
            $result = $connection->fetchAll($sql);
            // echo "<pre>";
            // print_r($result);
            foreach($result as $res)
            {
              $rulesCatId = $res['category'];
              $rulesCatIdsOriginal = explode(',',$rulesCatId);
              // printLog($rulesCatIdsOriginal);
              // printLog($categories);
              // print_r($rulesCatIdsOriginal);
              // print_r($categories);
              $c = array_intersect($rulesCatIdsOriginal, $categories);
              //print_r($c);
              if (count($c) > 0) {
                  $finalResult1 = $res;
                  break;
              }
            }
            if(count($finalResult1)==0)
                $finalResult = 0;
            else
                $finalResult = $finalResult1;
            //exit;
          }
          // echo "<pre>";
          // print_r($finalResult);
          // exit;
            //$customPrice = $connection->fetchAll("SELECT discount_percentage,standard_discount_percentage,category,brand FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1");
            return $finalResult;
          }
    }


}
