<?php
namespace MageMaclean\MyShipping\Model\Carrier;

/**
 * Interceptor class for @see \MageMaclean\MyShipping\Model\Carrier
 */
class Interceptor extends \MageMaclean\MyShipping\Model\Carrier implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Psr\Log\LoggerInterface $logger, \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory, \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory, \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory, \Magento\Framework\App\State $state, \Magento\Customer\Model\Session $customerSession, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Backend\Model\Session\Quote $quoteSession, \MageMaclean\MyShipping\Helper\Data $helper, \MageMaclean\MyShipping\Model\Repository\AccountRepository $accountRepository, \MageMaclean\MyShipping\Model\Repository\CourierListRepository $courierListRepository, \MageMaclean\MyShipping\Model\Myshipping\ResultFactory $resultFactory, \Magento\Quote\Api\Data\ShippingMethodExtensionFactory $shippingMethodExtensionFactory, array $data = [])
    {
        $this->___init();
        parent::__construct($scopeConfig, $logger, $rateResultFactory, $rateMethodFactory, $rateErrorFactory, $state, $customerSession, $checkoutSession, $quoteSession, $helper, $accountRepository, $courierListRepository, $resultFactory, $shippingMethodExtensionFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedMethods()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllowedMethods');
        if (!$pluginInfo) {
            return parent::getAllowedMethods();
        } else {
            return $this->___callPlugins('getAllowedMethods', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuote()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQuote');
        if (!$pluginInfo) {
            return parent::getQuote();
        } else {
            return $this->___callPlugins('getQuote', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isEnabled');
        if (!$pluginInfo) {
            return parent::isEnabled();
        } else {
            return $this->___callPlugins('isEnabled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isNewEnabled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isNewEnabled');
        if (!$pluginInfo) {
            return parent::isNewEnabled();
        } else {
            return $this->___callPlugins('isNewEnabled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreId');
        if (!$pluginInfo) {
            return parent::getStoreId();
        } else {
            return $this->___callPlugins('getStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerId');
        if (!$pluginInfo) {
            return parent::getCustomerId();
        } else {
            return $this->___callPlugins('getCustomerId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAccounts()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAccounts');
        if (!$pluginInfo) {
            return parent::getAccounts();
        } else {
            return $this->___callPlugins('getAccounts', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectRates(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'collectRates');
        if (!$pluginInfo) {
            return parent::collectRates($request);
        } else {
            return $this->___callPlugins('collectRates', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigData($field)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfigData');
        if (!$pluginInfo) {
            return parent::getConfigData($field);
        } else {
            return $this->___callPlugins('getConfigData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigFlag($field)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfigFlag');
        if (!$pluginInfo) {
            return parent::getConfigFlag($field);
        } else {
            return $this->___callPlugins('getConfigFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function requestToShipment($request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'requestToShipment');
        if (!$pluginInfo) {
            return parent::requestToShipment($request);
        } else {
            return $this->___callPlugins('requestToShipment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function returnOfShipment($request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'returnOfShipment');
        if (!$pluginInfo) {
            return parent::returnOfShipment($request);
        } else {
            return $this->___callPlugins('returnOfShipment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerTypes(?\Magento\Framework\DataObject $params = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getContainerTypes');
        if (!$pluginInfo) {
            return parent::getContainerTypes($params);
        } else {
            return $this->___callPlugins('getContainerTypes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomizableContainerTypes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomizableContainerTypes');
        if (!$pluginInfo) {
            return parent::getCustomizableContainerTypes();
        } else {
            return $this->___callPlugins('getCustomizableContainerTypes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveryConfirmationTypes(?\Magento\Framework\DataObject $params = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDeliveryConfirmationTypes');
        if (!$pluginInfo) {
            return parent::getDeliveryConfirmationTypes($params);
        } else {
            return $this->___callPlugins('getDeliveryConfirmationTypes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkAvailableShipCountries(\Magento\Framework\DataObject $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'checkAvailableShipCountries');
        if (!$pluginInfo) {
            return parent::checkAvailableShipCountries($request);
        } else {
            return $this->___callPlugins('checkAvailableShipCountries', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function processAdditionalValidation(\Magento\Framework\DataObject $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'processAdditionalValidation');
        if (!$pluginInfo) {
            return parent::processAdditionalValidation($request);
        } else {
            return $this->___callPlugins('processAdditionalValidation', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function proccessAdditionalValidation(\Magento\Framework\DataObject $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'proccessAdditionalValidation');
        if (!$pluginInfo) {
            return parent::proccessAdditionalValidation($request);
        } else {
            return $this->___callPlugins('proccessAdditionalValidation', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isActive');
        if (!$pluginInfo) {
            return parent::isActive();
        } else {
            return $this->___callPlugins('isActive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isFixed()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isFixed');
        if (!$pluginInfo) {
            return parent::isFixed();
        } else {
            return $this->___callPlugins('isFixed', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isTrackingAvailable()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isTrackingAvailable');
        if (!$pluginInfo) {
            return parent::isTrackingAvailable();
        } else {
            return $this->___callPlugins('isTrackingAvailable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isShippingLabelsAvailable()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isShippingLabelsAvailable');
        if (!$pluginInfo) {
            return parent::isShippingLabelsAvailable();
        } else {
            return $this->___callPlugins('isShippingLabelsAvailable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSortOrder');
        if (!$pluginInfo) {
            return parent::getSortOrder();
        } else {
            return $this->___callPlugins('getSortOrder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFinalPriceWithHandlingFee($cost)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFinalPriceWithHandlingFee');
        if (!$pluginInfo) {
            return parent::getFinalPriceWithHandlingFee($cost);
        } else {
            return $this->___callPlugins('getFinalPriceWithHandlingFee', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalNumOfBoxes($weight)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalNumOfBoxes');
        if (!$pluginInfo) {
            return parent::getTotalNumOfBoxes($weight);
        } else {
            return $this->___callPlugins('getTotalNumOfBoxes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isStateProvinceRequired()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isStateProvinceRequired');
        if (!$pluginInfo) {
            return parent::isStateProvinceRequired();
        } else {
            return $this->___callPlugins('isStateProvinceRequired', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isCityRequired()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isCityRequired');
        if (!$pluginInfo) {
            return parent::isCityRequired();
        } else {
            return $this->___callPlugins('isCityRequired', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isZipCodeRequired($countryId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isZipCodeRequired');
        if (!$pluginInfo) {
            return parent::isZipCodeRequired($countryId);
        } else {
            return $this->___callPlugins('isZipCodeRequired', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDebugFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDebugFlag');
        if (!$pluginInfo) {
            return parent::getDebugFlag();
        } else {
            return $this->___callPlugins('getDebugFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debugData($debugData)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'debugData');
        if (!$pluginInfo) {
            return parent::debugData($debugData);
        } else {
            return $this->___callPlugins('debugData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCarrierCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCarrierCode');
        if (!$pluginInfo) {
            return parent::getCarrierCode();
        } else {
            return $this->___callPlugins('getCarrierCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypes(\Magento\Framework\DataObject $params)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getContentTypes');
        if (!$pluginInfo) {
            return parent::getContentTypes($params);
        } else {
            return $this->___callPlugins('getContentTypes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addData(array $arr)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addData');
        if (!$pluginInfo) {
            return parent::addData($arr);
        } else {
            return $this->___callPlugins('addData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setData($key, $value = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setData');
        if (!$pluginInfo) {
            return parent::setData($key, $value);
        } else {
            return $this->___callPlugins('setData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetData($key = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetData');
        if (!$pluginInfo) {
            return parent::unsetData($key);
        } else {
            return $this->___callPlugins('unsetData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getData($key = '', $index = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getData');
        if (!$pluginInfo) {
            return parent::getData($key, $index);
        } else {
            return $this->___callPlugins('getData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByPath($path)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByPath');
        if (!$pluginInfo) {
            return parent::getDataByPath($path);
        } else {
            return $this->___callPlugins('getDataByPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByKey($key)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByKey');
        if (!$pluginInfo) {
            return parent::getDataByKey($key);
        } else {
            return $this->___callPlugins('getDataByKey', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataUsingMethod($key, $args = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDataUsingMethod');
        if (!$pluginInfo) {
            return parent::setDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('setDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataUsingMethod($key, $args = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataUsingMethod');
        if (!$pluginInfo) {
            return parent::getDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('getDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasData($key = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasData');
        if (!$pluginInfo) {
            return parent::hasData($key);
        } else {
            return $this->___callPlugins('hasData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $keys = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toArray');
        if (!$pluginInfo) {
            return parent::toArray($keys);
        } else {
            return $this->___callPlugins('toArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToArray(array $keys = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToArray');
        if (!$pluginInfo) {
            return parent::convertToArray($keys);
        } else {
            return $this->___callPlugins('convertToArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toXml(array $keys = [], $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toXml');
        if (!$pluginInfo) {
            return parent::toXml($keys, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('toXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToXml(array $arrAttributes = [], $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToXml');
        if (!$pluginInfo) {
            return parent::convertToXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('convertToXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toJson(array $keys = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toJson');
        if (!$pluginInfo) {
            return parent::toJson($keys);
        } else {
            return $this->___callPlugins('toJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToJson(array $keys = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToJson');
        if (!$pluginInfo) {
            return parent::convertToJson($keys);
        } else {
            return $this->___callPlugins('convertToJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toString($format = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toString');
        if (!$pluginInfo) {
            return parent::toString($format);
        } else {
            return $this->___callPlugins('toString', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $args)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '__call');
        if (!$pluginInfo) {
            return parent::__call($method, $args);
        } else {
            return $this->___callPlugins('__call', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isEmpty');
        if (!$pluginInfo) {
            return parent::isEmpty();
        } else {
            return $this->___callPlugins('isEmpty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($keys = [], $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'serialize');
        if (!$pluginInfo) {
            return parent::serialize($keys, $valueSeparator, $fieldSeparator, $quote);
        } else {
            return $this->___callPlugins('serialize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debug($data = null, &$objects = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'debug');
        if (!$pluginInfo) {
            return parent::debug($data, $objects);
        } else {
            return $this->___callPlugins('debug', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetSet');
        if (!$pluginInfo) {
            return parent::offsetSet($offset, $value);
        } else {
            return $this->___callPlugins('offsetSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetExists');
        if (!$pluginInfo) {
            return parent::offsetExists($offset);
        } else {
            return $this->___callPlugins('offsetExists', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetUnset');
        if (!$pluginInfo) {
            return parent::offsetUnset($offset);
        } else {
            return $this->___callPlugins('offsetUnset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetGet');
        if (!$pluginInfo) {
            return parent::offsetGet($offset);
        } else {
            return $this->___callPlugins('offsetGet', func_get_args(), $pluginInfo);
        }
    }
}
