<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\ShippingMethodInterface
 */
class ShippingMethodExtension extends \Magento\Framework\Api\AbstractSimpleObject implements ShippingMethodExtensionInterface
{
    /**
     * @return \MageMaclean\MyShipping\Api\Data\CourierOptionInterface[]|null
     */
    public function getMyshippingCouriers()
    {
        return $this->_get('myshipping_couriers');
    }

    /**
     * @param \MageMaclean\MyShipping\Api\Data\CourierOptionInterface[] $myshippingCouriers
     * @return $this
     */
    public function setMyshippingCouriers($myshippingCouriers)
    {
        $this->setData('myshipping_couriers', $myshippingCouriers);
        return $this;
    }

    /**
     * @return \MageMaclean\MyShipping\Api\Data\CourierMethodOptionInterface[]|null
     */
    public function getMyshippingCourierMethods()
    {
        return $this->_get('myshipping_courier_methods');
    }

    /**
     * @param \MageMaclean\MyShipping\Api\Data\CourierMethodOptionInterface[] $myshippingCourierMethods
     * @return $this
     */
    public function setMyshippingCourierMethods($myshippingCourierMethods)
    {
        $this->setData('myshipping_courier_methods', $myshippingCourierMethods);
        return $this;
    }
}
