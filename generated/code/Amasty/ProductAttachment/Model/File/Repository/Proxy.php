<?php
namespace Amasty\ProductAttachment\Model\File\Repository;

/**
 * Proxy class for @see \Amasty\ProductAttachment\Model\File\Repository
 */
class Proxy extends \Amasty\ProductAttachment\Model\File\Repository implements \Magento\Framework\ObjectManager\NoninterceptableInterface
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
     * @var \Amasty\ProductAttachment\Model\File\Repository
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Amasty\\ProductAttachment\\Model\\File\\Repository', $shared = true)
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
     * @return \Amasty\ProductAttachment\Model\File\Repository
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
    public function save(\Amasty\ProductAttachment\Api\Data\FileInterface $file)
    {
        return $this->_getSubject()->save($file);
    }

    /**
     * {@inheritdoc}
     */
    public function saveAll(\Amasty\ProductAttachment\Api\Data\FileInterface $file, $params = [], $checkExtension = true)
    {
        return $this->_getSubject()->saveAll($file, $params, $checkExtension);
    }

    /**
     * {@inheritdoc}
     */
    public function needToCheckUrl($file)
    {
        return $this->_getSubject()->needToCheckUrl($file);
    }

    /**
     * {@inheritdoc}
     */
    public function getById($fileId)
    {
        return $this->_getSubject()->getById($fileId);
    }

    /**
     * {@inheritdoc}
     */
    public function getByHash($hash)
    {
        return $this->_getSubject()->getByHash($hash);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Amasty\ProductAttachment\Api\Data\FileInterface $file)
    {
        return $this->_getSubject()->delete($file);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($fileId)
    {
        return $this->_getSubject()->deleteById($fileId);
    }
}
