<?php
namespace MageMaclean\MyShipping\Api\Data;


/**
 * Interface MyshippingQuoteInterface
 * @api
 */
interface MyshippingQuoteInterface
{
    public const MYSHIPPING_ACCOUNT_ID = 'myshipping_account_id';
    public const MYSHIPPING_COURIER_ID = 'myshipping_courier_id';
    public const MYSHIPPING_COURIER_METHOD = 'myshipping_courier_method';
    public const MYSHIPPING_ACCOUNT = 'myshipping_account';
    public const MYSHIPPING_SAVE = 'myshipping_save';

    /**
     * @param int $value
     * @return $this
     */
    public function setQuoteAddressId(int $value);

    /**
     * @return int
     */
    public function getQuoteAddressId();

    /**
     * @param int $value
     * @return $this
     */
    public function setMyshippingAccountId(int $value);

    /**
     * @return int
     */
    public function getMyshippingAccountId();

    /**
     * @param int $value
     * @return $this
     */
    public function setMyshippingCourierId(int $value);

    /**
     * @return int
     */
    public function getMyshippingCourierId();

    /**
     * @param string $value
     * @return $this
     */
    public function setMyshippingCourierMethod(string $value);

    /**
     * @return string
     */
    public function getMyshippingCourierMethod();

    /**
     * @param string $value
     * @return $this
     */
    public function setMyshippingAccount(string $value);

    /**
     * @return string|null
     */
    public function getMyshippingAccount();

    /**
     * @param boolean $value
     * @return $this
     */
    public function setMyshippingSave(bool $value);

    /**
     * @return bool
     */
    public function getMyshippingSave();
}
