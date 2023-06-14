<?php
namespace MageMaclean\MyShipping\Api\Data;

interface AccountInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ID = 'id';
    const CUSTOMER_ID = 'customer_id';
    const MYSHIPPING_COURIER_ID = 'myshipping_courier_id';
    const MYSHIPPING_COURIER_METHOD = 'myshipping_courier_method';
    const MYSHIPPING_ACCOUNT = 'myshipping_account';
    const POSITION = 'position';

    /**
     * @return int
     */
    public function getId();
    public function setId(int $id);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param int $value
     * @return $this
     */
    public function setCustomerId(int $customerId);

    /**
     * @return int
     */
    public function getMyshippingCourierId();

    /**
     * @param int $value
     * @return $this
     */
    public function setMyshippingCourierId(int $value);

    /**
     * @return string
     */
    public function getMyshippingCourierMethod();

    /**
     * @param string $value
     * @return $this
     */
    public function setMyshippingCourierMethod(string $value);

    /**
     * @return string
     */
    public function getMyshippingAccount();

    /**
     * @param string $value
     * @return $this
     */
    public function setMyshippingAccount(string $value);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $value
     * @return $this
     */
    public function setPosition(int $value);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \MageMaclean\MyShipping\Api\Data\AccountExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \MageMaclean\MyShipping\Api\Data\AccountExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \MageMaclean\MyShipping\Api\Data\AccountExtensionInterface $extensionAttributes
    );
}
