<?php
namespace Dcw\CustomPricing\Controller\Price;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Variable\Model\VariableFactory;
use Psr\Log\LoggerInterface;
use Dcw\CustomPricing\Model\CustomPricingFactory;


class Productprice extends \Magento\Framework\App\Action\Action
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
      $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/custp.log');
           $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);
           //$customerGroupId = $this->getCustomerGroupId();
          /* $expedicted = $customerGroupId[0]['discount_percentage'];
           $standard = $customerGroupId[0]['standard_discount_percentage'];*/
           $price = $this->getRequest()->getParam('price');
           $ptype = $this->getRequest()->getParam('ptype');
           $standard = $this->getRequest()->getParam('standard');
           $expedicted = $this->getRequest()->getParam('expedicted');
           $pid = $this->getRequest()->getParam('pid');
           $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($pid);
$productPriceById = $product->getId();
           $logger->info($price);
           $logger->info($ptype);
           $logger->info($standard);
           $logger->info($expedicted);
           $logger->info($productPriceById);

          preg_match_all('!\d+!', $price, $matches);

           //print_r($matches);//exit;

           $finalPrice = implode('.',$matches[0]);

          // echo $finalPrice;exit;

           //echo preg_replace("/[^0-9]/", '', $price);exit;

           if ($ptype == "stand") {
              $res = $finalPrice - (($finalPrice/100)*$standard);
           } else {
                $res = $finalPrice - (($finalPrice/100)*$expedicted);
           }


         echo(round($res,2));exit;

    }
    // public function getCustomerGroupId(){
    //               $customerSession =  $this->_objectManager->create('Magento\Customer\Model\Session');
    //       $customerGroupId = $customerSession->getCustomer()->getGroupId();
    //       $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
    //            $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
    //            $customerid = $connection->fetchAll("SELECT discount_percentage,standard_discount_percentage,category,brand FROM dcw_custom_price_rules WHERE customer_group = '".$customerGroupId."' and status = 1");
    //             return $customerid;
    //
    //         }

}
