<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

abstract class StoreAwareAbstractModel extends AbstractModel
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var string
     */
    protected $storeIdField;
    /**
     * @var string
     */
    protected $storeTable;

    /**
     * StoreAwareAbstractModel constructor.
     * @param Context $context
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param string $interfaceClass
     * @param StoreManagerInterface $storeManager
     * @param string $storeTable
     * @param null $connectionName
     * @param string $storeIdField
     */
    public function __construct(
        Context $context,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        string $interfaceClass,
        StoreManagerInterface $storeManager,
        string $storeTable,
        $connectionName = null,
        string $storeIdField = 'store_id'
    ) {
        $this->storeManager = $storeManager;
        $this->storeIdField = $storeIdField;
        $this->storeTable = $storeTable;
        parent::__construct($context, $entityManager, $metadataPool, $interfaceClass, $connectionName);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return Select
     * @throws LocalizedException
     * //phpcs:disable PSR2.Methods.MethodDeclaration.Underscore,PSR12.Methods.MethodDeclaration.Underscore
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata($this->interfaceClass);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($storeId = $object->getData($this->storeIdField)) {
            $stores = [(int)$storeId, Store::DEFAULT_STORE_ID];

            $select->join(
                ['entity_store_table' => $this->getTable($this->storeTable)],
                $this->getMainTable() . '.' . $linkField . ' = entity_store_table.' . $linkField,
                [$this->storeIdField]
            );
            $select->where('entity_store_table.store_id in (?)', $stores);
            $select->order('store_id DESC');
            $select->limit(1);
        }
        return $select;
    }
    //phpcs:enable

    /**
     * @param AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return bool|int|string
     * @throws LocalizedException
     * @throws \Exception
     */
    private function getEntityId(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata($this->interfaceClass);
        if (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }
        $entityId = $value;
        if ($field != $entityMetadata->getIdentifierField() || $object->getData($this->storeIdField)) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS);
            $select->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField());
            $select->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $entityId = count($result) ? $result[0] : false;
        }
        return $entityId;
    }

    /**
     * @param AbstractModel $object
     * @param mixed $value
     * @param null $field
     * @return $this|AbstractDb
     * @throws LocalizedException
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        $entityId = $this->getEntityId($object, $value, $field);
        if ($entityId) {
            $this->entityManager->load($object, $entityId);
        }
        return $this;
    }

    /**
     * @param $id
     * @return array
     * @throws LocalizedException
     */
    public function lookupStoreIds($id): array
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata($this->interfaceClass);
        $idField = $entityMetadata->getIdentifierField();
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select();
        $select->from(['entity_store_table' => $this->getTable($this->storeTable)], $this->storeIdField);
        $select->join(
            ['entity_table' => $this->getMainTable()],
            'entity_store_table.' . $linkField . ' = entity_table.' . $linkField,
            []
        );
        $select->where('entity_table.' . $entityMetadata->getIdentifierField() . ' = :' . $idField);
        return $connection->fetchCol($select, [$idField => (int)$id]);
    }
}
