<?php
namespace MageMaclean\MyShipping\Api\Data;

use MageMaclean\MyShipping\Api\Data\AccountInterface;

/**
 * Interface MyshippingResultInterface
 * @api
 */
interface MyshippingResultInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const MYSHIPPING_ACCOUNT_ID = 'myshipping_account_id';
    public const MYSHIPPING_COURIER_ID = 'myshipping_courier_id';
    public const MYSHIPPING_COURIER_METHOD = 'myshipping_courier_method';
    public const MYSHIPPING_ACCOUNT = 'myshipping_account';
    public const MYSHIPPING_SAVE = 'myshipping_save';

    /**
     * @return float
     */
    public function getShippingPrice();

    /**
     * @param array $items
     * @return $this
     */
    public function setItems(array $items);

    /**
     * @return mixed[]
     */
    public function getItems();

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

    /**
     * @return AccountInterface
     */
    public function getAccount();

    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getCarrierTitle();

    /**
     * @return string
     */
    public function getMethodTitle();

    /**
     * @return string
     */
    public function getShippingDescription();

    /**
     * @return string
     */
    public function getCarrier();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return boolean
     */
    public function isNew();

    /**
     * @return boolean
     */
    public function isAccount();

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \MageMaclean\MyShipping\Api\Data\MyshippingResultExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \MageMaclean\MyShipping\Api\Data\MyshippingResultExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \MageMaclean\MyShipping\Api\Data\MyshippingResultExtensionInterface $extensionAttributes
    );
}
