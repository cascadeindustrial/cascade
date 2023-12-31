<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

abstract class AbstractModel extends AbstractDb
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
    /**
     * @var MetadataPool
     */
    protected $metadataPool;
    /**
     * @var string
     */
    protected $interfaceClass;

    /**
     * AbstractModel constructor.
     * @param Context $context
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param string $interfaceClass
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        string $interfaceClass,
        $connectionName = null
    ) {
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
        $this->interfaceClass = $interfaceClass;
        parent::__construct($context, $connectionName);
    }

    /**
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this|AbstractDb
     * @throws \Exception
     */
    public function delete(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }

    /**
     * @return false|\Magento\Framework\DB\Adapter\AdapterInterface
     * @throws \Exception
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata($this->interfaceClass)->getEntityConnection();
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param null $field
     * @return $this|AbstractDb
     * @throws LocalizedException
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        $this->entityManager->load($object, $value);
        return $this;
    }
}
