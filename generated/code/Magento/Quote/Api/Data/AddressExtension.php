<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\AddressInterface
 */
class AddressExtension extends \Magento\Framework\Api\AbstractSimpleObject implements AddressExtensionInterface
{
    /**
     * @return \Magento\SalesRule\Api\Data\RuleDiscountInterface[]|null
     */
    public function getDiscounts()
    {
        return $this->_get('discounts');
    }

    /**
     * @param \Magento\SalesRule\Api\Data\RuleDiscountInterface[] $discounts
     * @return $this
     */
    public function setDiscounts($discounts)
    {
        $this->setData('discounts', $discounts);
        return $this;
    }

    /**
     * @return \Magento\Framework\Api\AttributeInterface[]|null
     */
    public function getCheckoutFields()
    {
        return $this->_get('checkout_fields');
    }

    /**
     * @param \Magento\Framework\Api\AttributeInterface[] $checkoutFields
     * @return $this
     */
    public function setCheckoutFields($checkoutFields)
    {
        $this->setData('checkout_fields', $checkoutFields);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMyshippingAccountId()
    {
        return $this->_get('myshipping_account_id');
    }

    /**
     * @param int $myshippingAccountId
     * @return $this
     */
    public function setMyshippingAccountId($myshippingAccountId)
    {
        $this->setData('myshipping_account_id', $myshippingAccountId);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMyshippingCourierId()
    {
        return $this->_get('myshipping_courier_id');
    }

    /**
     * @param int $myshippingCourierId
     * @return $this
     */
    public function setMyshippingCourierId($myshippingCourierId)
    {
        $this->setData('myshipping_courier_id', $myshippingCourierId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMyshippingCourierMethod()
    {
        return $this->_get('myshipping_courier_method');
    }

    /**
     * @param string $myshippingCourierMethod
     * @return $this
     */
    public function setMyshippingCourierMethod($myshippingCourierMethod)
    {
        $this->setData('myshipping_courier_method', $myshippingCourierMethod);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMyshippingAccount()
    {
        return $this->_get('myshipping_account');
    }

    /**
     * @param string $myshippingAccount
     * @return $this
     */
    public function setMyshippingAccount($myshippingAccount)
    {
        $this->setData('myshipping_account', $myshippingAccount);
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getMyshippingSave()
    {
        return $this->_get('myshipping_save');
    }

    /**
     * @param bool $myshippingSave
     * @return $this
     */
    public function setMyshippingSave($myshippingSave)
    {
        $this->setData('myshipping_save', $myshippingSave);
        return $this;
    }
}
