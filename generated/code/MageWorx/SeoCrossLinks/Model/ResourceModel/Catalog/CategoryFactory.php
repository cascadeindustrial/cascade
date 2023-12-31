<?php
namespace MageWorx\SeoCrossLinks\Model\ResourceModel\Catalog;

/**
 * Factory class for @see \MageWorx\SeoCrossLinks\Model\ResourceModel\Catalog\Category
 */
class CategoryFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\MageWorx\\SeoCrossLinks\\Model\\ResourceModel\\Catalog\\Category')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \MageWorx\SeoCrossLinks\Model\ResourceModel\Catalog\Category
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
