<?php
namespace Cminds\Creditline\Api\Data;

/**
 * @api
 */
interface ProductOptionCreditInterface
{
    const KEY_OPTION_PRODUCT_ID    = 'option_product_id';
    const KEY_STORE_ID             = 'store_id';
    const KEY_OPTION_PRICE_TYPE    = 'option_price_type';
    const KEY_OPTION_PRICE_OPTIONS = 'option_price_options';
    const KEY_OPTION_PRICE         = 'option_price';
    const KEY_OPTION_CREDITS       = 'option_credits';
    const KEY_OPTION_MIN_CREDITS   = 'option_min_credits';
    const KEY_OPTION_MAX_CREDITS   = 'option_max_credits';

    /**
     * @return int
     */
    public function getOptionProductId();

    /**
     * @param int $productId
     * @return $this
     */
    public function setOptionProductId($productId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * @return string
     */
    public function getOptionPriceType();

    /**
     * @param string $optionType
     * @return $this
     */
    public function setOptionPriceType($optionType);

    /**
     * @return string
     */
    public function getOptionPriceOptions();

    /**
     * @param string $optionType
     * @return $this
     */
    public function setOptionPriceOptions($optionType);

    /**
     * @return float
     */
    public function getOptionPrice();

    /**
     * @param float $price
     * @return $this
     */
    public function setOptionPrice($price);

    /**
     * @return float
     */
    public function getOptionCredits();

    /**
     * @param float $credits
     * @return $this
     */
    public function setOptionCredits($credits);

    /**
     * @return int
     */
    public function getOptionMinCredits();

    /**
     * @param int $credits
     * @return $this
     */
    public function setOptionMinCredits($credits);

    /**
     * @return int
     */
    public function getOptionMaxCredits();

    /**
     * @param int $credits
     * @return $this
     */
    public function setOptionMaxCredits($credits);
}