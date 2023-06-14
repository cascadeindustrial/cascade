<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Source;

use MageMaclean\MyShipping\Api\CourierListRepositoryInterface;
use MageMaclean\MyShipping\Api\Data\CourierInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;

class Courier implements OptionSourceInterface
{
    /**
     * @var CourierListRepositoryInterface
     */
    private $repository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var array
     */
    private $options;

    /**
     * Courier constructor.
     * @param CourierListRepositoryInterface $repository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        CourierListRepositoryInterface $repository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->repository = $repository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = array_map(
                function ($courier) {
                    return [
                        'label' => $courier->getTitle(),
                        'value' => $courier->getCourierId()
                    ];
                },
                $this->repository->getList($this->searchCriteriaBuilder->create())->getItems()
            );
            uasort(
                $this->options,
                function (array $optionA, array $optionB) {
                    return strcmp($optionA['label'], $optionB['label']);
                }
            );
            $this->options = array_values($this->options);
        }
        return $this->options;
    }
}
