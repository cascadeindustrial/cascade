<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Api\Data;

/**
 * @api
 */
interface CourierSearchResultInterface
{
    /**
     * get items
     *
     * @return \MageMaclean\MyShipping\Api\Data\CourierInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param \MageMaclean\MyShipping\Api\Data\CourierInterface[] $items
     * @return $this
     */
    public function setItems(array $items);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $count
     * @return $this
     */
    public function setTotalCount($count);
}
