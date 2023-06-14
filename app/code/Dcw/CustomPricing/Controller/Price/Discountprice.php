<?php
namespace Dcw\CustomPricing\Controller\Price;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Variable\Model\VariableFactory;
use Psr\Log\LoggerInterface;
use Dcw\CustomPricing\Model\CustomPricingFactory;


class Discountprice extends \Magento\Framework\App\Action\Action
{
    protected $_modelCustomPricingFactory;
    protected $objectmanager;
    protected $logger;
    protected $resultPageFactory;
    protected $variableFactory;
    protected  $_productloader;
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        VariableFactory $variableFactory,
        CustomPricingFactory $modelCustomPricingFactory,
        ResultFactory $resultPageFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Framework\ObjectManagerInterface $objectmanager
    )
    {
        $this->_modelCustomPricingFactory = $modelCustomPricingFactory;
        $this->logger = $logger;
        $this->resultPageFactory = $resultPageFactory;
        $this->_productloader = $_productloader;
        $this->variableFactory = $variableFactory;
        $this->_objectManager = $objectmanager;
        parent::__construct($context);
    }
    public function execute()
    {

      $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/custprice.log');
           $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);
           
           // $customerGroupId = $this->getCustomerGroupId();
           // $expedicted = $customerGroupId[0]['discount_percentage'];
           // $standard = $customerGroupId[0]['standard_discount_percentage'];
           $price = $this->getRequest()->getParam('price');
        
           

          
           $standardPercentage = $this->getRequest()->getParam('standardPercentage');
           $expeditedPercentage = $this->getRequest()->getParam('expeditedPercentage');


          // preg_match_all('!\d+!', $price, $matches);

           $finalPrice = preg_replace('/[^\\d.]+/', '', $price);
           $finalPrice = number_format((float)$finalPrice, 2, '.', ''); 
           // $logger->info($finalPrice);

           // $finalPrice =  (int)$finalPrice;

           //print_r($matches);//exit;

           // $finalPrice = implode('.',$matches[0]);

           //echo $finalPrice;//exit;

           //echo preg_replace("/[^0-9]/", '', $price);exit;

           //if ($ptype == "stand") {
    
              
              $standardFinalPrice = $finalPrice - (($finalPrice/100)*$standardPercentage);

              
             // $logger->info($standardPercentage);
           //} else {
          $expeditedFinalPrice = $finalPrice - (($finalPrice/100)*$expeditedPercentage);

          $expedited = number_format((float)$expeditedFinalPrice, 2, '.', ''); 
            $standard = number_format((float)$standardFinalPrice, 2, '.', ''); 
          $discountPrices['expedited'] = number_format($expedited,2); 
          $discountPrices['standard'] =  number_format($standard,2);

          // $logger->info($ex);
          // $logger->info($std);

           //}

              // echo $standardFinalPrice."<br>";
              // echo $expeditedFinalPrice."<br>";
          // $discountPrices['standard']  = round($standardFinalPrice, 2);
          // $discountPrices['expedited'] = round($expeditedFinalPrice, 2);
          // $discountPrices['standard']  = $standardFinalPrice;
          // $discountPrices['expedited'] =  $expeditedFinalPrice;
         //$discountPrices['standard']  = number_format(($standardFinalPrice, 2, '.', ''));
          //$discountPrices['expedited'] =  number_format(($expeditedFinalPrice, 2, '.', ''));

          echo json_encode($discountPrices);

         //echo(round($res,2));
         exit;

    }
    /*public function getCustomerGroupId(){
                  $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
          $customerGroupId = $customerSession->getCustomer()->getGroupId();
          $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
               $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
               $customerid = $connection->fetchAll("SELECT discount_percentage,standard_discount_percentage FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1");
                return $customerid;

            }*/

}
