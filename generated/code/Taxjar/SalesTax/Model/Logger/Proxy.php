<?php
namespace Taxjar\SalesTax\Model\Logger;

/**
 * Proxy class for @see \Taxjar\SalesTax\Model\Logger
 */
class Proxy extends \Taxjar\SalesTax\Model\Logger implements \Magento\Framework\ObjectManager\NoninterceptableInterface
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
     * @var \Taxjar\SalesTax\Model\Logger
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Taxjar\\SalesTax\\Model\\Logger', $shared = true)
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
     * @return \Taxjar\SalesTax\Model\Logger
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
    public function setFilename(string $filename) : \Taxjar\SalesTax\Model\Logger
    {
        return $this->_getSubject()->setFilename($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function setPlayback(array $playback) : \Taxjar\SalesTax\Model\Logger
    {
        return $this->_getSubject()->setPlayback($playback);
    }

    /**
     * {@inheritdoc}
     */
    public function force(bool $isForced = true) : \Taxjar\SalesTax\Model\Logger
    {
        return $this->_getSubject()->force($isForced);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath() : string
    {
        return $this->_getSubject()->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function log($message, $label = '') : void
    {
        $this->_getSubject()->log($message, $label);
    }

    /**
     * {@inheritdoc}
     */
    public function record() : void
    {
        $this->_getSubject()->record();
    }

    /**
     * {@inheritdoc}
     */
    public function playback() : array
    {
        return $this->_getSubject()->playback();
    }

    /**
     * {@inheritdoc}
     */
    public function console($output) : void
    {
        $this->_getSubject()->console($output);
    }
}
