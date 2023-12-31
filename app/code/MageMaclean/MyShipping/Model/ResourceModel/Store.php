<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\StoreManagerInterface;

class Store
{
    /**
     * @param AbstractCollection $collection
     * @param string $tableName
     * @param string $linkField
     * @param string $storeIdField
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function addStoresToCollection(
        AbstractCollection $collection,
        string $tableName,
        string $linkField,
        $storeIdField = 'store_id'
    ) {
        $linkedIds = $collection->getColumnValues($linkField);
        if (count($linkedIds)) {
            $connection = $collection->getConnection();
            $select = $connection->select()->from(['entity_store' => $collection->getTable($tableName)])
                ->where('entity_store.' . $linkField . ' IN (?)', $linkedIds);
            $result = $connection->fetchAll($select);
            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData[$linkField]][] = $storeData[$storeIdField];
                }
                foreach ($collection as $item) {
                    $linkedId = $item->getData($linkField);
                    if (!isset($storesData[$linkedId])) {
                        continue;
                    }
                    $item->setData($storeIdField, $storesData[$linkedId]);
                }
            }
        }
    }

    /**
     * @param AbstractCollection $collection
     * @param $store
     * @param string $storeField
     * @param bool $withAdmin
     */
    public function addStoreFilter(AbstractCollection $collection, $store, $storeField = 'store_id', $withAdmin = true)
    {
        if (!is_array($store)) {
            $store = [$store];
        }
        if ($withAdmin) {
            $store[] = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        }
        $collection->addFilter($storeField, ['in' => $store], 'public');
    }

    /**
     * @param AbstractCollection $collection
     * @param string $tableName
     * @param string $linkField
     */
    public function joinStoreRelationTable(AbstractCollection $collection, string $tableName, string $linkField)
    {
        $collection->getSelect()->join(
            ['store_table' => $collection->getTable($tableName)],
            'main_table.' . $linkField . ' = store_table.' . $linkField,
            []
        )->group(
            'main_table.' . $linkField
        );
    }
}
