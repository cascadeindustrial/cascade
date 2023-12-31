<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model\ResourceModel\Relation\Store;

use MageMaclean\MyShipping\Model\ResourceModel\StoreAwareAbstractModel;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class SaveHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;
    /**
     * @var StoreAwareAbstractModel
     */
    private $resource;
    /**
     * @var string
     */
    private $entityType;
    /**
     * @var string
     */
    private $storeTable;
    /**
     * @var string
     */
    private $storeIdField;

    /**
     * SaveHandler constructor.
     * @param MetadataPool $metadataPool
     * @param StoreAwareAbstractModel $resource
     * @param string $entityType
     * @param string $storeTable
     * @param string $storeIdField
     */
    public function __construct(
        MetadataPool $metadataPool,
        StoreAwareAbstractModel $resource,
        string $entityType,
        string $storeTable,
        string $storeIdField = 'store_id'
    ) {
        $this->metadataPool = $metadataPool;
        $this->resource = $resource;
        $this->entityType = $entityType;
        $this->storeTable = $storeTable;
        $this->storeIdField = $storeIdField;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     * @throws \Exception
     */
    public function execute($entity, $arguments = [])
    {
        $entityMetadata = $this->metadataPool->getMetadata($this->entityType);
        $linkField = $entityMetadata->getLinkField();

        $connection = $this->resource->getConnection();

        $oldStores = $this->resource->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getData($this->storeIdField);

        $table = $connection->getTableName($this->storeTable);

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                $this->storeIdField . ' IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    $this->storeIdField => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }
        return $entity;
    }
}
