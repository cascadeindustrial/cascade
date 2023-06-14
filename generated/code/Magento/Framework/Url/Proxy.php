<?php
namespace Magento\Framework\Url;

/**
 * Proxy class for @see \Magento\Framework\Url
 */
class Proxy extends \Magento\Framework\Url implements \Magento\Framework\ObjectManager\NoninterceptableInterface
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Proxied instance name
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Proxied instance
     *
     * @var \Magento\Framework\Url
     */
    protected $_subject = null;

    /**
     * Instance shareability flag
     *
     * @var bool
     */
    protected $_isShared = null;

    /**
     * Proxy constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     * @param bool $shared
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Magento\\Framework\\Url', $shared = true)
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
        $this->_isShared = $shared;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['_subject', '_isShared', '_instanceName'];
    }

    /**
     * Retrieve ObjectManager from global scope
     */
    public function __wakeup()
    {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * Clone proxied instance
     */
    public function __clone()
    {
        $this->_subject = clone $this->_getSubject();
    }

    /**
     * Get proxied instance
     *
     * @return \Magento\Framework\Url
     */
    protected function _getSubject()
    {
        if (!$this->_subject) {
            $this->_subject = true === $this->_isShared
                ? $this->_objectManager->get($this->_instanceName)
                : $this->_objectManager->create($this->_instanceName);
        }
        return $this->_subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setUseSession($useSession)
    {
        return $this->_getSubject()->setUseSession($useSession);
    }

    /**
     * {@inheritdoc}
     */
    public function getUseSession()
    {
        return $this->_getSubject()->getUseSession();
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigData($key, $prefix = null)
    {
        return $this->_getSubject()->getConfigData($key, $prefix);
    }

    /**
     * {@inheritdoc}
     */
    public function setRequest(\Magento\Framework\App\RequestInterface $request)
    {
        return $this->_getSubject()->setRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function setScope($params)
    {
        return $this->_getSubject()->setScope($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUrl($params = [])
    {
        return $this->_getSubject()->getBaseUrl($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteUrl($routePath = null, $routeParams = null)
    {
        return $this->_getSubject()->getRouteUrl($routePath, $routeParams);
    }

    /**
     * {@inheritdoc}
     */
    public function addSessionParam()
    {
        return $this->_getSubject()->addSessionParam();
    }

    /**
     * {@inheritdoc}
     */
    public function addQueryParams(array $data)
    {
        return $this->_getSubject()->addQueryParams($data);
    }

    /**
     * {@inheritdoc}
     */
    public function setQueryParam($key, $data)
    {
        return $this->_getSubject()->setQueryParam($key, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($routePath = null, $routeParams = null)
    {
        return $this->_getSubject()->getUrl($routePath, $routeParams);
    }

    /**
     * {@inheritdoc}
     */
    public function getRebuiltUrl($url)
    {
        return $this->_getSubject()->getRebuiltUrl($url);
    }

    /**
     * {@inheritdoc}
     */
    public function escape($value)
    {
        return $this->_getSubject()->escape($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDirectUrl($url, $params = [])
    {
        return $this->_getSubject()->getDirectUrl($url, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function sessionUrlVar($html)
    {
        return $this->_getSubject()->sessionUrlVar($html);
    }

    /**
     * {@inheritdoc}
     */
    public function useSessionIdForUrl($secure = false)
    {
        return $this->_getSubject()->useSessionIdForUrl($secure);
    }

    /**
     * {@inheritdoc}
     */
    public function isOwnOriginUrl()
    {
        return $this->_getSubject()->isOwnOriginUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl($url)
    {
        return $this->_getSubject()->getRedirectUrl($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentUrl()
    {
        return $this->_getSubject()->getCurrentUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function addData(array $arr)
    {
        return $this->_getSubject()->addData($arr);
    }

    /**
     * {@inheritdoc}
     */
    public function setData($key, $value = null)
    {
        return $this->_getSubject()->setData($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function unsetData($key = null)
    {
        return $this->_getSubject()->unsetData($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getData($key = '', $index = null)
    {
        return $this->_getSubject()->getData($key, $index);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByPath($path)
    {
        return $this->_getSubject()->getDataByPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByKey($key)
    {
        return $this->_getSubject()->getDataByKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function setDataUsingMethod($key, $args = [])
    {
        return $this->_getSubject()->setDataUsingMethod($key, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataUsingMethod($key, $args = null)
    {
        return $this->_getSubject()->getDataUsingMethod($key, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function hasData($key = '')
    {
        return $this->_getSubject()->hasData($key);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $keys = [])
    {
        return $this->_getSubject()->toArray($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToArray(array $keys = [])
    {
        return $this->_getSubject()->convertToArray($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function toXml(array $keys = [], $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        return $this->_getSubject()->toXml($keys, $rootName, $addOpenTag, $addCdata);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToXml(array $arrAttributes = [], $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        return $this->_getSubject()->convertToXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
    }

    /**
     * {@inheritdoc}
     */
    public function toJson(array $keys = [])
    {
        return $this->_getSubject()->toJson($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToJson(array $keys = [])
    {
        return $this->_getSubject()->convertToJson($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function toString($format = '')
    {
        return $this->_getSubject()->toString($format);
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $args)
    {
        return $this->_getSubject()->__call($method, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->_getSubject()->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($keys = [], $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        return $this->_getSubject()->serialize($keys, $valueSeparator, $fieldSeparator, $quote);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($data = null, &$objects = [])
    {
        return $this->_getSubject()->debug($data, $objects);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        return $this->_getSubject()->offsetSet($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->_getSubject()->offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->_getSubject()->offsetUnset($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->_getSubject()->offsetGet($offset);
    }
}
