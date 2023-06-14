<?php
namespace Dcw\ShippingMethod\Model\Fedex;

use Magento\Quote\Model\Quote\Address\RateRequest;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Module\Dir;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\Soap\ClientFactory;
use Magento\Framework\Xml\Security;
use Magento\Shipping\Model\Rate\Result;

class Carrier extends \Magento\Fedex\Model\Carrier
{

    /**
     * Code of the carrier
     *
     * @var string
     */
    const CODE = 'fedex';

    /**
     * Purpose of rate request
     *
     * @var string
     */
    const RATE_REQUEST_GENERAL = 'general';

    /**
     * Purpose of rate request
     *
     * @var string
     */
    const RATE_REQUEST_SMARTPOST = 'SMART_POST';

    /**
     * Code of the carrier
     *
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * Types of rates, order is important
     *
     * @var array
     */
    protected $_ratesOrder = [
        'RATED_ACCOUNT_PACKAGE',
        'PAYOR_ACCOUNT_PACKAGE',
        'RATED_ACCOUNT_SHIPMENT',
        'PAYOR_ACCOUNT_SHIPMENT',
        'RATED_LIST_PACKAGE',
        'PAYOR_LIST_PACKAGE',
        'RATED_LIST_SHIPMENT',
        'PAYOR_LIST_SHIPMENT',
    ];

    /**
     * Rate request data
     *
     * @var RateRequest|null
     */
    protected $_request = null;

    protected $_cart;
    protected $_productloader;
    protected $objectmanager;
    /**
     * Rate result data
     *
     * @var Result|null
     */
    protected $_result = null;

    /**
     * Path to wsdl file of rate service
     *
     * @var string
     */
    protected $_rateServiceWsdl;

    /**
     * Path to wsdl file of ship service
     *
     * @var string
     */
    protected $_shipServiceWsdl = null;

    /**
     * Path to wsdl file of track service
     *
     * @var string
     */
    protected $_trackServiceWsdl = null;

    /**
     * Container types that could be customized for FedEx carrier
     *
     * @var string[]
     */
    protected $_customizableContainerTypes = ['YOUR_PACKAGING'];

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @inheritdoc
     */
    protected $_debugReplacePrivateDataKeys = [
        'Key', 'Password', 'MeterNumber',
    ];

    /**
     * Version of tracking service
     * @var int
     */
    private static $trackServiceVersion = 10;

    /**
     * List of TrackReply errors
     * @var array
     */
    private static $trackingErrors = ['FAILURE', 'ERROR'];

    /**
     * @var Json
     */
    private $serializer;



    /**
     * @var ClientFactory
     */
    private $soapClientFactory;


    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        Security $xmlSecurity,
        \Magento\Shipping\Model\Simplexml\ElementFactory $xmlElFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory,
        \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Dir\Reader $configReader,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = [],
        Json $serializer = null,
        ClientFactory $soapClientFactory = null,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Framework\ObjectManagerInterface $objectmanager

    ) {
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $storeManager,
            $configReader,
            $productCollectionFactory,
            $data,
            $serializer,
            $soapClientFactory
        );
        $this->_cart = $cart;
        $this->_productloader = $_productloader;
        $this->_objectManager = $objectmanager;
    }

    protected function _formRateRequest($purpose)
    {
      $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/fedex18.log');
         $logger = new \Zend\Log\Logger();
         $logger->addWriter($writer);

      $r = $this->_rawRequest;
      $length = $width = $height = $weight = 0;


      //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $state =  $this->_objectManager->get('Magento\Framework\App\State');
      $stateArea = $state->getAreaCode(); //frontend or adminhtml or webapi_rest

      // $requestRouter = $this->_objectManager->get('Magento\Framework\App\Request\Http');
      //
      // $moduleName = $requestRouter->getModuleName();
      // $controller = $requestRouter->getControllerName();
      // $action     = $requestRouter->getActionName();
      //
      // $completePath = $moduleName.'_'.$controller.'_'.$action;
      // $storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
      //
      // $currentUrl = $storeManager->getStore()->getCurrentUrl(false);
      // $currentURl2 = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
      $currentRequestUrL = $_SERVER['REQUEST_URI'];

      // printLog("completePath Fedex");
      // printLog($completePath);
      // printLog("current url");
      // printLog($currentUrl);
      // printLog($_SERVER['REQUEST_URI']);

      //$completePath = 'quotation_quote_index';

      //exit;
      //if($stateArea != 'adminhtml'&& (strpos($currentRequestUrL, 'quotation') !== false))
      if(false)
      {
        //printLog("inside if loop2");
        $session = $this->_objectManager->get('\Magento\Checkout\Model\Session');
        $quote_repository = $this->_objectManager->get('\Magento\Quote\Api\CartRepositoryInterface');
        $qid = $session->getQuoteId();
        $quote = $quote_repository->get($qid);
         $items = $quote->getAllItems();

         //$items = $this->_cart->getQuote()->getAllItems();

        foreach($items as $item) {
            $id = $item->getProduct()->getId();

            $quantity = $item->getQty();
            $product = $this->_productloader->create()->load($id);
            $length += ($product->getData('length') * $quantity) ;
            $width += ($product->getData('width') * $quantity) ;
            $height += ($product->getData('height') * $quantity) ;
            $weight += ($product->getWeight() * $quantity) ;
        }

        $dimensionalWeight = $length + $width+ $height;

        if($dimensionalWeight>$weight)
            $finalWeight = $dimensionalWeight;
        else
            $finalWeight = $weight;

      }
      else{
          $finalWeight = $r->getWeight();
      }

      $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

      $satDelivery = $this->_scopeConfig->getValue('carriers/fedex/saturday_delivery', $storeScope);
      $logger->info($satDelivery);

      // $date = new \DateTime(date('c'));
      // $date->modify('tomorrow');
      // $satDelivery = $date->format('c');

      if($satDelivery==1){
        $logger->info("in if loop");
        $ratesRequest = [
              'WebAuthenticationDetail' => [
                  'UserCredential' => ['Key' => $r->getKey(), 'Password' => $r->getPassword()],
              ],
              'ClientDetail' => ['AccountNumber' => $r->getAccount(), 'MeterNumber' => $r->getMeterNumber()],
              'Version' => $this->getVersionInfo(),
              'VariableOptions' =>'SATURDAY_DELIVERY',
              'ReturnTransitAndCommit'=>true,
              'RequestedShipment' => [
                  'DropoffType' => $r->getDropoffType(),
                  'ShipTimestamp' => date('c'),
                  //'ShipTimestamp' => $satDelivery,
                  'PackagingType' => $r->getPackaging(),
                  'TotalInsuredValue' => ['Amount' => $r->getValue(), 'Currency' => $this->getCurrencyCode()],
                  'Shipper' => [
                      'Address' => ['PostalCode' => $r->getOrigPostal(), 'CountryCode' => $r->getOrigCountry()],
                  ],
                  'Recipient' => [
                      'Address' => [
                          'PostalCode' => $r->getDestPostal(),
                          'CountryCode' => $r->getDestCountry(),
                          'Residential' => (bool)$this->getConfigData('residence_delivery'),
                      ],
                  ],
                  'ShippingChargesPayment' => [
                      'PaymentType' => 'SENDER',
                      'Payor' => ['AccountNumber' => $r->getAccount(), 'CountryCode' => $r->getOrigCountry()],
                  ],
                  'CustomsClearanceDetail' => [
                      'CustomsValue' => ['Amount' => $r->getValue(), 'Currency' => $this->getCurrencyCode()],
                  ],
                  'RateRequestTypes' => 'LIST',
                  'PackageCount' => '1',
                  'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                  'RequestedPackageLineItems' => [
                      '0' => [
                          'Weight' => [
                              'Value' => (double)$finalWeight,
                              'Units' => $this->getConfigData('unit_of_measure'),
                          ],
                          'GroupPackageCount' => 1,
                      ],
                  ],
              ],
          ];
      }
      else{
        $logger->info("in if else loop");
        $ratesRequest = [
              'WebAuthenticationDetail' => [
                  'UserCredential' => ['Key' => $r->getKey(), 'Password' => $r->getPassword()],
              ],
              'ClientDetail' => ['AccountNumber' => $r->getAccount(), 'MeterNumber' => $r->getMeterNumber()],
              'Version' => $this->getVersionInfo(),
              'ReturnTransitAndCommit'=>true,
              'RequestedShipment' => [
                  'DropoffType' => $r->getDropoffType(),
                  'ShipTimestamp' => date('c'),
                  //'ShipTimestamp' => $satDelivery,
                  'PackagingType' => $r->getPackaging(),
                  'TotalInsuredValue' => ['Amount' => $r->getValue(), 'Currency' => $this->getCurrencyCode()],
                  'Shipper' => [
                      'Address' => ['PostalCode' => $r->getOrigPostal(), 'CountryCode' => $r->getOrigCountry()],
                  ],
                  'Recipient' => [
                      'Address' => [
                          'PostalCode' => $r->getDestPostal(),
                          'CountryCode' => $r->getDestCountry(),
                          'Residential' => (bool)$this->getConfigData('residence_delivery'),
                      ],
                  ],
                  'ShippingChargesPayment' => [
                      'PaymentType' => 'SENDER',
                      'Payor' => ['AccountNumber' => $r->getAccount(), 'CountryCode' => $r->getOrigCountry()],
                  ],
                  'CustomsClearanceDetail' => [
                      'CustomsValue' => ['Amount' => $r->getValue(), 'Currency' => $this->getCurrencyCode()],
                  ],
                  'RateRequestTypes' => 'LIST',
                  'PackageCount' => '1',
                  'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                  'RequestedPackageLineItems' => [
                      '0' => [
                          'Weight' => [
                              'Value' => (double)$finalWeight,
                              'Units' => $this->getConfigData('unit_of_measure'),
                          ],
                          'GroupPackageCount' => 1,
                      ],
                  ],
              ],
          ];

      }

      $logger->info("RatesRequest");
      $logger->info($ratesRequest);

        if ($r->getDestCity()) {
            $ratesRequest['RequestedShipment']['Recipient']['Address']['City'] = $r->getDestCity();
        }

        if ($purpose == self::RATE_REQUEST_GENERAL) {
            $ratesRequest['RequestedShipment']['RequestedPackageLineItems'][0]['InsuredValue'] = [
                'Amount' => $r->getValue(),
                'Currency' => $this->getCurrencyCode(),
            ];
        } else {
            if ($purpose == self::RATE_REQUEST_SMARTPOST) {
                $ratesRequest['RequestedShipment']['ServiceType'] = self::RATE_REQUEST_SMARTPOST;
                $ratesRequest['RequestedShipment']['SmartPostDetail'] = [
                    'Indicia' => (double)$r->getWeight() >= 1 ? 'PARCEL_SELECT' : 'PRESORTED_STANDARD',
                    'HubId' => $this->getConfigData('smartpost_hubid'),
                ];
            }
        }

        return $ratesRequest;
    }
    protected function _prepareRateResponse($response)
   {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/fedex18.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        //$logger->info("response");
        //$logger->info(json_encode((array)$response));

        $deliveryDates = array();

    //     $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    //
    // $satDelivery = $this->_scopeConfig->getValue('carriers/fedex/saturday_delivery', $storeScope);

       $costArr = [];
       $priceArr = [];
       $errorTitle = 'For some reason we can\'t retrieve tracking info right now.';

       if (is_object($response)) {
           if ($response->HighestSeverity == 'FAILURE' || $response->HighestSeverity == 'ERROR') {
               if (is_array($response->Notifications)) {
                   $notification = array_pop($response->Notifications);
                   $errorTitle = (string)$notification->Message;
               } else {
                   $errorTitle = (string)$response->Notifications->Message;
               }
           } elseif (isset($response->RateReplyDetails)) {
               $allowedMethods = explode(",", $this->getConfigData('allowed_methods'));

               if (is_array($response->RateReplyDetails)) {
                   $x = 0;
                   foreach ($response->RateReplyDetails as $rate) {
                     $serviceName = (string)$rate->ServiceType;
                     if(isset($rate->DeliveryTimestamp))
                          $deliveryTime = (string)$rate->DeliveryTimestamp;
                     else
                          $deliveryTime ='';

                    $deliveryDates[$serviceName]=$deliveryTime;

                    //
                    // $logger->info("serviceName");
                    // $logger->info($serviceName);
                    // $logger->info("deliveryTime");
                    // $logger->info($deliveryTime);

                       if(isset($rate->AppliedOptions))
                       {
                         $appliedOptions = (string)$rate->AppliedOptions;
                         if($appliedOptions == 'SATURDAY_DELIVERY')
                         {
                           //$logger->info("saturday delivery");
                           $serviceName = (string)$rate->ServiceType."_SAT";
                           array_push($allowedMethods,$serviceName);
                           $x=1;
                         }
                       }
                       // $logger->info("x value");
                       // $logger->info($x);

                       // if($x!=1)
                       //   $serviceName = (string)$rate->ServiceType;


                         //$logger->info($x);
                         // $logger->info("allowed methods");
                         // $logger->info($allowedMethods);

                       if (in_array($serviceName, $allowedMethods)) {
                           $amount = $this->_getRateAmountOriginBased($rate);
                           $logger->info("serviceName");
                           $logger->info($serviceName);
                           $logger->info($amount);
                           $costArr[$serviceName] = $amount;
                           $priceArr[$serviceName] = $this->getMethodPrice($amount, $serviceName);
                       }
                   }
                   asort($priceArr);
               } else {
                   $rate = $response->RateReplyDetails;
                   $serviceName = (string)$rate->ServiceType;
                   if (in_array($serviceName, $allowedMethods)) {
                       $amount = $this->_getRateAmountOriginBased($rate);
                       $costArr[$serviceName] = $amount;
                       $priceArr[$serviceName] = $this->getMethodPrice($amount, $serviceName);
                   }
               }
           }
       }

       $result = $this->_rateFactory->create();
       $logger->info($priceArr);
       $logger->info($deliveryDates);

//        array (
//   'GROUND_HOME_DELIVERY' => 12.92,
//   'FEDEX_EXPRESS_SAVER' => 17.9,
//   'FEDEX_2_DAY' => 19.58,
//   'FEDEX_2_DAY_AM' => 20.21,
//   'STANDARD_OVERNIGHT' => 22.32,
//   'PRIORITY_OVERNIGHT' => 23.24,
//   'FIRST_OVERNIGHT' => 71.57,
// )

       if (empty($priceArr)) {
           $error = $this->_rateErrorFactory->create();
           $error->setCarrier($this->_code);
           $error->setCarrierTitle($this->getConfigData('title'));
           $error->setErrorMessage($errorTitle);
           $error->setErrorMessage($this->getConfigData('specificerrmsg'));
           $result->append($error);
       } else {
         $createdDate = $this->_objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
         $currentDateTime = $createdDate->date()->format('Y-m-d H:i:s');
         $logger->info("currentdatetime:");
         $logger->info($currentDateTime);
         $timestamp = strtotime($currentDateTime);

         $day = date('D', $timestamp);
         $logger->info($day);
         if($day=="Thu")
         {
             $logger->info("in thursday condition");
             unset($priceArr['FEDEX_2_DAY_FREIGHT_SAT']);
             unset($priceArr['PRIORITY_OVERNIGHT_SAT']);
             unset($priceArr['FIRST_OVERNIGHT_SAT']);
             unset($priceArr['INTERNATIONAL_PRIORITY_SAT']);
         }
         else if($day=="Fri"){
           $logger->info("in friday condition");
           unset($priceArr['INTERNATIONAL_PRIORITY_SAT']);
           unset($priceArr['FEDEX_2_DAY_FREIGHT_SAT']);
           unset($priceArr['FEDEX_2_DAY_SAT']);
           unset($priceArr['FIRST_OVERNIGHT_SAT']);
         }
           foreach ($priceArr as $method => $price) {
               $rate = $this->_rateMethodFactory->create();
               //printLog(get_class($rate));
               $rate->setCarrier($this->_code);
               $rate->setCarrierTitle($this->getConfigData('title'));
               $rate->setMethod($method);

               if(isset($deliveryDates[$method]))
               {
                 $deliveryDate = $deliveryDates[$method];
                 if($deliveryDate)
                 {
                   $dateValue = date('Y-m-d h:i:s', strtotime($deliveryDate));
                   $time=strtotime($dateValue);
                   $day=date("d",$time);
                   $month=date("F",$time);
                   $year=date("Y",$time);
                   $dTArry = explode('T',$deliveryDate);
                   $finaldT = explode(':',$dTArry[1]);
                   if($finaldT[0]>12)
                   {
                     $finalTime = $finaldT[0]-12;
                     $finalDateTime = $finalTime.":".$finaldT[1]." P.M.";
                   }
                   else{
                     $finalTime = $finaldT[0];
                     $finalFormattedTime = ltrim($finalTime, '0');
                     $finalDateTime = $finalFormattedTime.":".$finaldT[1]." A.M.";

                   }
                   $modifiedDay = $this->ordinal($day);
                   $modifiedDay = ltrim($modifiedDay,"0");
                   $deliveryMessage = "Estimated delivery by $modifiedDay $month"." ".$year." ".$finalDateTime;
                 }
                 else{
                   $deliveryMessage = '';
                 }
               }

               //printLog($deliveryMessage);
               $logger->info(get_class($rate));
               $logger->info("deliveryDate");
               $logger->info($deliveryDate);
               $logger->info("deliveryMessage");
               $logger->info($deliveryMessage);

               $rate->setDescription($deliveryMessage);
               //$rate->setDescription("TEST");
               //$rate->setShortdescription('Fast Domestic Shipping');
               $methodArr = $this->getShipmentByCode($method);

               $satShipping=0;
               switch($method)
               {
                 case 'FEDEX_2_DAY_SAT' : $satShipping=1;$methodTitle = "Saturday 2 Day"; break;
                 case 'FEDEX_2_DAY_FREIGHT_SAT' : $satShipping=1;$methodTitle = "Saturday 2 Day Freight"; break;
                 case 'PRIORITY_OVERNIGHT_SAT' : $satShipping=1;$methodTitle = "Saturday Priority Overnight"; break;
                 case 'FIRST_OVERNIGHT_SAT' : $satShipping=1;$methodTitle = "Saturday First Overnight"; break;
                 case 'INTERNATIONAL_PRIORITY_SAT' : $satShipping=1;$methodTitle = "Saturday International Priority"; break;
               }

               if($satShipping==1)
                   $rate->setMethodTitle($methodTitle);
               elseif($method == 'FEDEX_FIRST_FREIGHT')
                   $rate->setMethodTitle("First Freight");
               else
                   $rate->setMethodTitle($this->getCode('method', $method));
               //$rate->setMethodTitle($this->getCode('method', $method));
               $rate->setCost($costArr[$method]);
               $rate->setPrice($price);
               $result->append($rate);
           }
       }

       return $result;
   }
   function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
    }

}
