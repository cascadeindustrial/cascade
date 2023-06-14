<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Api;

interface CourierListRepositoryInterface
{
    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MageMaclean\MyShipping\Api\Data\CourierSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
