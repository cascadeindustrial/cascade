<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model\ResourceModel\Collection;

use MageMaclean\MyShipping\Model\ResourceModel\Store;
use Magento\Framework\EntityManager\MetadataPool;

class StoreAwareAbstractCollection extends AbstractCollection
{
    /**
     * @var Store
     */
    private $storeResource;
    /**
     * @var string
     */
    private $interfaceClass;
    /**
     * @var MetadataPool
     */
    private $metadataPool;
    /**
     * @var string
     */
    private $storeTable;

    /**
     * StoreAwareAbstractCollection constructor.
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param Store $storeResource
     * @param MetadataPool $metadataPool
     * @param string $interfaceClass
     * @param string $storeTable
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        string $mainTable,
        string $eventPrefix,
        string $eventObject,
        string $resourceModel,
        string $model,
        Store $storeResource,
        MetadataPool $metadataPool,
        string $interfaceClass,
        string $storeTable,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->storeResource = $storeResource;
        $this->metadataPool = $metadataPool;
        $this->interfaceClass = $interfaceClass;
        $this->storeTable = $storeTable;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $eventPrefix,
            $eventObject,
            $resourceModel,
            $model,
            $connection,
            $resource
        );
    }

    /**
     * @return AbstractCollection
     * @throws \Exception
     * //phpcs:disable PSR2.Methods.MethodDeclaration.Underscore,PSR12.Methods.MethodDeclaration.Underscore
     */
    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata($this->interfaceClass);
        $this->storeResource->addStoresToCollection($this, $this->storeTable, $entityMetadata->getLinkField());
        return parent::_afterLoad();
    }

    /**
     * @throws \Exception
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata($this->interfaceClass);
        $this->storeResource->joinStoreRelationTable($this, $this->storeTable, $entityMetadata->getLinkField());
        parent::_renderFiltersBefore();
    }
    //phpcs:enable

    /**
     * @param $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->storeResource->addStoreFilter($this, $store, 'store_id', $withAdmin);
        return $this;
    }

    /**
     * @param array|string $field
     * @param null $condition
     * @return AbstractCollection|StoreAwareAbstractCollection
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, true);
        }
        return parent::addFieldToFilter($field, $condition);
    }
}
