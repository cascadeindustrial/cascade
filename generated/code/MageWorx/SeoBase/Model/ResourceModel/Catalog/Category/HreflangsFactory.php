<?php
namespace MageWorx\SeoBase\Model\ResourceModel\Catalog\Category;

/**
 * Factory class for @see \MageWorx\SeoBase\Model\ResourceModel\Catalog\Category\Hreflangs
 */
class HreflangsFactory
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\MageWorx\\SeoBase\\Model\\ResourceModel\\Catalog\\Category\\Hreflangs')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \MageWorx\SeoBase\Model\ResourceModel\Catalog\Category\Hreflangs
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
