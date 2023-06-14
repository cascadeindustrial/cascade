<?php
namespace Magento\Quote\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Quote\Api\Data\ShippingMethodInterface
 */
interface ShippingMethodExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \MageMaclean\MyShipping\Api\Data\CourierOptionInterface[]|null
     */
    public function getMyshippingCouriers();

    /**
     * @param \MageMaclean\MyShipping\Api\Data\CourierOptionInterface[] $myshippingCouriers
     * @return $this
     */
    public function setMyshippingCouriers($myshippingCouriers);

    /**
     * @return \MageMaclean\MyShipping\Api\Data\CourierMethodOptionInterface[]|null
     */
    public function getMyshippingCourierMethods();

    /**
     * @param \MageMaclean\MyShipping\Api\Data\CourierMethodOptionInterface[] $myshippingCourierMethods
     * @return $this
     */
    public function setMyshippingCourierMethods($myshippingCourierMethods);
}
