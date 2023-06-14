<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model\Repository;

use MageMaclean\MyShipping\Api\CourierListRepositoryInterface;
use MageMaclean\MyShipping\Api\Data\CourierSearchResultInterfaceFactory;
use MageMaclean\MyShipping\Model\ResourceModel\Courier\Collection;
use MageMaclean\MyShipping\Model\ResourceModel\Courier\CollectionFactory;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;

class CourierListRepository implements CourierListRepositoryInterface
{
    /**
     * @var CourierSearchResultInterfaceFactory
     */
    private $searchResultFactory;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    protected $_searchCriteriaBuilder;
    protected $_filterBuilder;

    /**
     * @param CourierSearchResultInterfaceFactory $searchResultFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        CourierSearchResultInterfaceFactory $searchResultFactory,
        CollectionFactory $collectionFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->searchResultFactory = $searchResultFactory;
        $this->collectionFactory = $collectionFactory;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_filterBuilder = $filterBuilder;
    }

    /**
     * @param int storeId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getHasStoreCouriers($storeId = null) {
        $filters = array();
        if($storeId) {
            $filters[] = $this->_filterBuilder
                ->setField('store')
                ->setConditionType('eq')
                ->setValue($storeId)
                ->create();

            $searchCriteria = $this->_searchCriteriaBuilder->addFilters($filters)->create();

            $courierListResults = $this->getList($searchCriteria);
        } else {
            $searchCriteria = $this->_searchCriteriaBuilder->create();
            $courierListResults = $this->getList($searchCriteria);
        }

        return ($courierListResults->getTotalCount() > 0);
    }

    /**
     * @param int storeId
     * @return CourierSearchResultInterfaceFactory
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getStoreList($storeId = null) {
        $filters = array();
        if($storeId) {
            $filters[] = $this->_filterBuilder
                ->setField('store')
                ->setConditionType('eq')
                ->setValue($storeId)
                ->create();

            $searchCriteria = $this->_searchCriteriaBuilder->addFilters($filters)->create();

            $courierListResults = $this->getList($searchCriteria);
        } else {
            $searchCriteria = $this->_searchCriteriaBuilder->create();
            $courierListResults = $this->getList($searchCriteria);
        }

        return $courierListResults->getItems();
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return CourierSearchResultInterfaceFactory
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var CourierSearchResultInterfaceFactory $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $direction = $sortOrder->getDirection();
                $collection->addOrder(
                    $field,
                    ($direction === SortOrder::SORT_ASC) ? SortOrder::SORT_ASC : SortOrder::SORT_DESC
                );
            }
        } else {
            $collection->addOrder(
                'sort_order',
                SortOrder::SORT_ASC
            );
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setItems($collection->getItems());
        return $searchResult;
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection $collection
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     */
    private function addFilterGroupToCollection(
        FilterGroup $filterGroup,
        Collection $collection
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            if ($filter->getField() === 'store') {
                $collection->addStoreFilter($filter->getValue(), true);
            } else {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
        return $this;
    }
}
