<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model;

use MageMaclean\MyShipping\Api\CourierRepositoryInterface;
use MageMaclean\MyShipping\Api\Data\CourierInterface;
use MageMaclean\MyShipping\Ui\EntityUiManagerInterface;

class CourierUiManager implements EntityUiManagerInterface
{
    /**
     * @var CourierRepositoryInterface
     */
    private $repository;
    /**
     * @var CourierFactory
     */
    public $factory;

    /**
     * @param CourierRepositoryInterface $repository
     * @param CourierFactory $factory
     */
    public function __construct(
        CourierRepositoryInterface $repository,
        CourierFactory $factory
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param int|null $id
     * @return \Magento\Framework\Model\AbstractModel | Courier | CourierInterface;
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(?int $id)
    {
        return ($id)
            ? $this->repository->get($id)
            : $this->factory->create();
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $courier
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Magento\Framework\Model\AbstractModel $courier)
    {
        $this->repository->save($courier);
    }

    /**
     * @param int $id
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(int $id)
    {
        $this->repository->deleteById($id);
    }
}
