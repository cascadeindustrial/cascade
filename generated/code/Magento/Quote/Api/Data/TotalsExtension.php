<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\TotalsInterface
 */
class TotalsExtension extends \Magento\Framework\Api\AbstractSimpleObject implements TotalsExtensionInterface
{
    /**
     * @return string|null
     */
    public function getCouponLabel()
    {
        return $this->_get('coupon_label');
    }

    /**
     * @param string $couponLabel
     * @return $this
     */
    public function setCouponLabel($couponLabel)
    {
        $this->setData('coupon_label', $couponLabel);
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
}
