<?php
namespace MageMaclean\MyShipping\Api\Data;

/**
 * Interface MyshippingInformationInterface
 * @api
 */
interface MyshippingInformationInterface extends \Magento\Framework\Api\CustomAttributesDataInterface
{
    const ADDRESS_ID = 'address_id';
    const SHIPPING_ADDRESS = 'shipping_address';
    const SHIPPING_METHOD_CODE = 'shipping_method_code';
    const SHIPPING_CARRIER_CODE = 'shipping_carrier_code';

    const MYSHIPPING_ACCOUNT_ID = 'myshipping_account_id';
    const MYSHIPPING_COURIER_ID = 'myshipping_courier_id';
    const MYSHIPPING_COURIER_METHOD = 'myshipping_courier_method';
    const MYSHIPPING_ACCOUNT = 'myshipping_account';
    const MYSHIPPING_SAVE = 'myshipping_save';

    /**
     * Returns shipping address
     *
     * @return \Magento\Quote\Api\Data\AddressInterface
     */
    public function getShippingAddress();

    /**
     * Set shipping address
     *
     * @param \Magento\Quote\Api\Data\AddressInterface $address
     * @return $this
     */
    public function setShippingAddress(\Magento\Quote\Api\Data\AddressInterface $address);

    /**
     * Returns address id
     *
     * @return string
     */
    public function getAddressId();

    /**
     * Set address id
     *
     * @param string $addressId
     * @return $this
     */
    public function setAddressId($addressId);


    /**
     * Returns shipping method code
     *
     * @return string
     */
    public function getShippingMethodCode();

    /**
     * Set shipping method code
     *
     * @param string $code
     * @return $this
     */
    public function setShippingMethodCode($code);

    /**
     * Returns carrier code
     *
     * @return string
     */
    public function getShippingCarrierCode();

    /**
     * Set carrier code
     *
     * @param string $code
     * @return $this
     */
    public function setShippingCarrierCode($code);

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
     * @return string
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
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \MageMaclean\MyShipping\Api\Data\MyshippingInformationExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \MageMaclean\MyShipping\Api\Data\MyshippingInformationExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \MageMaclean\MyShipping\Api\Data\MyshippingInformationExtensionInterface $extensionAttributes
    );
}
