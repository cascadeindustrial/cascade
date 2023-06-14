<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Api;

use MageMaclean\MyShipping\Api\Data\CourierInterface;

/**
 * @api
 */
interface CourierRepositoryInterface
{
    /**
     * @param \MageMaclean\MyShipping\Api\Data\CourierInterface $courier
     * @return \MageMaclean\MyShipping\Api\Data\CourierInterface
     */
    public function save(CourierInterface $courier);

    /**
     * @param int $courierId
     * @return \MageMaclean\MyShipping\Api\Data\CourierInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $courierId);

    /**
     * @param \MageMaclean\MyShipping\Api\Data\CourierInterface $courier
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete($courier);

    /**
     * @param int $courierId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $courierId);

    /**
     * clear caches instances
     * @return void
     */
    public function clear();
}
