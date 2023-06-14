<?php
namespace Magento\Quote\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Quote\Api\Data\AddressInterface
 */
interface AddressExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\SalesRule\Api\Data\RuleDiscountInterface[]|null
     */
    public function getDiscounts();

    /**
     * @param \Magento\SalesRule\Api\Data\RuleDiscountInterface[] $discounts
     * @return $this
     */
    public function setDiscounts($discounts);

    /**
     * @return \Magento\Framework\Api\AttributeInterface[]|null
     */
    public function getCheckoutFields();

    /**
     * @param \Magento\Framework\Api\AttributeInterface[] $checkoutFields
     * @return $this
     */
    public function setCheckoutFields($checkoutFields);

    /**
     * @return int|null
     */
    public function getMyshippingAccountId();

    /**
     * @param int $myshippingAccountId
     * @return $this
     */
    public function setMyshippingAccountId($myshippingAccountId);

    /**
     * @return int|null
     */
    public function getMyshippingCourierId();

    /**
     * @param int $myshippingCourierId
     * @return $this
     */
    public function setMyshippingCourierId($myshippingCourierId);

    /**
     * @return string|null
     */
    public function getMyshippingCourierMethod();

    /**
     * @param string $myshippingCourierMethod
     * @return $this
     */
    public function setMyshippingCourierMethod($myshippingCourierMethod);

    /**
     * @return string|null
     */
    public function getMyshippingAccount();

    /**
     * @param string $myshippingAccount
     * @return $this
     */
    public function setMyshippingAccount($myshippingAccount);

    /**
     * @return bool|null
     */
    public function getMyshippingSave();

    /**
     * @param bool $myshippingSave
     * @return $this
     */
    public function setMyshippingSave($myshippingSave);
}
