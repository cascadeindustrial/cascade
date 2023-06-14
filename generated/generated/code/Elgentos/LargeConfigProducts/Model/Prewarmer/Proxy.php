<?php
namespace Elgentos\LargeConfigProducts\Model\Prewarmer;

/**
 * Proxy class for @see \Elgentos\LargeConfigProducts\Model\Prewarmer
 */
class Proxy extends \Elgentos\LargeConfigProducts\Model\Prewarmer implements \Magento\Framework\ObjectManager\NoninterceptableInterface
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
     * @var \Elgentos\LargeConfigProducts\Model\Prewarmer
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Elgentos\\LargeConfigProducts\\Model\\Prewarmer', $shared = true)
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
     * @return \Elgentos\LargeConfigProducts\Model\Prewarmer
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
    public function prewarm($productIdsToWarm, $storeCodesToWarm, $force)
    {
        return $this->_getSubject()->prewarm($productIdsToWarm, $storeCodesToWarm, $force);
    }

    /**
     * {@inheritdoc}
     */
    public function getJsonConfig($currentProduct)
    {
        return $this->_getSubject()->getJsonConfig($currentProduct);
    }
}
