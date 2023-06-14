<?php
namespace MageMaclean\MyShippingCustomer\Api\Data;


/**
 * Interface CustomerInterface
 * @api
 */
interface CustomerInterface
{
    public const CUSTOMER_ID = 'customer_id';
    public const MYSHIPPING_ENABLED = 'myshipping_enabled';
    public const MYSHIPPING_NEW_ENABLED = 'myshipping_new_enabled';
    
    /**
     * @param int $value
     * @return void
     */
    public function setCustomerId(int $value);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param bool $value
     * @return void
     */
    public function setMyshippingEnabled(bool $value);

    /**
     * @return bool
     */
    public function getMyshippingEnabled();

    /**
     * @param bool $value
     * @return void
     */
    public function setMyshippingNewEnabled(bool $value);

    /**
     * @return bool
     */
    public function getMyshippingNewEnabled();
}
