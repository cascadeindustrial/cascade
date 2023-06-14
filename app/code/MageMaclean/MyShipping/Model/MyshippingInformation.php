<?php
namespace MageMaclean\MyShipping\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use MageMaclean\MyShipping\Api\Data\MyshippingInformationInterface;

class MyshippingInformation extends AbstractExtensibleModel implements MyshippingInformationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getShippingAddress()
    {
        return $this->getData(self::SHIPPING_ADDRESS);
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAddress(\Magento\Quote\Api\Data\AddressInterface $address)
    {
        return $this->setData(self::SHIPPING_ADDRESS, $address);
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressId()
    {
        return $this->getData(self::ADDRESS_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setAddressId($addressId)
    {
        return $this->setData(self::ADDRESS_ID, $addressId);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethodCode()
    {
        return $this->getData(self::SHIPPING_METHOD_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingMethodCode($code)
    {
        return $this->setData(self::SHIPPING_METHOD_CODE, $code);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingCarrierCode()
    {
        return $this->getData(self::SHIPPING_CARRIER_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingCarrierCode($code)
    {
        return $this->setData(self::SHIPPING_CARRIER_CODE, $code);
    }

    /**
     * {@inheritdoc}
     */
    public function getMyshippingAccountId()
    {
        return $this->getData(self::MYSHIPPING_ACCOUNT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setMyshippingAccountId($value)
    {
        return $this->setData(self::MYSHIPPING_ACCOUNT_ID, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMyshippingCourierId()
    {
        return $this->getData(self::MYSHIPPING_COURIER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setMyshippingCourierId($value)
    {
        return $this->setData(self::MYSHIPPING_COURIER_ID, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMyshippingCourierMethod()
    {
        return $this->getData(self::MYSHIPPING_COURIER_METHOD);
    }

    /**
     * {@inheritdoc}
     */
    public function setMyshippingCourierMethod($value)
    {
        return $this->setData(self::MYSHIPPING_COURIER_METHOD, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMyshippingAccount()
    {
        return $this->getData(self::MYSHIPPING_ACCOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setMyshippingAccount($value)
    {
        return $this->setData(self::MYSHIPPING_ACCOUNT, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMyshippingSave()
    {
        return $this->getData(self::MYSHIPPING_COURIER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setMyshippingSave($value)
    {
        return $this->setData(self::MYSHIPPING_SAVE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \MageMaclean\MyShipping\Api\Data\MyshippingInformationExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
