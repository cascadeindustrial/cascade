<?php

namespace Dcw\ShippingMethod\Model;


use Magento\Framework\App\ObjectManager;
use Magento\Framework\Async\CallbackDeferred;
use Magento\Framework\HTTP\AsyncClient\Request;
use Magento\Framework\HTTP\AsyncClientInterface;
use Magento\Framework\Xml\Security;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Helper\Carrier as CarrierHelper;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\Result\ProxyDeferredFactory;
use Magento\Usps\Helper\Data as DataHelper;

class Carrier extends \Magento\Usps\Model\Carrier
{

    /** @deprecated */
    const CONTAINER_VARIABLE = 'VARIABLE';

    /** @deprecated */
    const CONTAINER_FLAT_RATE_BOX = 'FLAT RATE BOX';

    /** @deprecated */
    const CONTAINER_FLAT_RATE_ENVELOPE = 'FLAT RATE ENVELOPE';

    /** @deprecated */
    const CONTAINER_RECTANGULAR = 'RECTANGULAR';

    /** @deprecated */
    const CONTAINER_NONRECTANGULAR = 'NONRECTANGULAR';

    /**
     * USPS size
     */
    const SIZE_REGULAR = 'REGULAR';

    const SIZE_LARGE = 'LARGE';

    /**
     * Default api revision
     *
     * @var int
     */
    const DEFAULT_REVISION = 2;

    /**
     * Code of the carrier
     *
     * @var string
     */
    const CODE = 'usps';

    /**
     * Ounces in one pound for conversion
     */
    const OUNCES_POUND = 16;

    /**
     * Code of the carrier
     *
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * Weight precision
     *
     * @var int
     */
    private static $weightPrecision = 10;

    /**
     * Rate request data
     *
     * @var \Magento\Quote\Model\Quote\Address\RateRequest|null
     */
    protected $_request = null;

    /**
     * Rate result data
     *
     * @var Result|null
     */
    protected $_result = null;

    /**
     * Default cgi gateway url
     *
     * @var string
     */
    protected $_defaultGatewayUrl = 'http://production.shippingapis.com/ShippingAPI.dll';

    /**
     * Container types that could be customized for USPS carrier
     *
     * @var string[]
     */
    protected $_customizableContainerTypes = ['VARIABLE', 'RECTANGULAR', 'NONRECTANGULAR'];

    /**
     * Carrier helper
     *
     * @var \Magento\Shipping\Helper\Carrier
     */
    protected $_carrierHelper;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     * @deprecated Use asynchronous client.
     * @see $httpClient
     */
    protected $_httpClientFactory;

    protected $_finalWeight;

    /**
     * @inheritdoc
     */
    protected $_debugReplacePrivateDataKeys = [
        'USERID'
    ];

    /**
     * @var DataHelper
     */
    private $dataHelper;

    /**
     * @var AsyncClientInterface
     */
    private $httpClient;

    /**
     * @var ProxyDeferredFactory
     */
    private $proxyDeferredFactory;


    protected $_cart;
    protected $_productloader;
    protected $objectmanager;
    protected $_adminVar;

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
        CarrierHelper $carrierHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory,
        array $data = [],
        ?AsyncClientInterface $httpClient = null,
        ?ProxyDeferredFactory $proxyDeferredFactory = null,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Variable\Model\VariableFactory $_adminVar,
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
            $carrierHelper,
            $productCollectionFactory,
            $httpClientFactory,
            $data,
            $httpClient,
            $proxyDeferredFactory
        );
        $this->_cart = $cart;
        $this->_productloader = $_productloader;
        $this->_adminVar = $_adminVar;
        $this->_objectManager = $objectmanager;
        $this->httpClient = $httpClient ?? ObjectManager::getInstance()->get(AsyncClientInterface::class);
        $this->proxyDeferredFactory = $proxyDeferredFactory
            ?? ObjectManager::getInstance()->get(ProxyDeferredFactory::class);
    }

    public function collectRates(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {

      $state =  $this->_objectManager->get('Magento\Framework\App\State');
      $stateArea = $state->getAreaCode(); //frontend or adminhtml or webapi_rest

      // $requestRouter = $this->_objectManager->get('Magento\Framework\App\Request\Http');
      //
      // $moduleName = $requestRouter->getModuleName();
      // $controller = $requestRouter->getControllerName();
      // $action     = $requestRouter->getActionName();
      //
      // $completePath = $moduleName.'_'.$controller.'_'.$action;
      // printLog("completePath");
      // printLog($completePath);
      $currentRequestUrL = $_SERVER['REQUEST_URI'];

      //exit;
      //if($stateArea != 'adminhtml' && $completePath!='quotation_quote_index')
      //if($stateArea != 'adminhtml' && (strpos($currentRequestUrL, 'quotation') !== false))
      if(false)
      {
        //printLog("in if loop");
        $variable = $this->_adminVar->create();
        $threshholdValue = $variable->loadByCode('threshhold_weight')->getPlainValue();

        $length = $width = $height = $weight = 0;

        $session = $this->_objectManager->get('\Magento\Checkout\Model\Session');
          $quote_repository = $this->_objectManager->get('\Magento\Quote\Api\CartRepositoryInterface');
          $qid = $session->getQuoteId();
          $quote = $quote_repository->get($qid);
          $items = $quote->getAllItems();

        //$items = $this->_cart->getQuote()->getAllItems();

        foreach($items as $item) {
            $id = $item->getProductId();
            $quantity = $item->getQty();
            $product = $this->_productloader->create()->load($id);
            $isUspsShippingEnabled = $product->getData('usps_shipping_method');
            if(!$isUspsShippingEnabled)
                return true;

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

          if($finalWeight > $threshholdValue)
                  return true;

       $this->finalWeight = $finalWeight;

      }


      if (!$this->canCollectRates()) {
        return $this->getErrorMessage();
      }


        $this->setRequest($request);
        //Saving current result to use the right one in the callback.
        $this->_result = $result = $this->_getQuotes();
        return $this->proxyDeferredFactory->create(
            [
                'deferred' => new CallbackDeferred(
                    function () use ($request, $result) {
                        $this->_result = $result;
                        $this->_updateFreeMethodQuote($request);

                      return $this->getResult();
                    }
                )
            ]
        );
    }

    public function setRequest(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
            $this->_request = $request;

            $r = new \Magento\Framework\DataObject();

            if ($request->getLimitMethod()) {
                $r->setService($request->getLimitMethod());
            } else {
                $r->setService('ALL');
            }

            if ($request->getUspsUserid()) {
                $userId = $request->getUspsUserid();
            } else {
                $userId = $this->getConfigData('userid');
            }
            $r->setUserId($userId);

            if ($request->getUspsContainer()) {
                $container = $request->getUspsContainer();
            } else {
                $container = $this->getConfigData('container');
            }
            $r->setContainer($container);

            if ($request->getUspsSize()) {
                $size = $request->getUspsSize();
            } else {
                $size = $this->getConfigData('size');
            }
            $r->setSize($size);

            if ($request->getGirth()) {
                $girth = $request->getGirth();
            } else {
                $girth = $this->getConfigData('girth');
            }
            $r->setGirth($girth);

            if ($request->getHeight()) {
                $height = $request->getHeight();
            } else {
                $height = $this->getConfigData('height');
            }
            $r->setHeight($height);

            if ($request->getLength()) {
                $length = $request->getLength();
            } else {
                $length = $this->getConfigData('length');
            }
            $r->setLength($length);

            if ($request->getWidth()) {
                $width = $request->getWidth();
            } else {
                $width = $this->getConfigData('width');
            }
            $r->setWidth($width);

            if ($request->getUspsMachinable()) {
                $machinable = $request->getUspsMachinable();
            } else {
                $machinable = $this->getConfigData('machinable');
            }
            $r->setMachinable($machinable);

            if ($request->getOrigPostcode()) {
                $r->setOrigPostal($request->getOrigPostcode());
            } else {
                $r->setOrigPostal(
                    $this->_scopeConfig->getValue(
                        \Magento\Sales\Model\Order\Shipment::XML_PATH_STORE_ZIP,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                        $request->getStoreId()
                    )
                );
            }

            if ($request->getOrigCountryId()) {
                $r->setOrigCountryId($request->getOrigCountryId());
            } else {
                $r->setOrigCountryId(
                    $this->_scopeConfig->getValue(
                        \Magento\Sales\Model\Order\Shipment::XML_PATH_STORE_COUNTRY_ID,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                        $request->getStoreId()
                    )
                );
            }

            if ($request->getDestCountryId()) {
                $destCountry = $request->getDestCountryId();
            } else {
                $destCountry = self::USA_COUNTRY_ID;
            }

            $r->setDestCountryId($destCountry);

            if (!$this->_isUSCountry($destCountry)) {
                $r->setDestCountryName($this->_getCountryName($destCountry));
            }

            if ($request->getDestPostcode()) {
                $r->setDestPostal($request->getDestPostcode());
            }

            $weight = $this->getTotalNumOfBoxes($request->getPackageWeight());

            /* custom logic for weight */
                //$weight = $this->finalWeight;
            /* custom logic for weight */

            $r->setWeightPounds(floor($weight));
            $ounces = ($weight - floor($weight)) * self::OUNCES_POUND;
            $r->setWeightOunces(sprintf('%.' . self::$weightPrecision . 'f', $ounces));
            if ($request->getFreeMethodWeight() != $request->getPackageWeight()) {
                $r->setFreeMethodWeight($request->getFreeMethodWeight());
            }

            $r->setValue($request->getPackageValue());
            $r->setValueWithDiscount($request->getPackageValueWithDiscount());

            $r->setBaseSubtotalInclTax($request->getBaseSubtotalInclTax());

            $this->setRawRequest($r);

            return $this;
  }

}
