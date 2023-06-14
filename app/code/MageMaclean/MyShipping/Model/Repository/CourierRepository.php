<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model\Repository;

use MageMaclean\MyShipping\Api\CourierRepositoryInterface;
use MageMaclean\MyShipping\Api\Data\CourierInterface;
use MageMaclean\MyShipping\Model\CourierFactory;
use MageMaclean\MyShipping\Model\ResourceModel\Courier as CourierResourceModel;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;

class CourierRepository implements CourierRepositoryInterface
{
    /**
     * @var CourierFactory
     */
    private $factory;
    /**
     * @var CourierResourceModel
     */
    private $resource;
    /**
     * @var CourierInterface[]
     */
    private $cache = [];

    /** 
     * @var AccountRepository
     */
    private $_accountRepository;

    /**
     * @param CourierFactory $factory
     * @param CourierResourceModel $resource
     * @param AccountRepository $accountRepository
     * @return void
     */
    public function __construct(
        CourierFactory $factory,
        CourierResourceModel $resource,
        AccountRepository $accountRepository
    ) {
        $this->factory = $factory;
        $this->resource = $resource;
        $this->_accountRepository = $accountRepository;
    }

    /**
     * @inheritdoc
     */
    public function save(CourierInterface $courier)
    {
        try {
            $this->resource->save($courier);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __($exception->getMessage())
            );
        }
        return $courier;
    }

    /**
     * @inheritdoc
     */
    public function get(int $courierId)
    {
        if (!isset($this->cache[$courierId])) {
            $courier = $this->factory->create();
            $this->resource->load($courier, $courierId);
            if (!$courier->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(
                    __('The Courier with the ID "%1" does not exist . ', $courierId)
                );
            }
            $this->cache[$courierId] = $courier;
        }
        return $this->cache[$courierId];
    }

    /**
     * @inheritdoc
     */
    public function delete($courier)
    {
        try {
            $id = $courier->getId();

            $accounts = $this->_accountRepository->getListByCourierId($courier->getId());
            if($accounts && $accounts->count()) {
                throw new \Magento\Framework\Exception\CouldNotDeleteException(__("Can't delete courier, this courier is being used in %1 stored customer accounts.", $accounts->count()));
            } else {
                $this->resource->delete($courier);
                unset($this->cache[$id]);
            }
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __($exception->getMessage())
            );
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $courierId)
    {
        return $this->delete($this->get($courierId));
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        return $this->cache = [];
    }
}
