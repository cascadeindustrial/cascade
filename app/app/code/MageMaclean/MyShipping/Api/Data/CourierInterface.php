<?php
declare(strict_types=1);

namespace MageMaclean\MyShipping\Api\Data;

/**
 * Interface CourierInterface
 * @api
 */
interface CourierInterface
{
    public const COURIER_ID = 'courier_id';
    public const IS_ENABLED = 'is_enabled';
    public const TITLE = 'title';
    public const METHODS = 'methods';
    public const STORE_ID = 'store_id';
    public const SALLOWSPECIFIC = 'sallowspecific';
    public const SPECIFICCOUNTRY = 'specificcountry';
    public const SORT_ORDER = 'sort_order';

    /**
     * @return string $carrierCode
     */
    public function getCarrierCode();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setCourierId($id);

    /**
     * @return int
     */
    public function getCourierId();
    /**
     * @param int $isEnabled
     * @return $this
     */
    public function setIsEnabled($isEnabled);

    /**
     * @return int
     */
    public function getIsEnabled();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string|array $methods
     * @return $this
     */
    public function setMethods($methods);

    /**
     * @param bool $asArray
     * @return string|array
     */
    public function getMethods($asArray = false);

    /**
     * @param bool $sallowspecific
     * @return $this
     */
    public function setSallowspecific($sallowspecific);

    /**
     * @return bool
     */
    public function getSallowspecific();
    
    /**
     * @param string $specificcountry
     * @return $this
     */
    public function setSpecificcountry($specificcountry);

    /**
     * @param bool $asArray
     * @return string|array
     */
    public function getSpecificcountry($asArray = false);

    /**
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * @return int[]
     */
    public function getSortOrder();

    /**
     * @param int[] $store
     * @return $this
     */
    public function setStoreId($store);

    /**
     * @return int[]
     */
    public function getStoreId();

}
