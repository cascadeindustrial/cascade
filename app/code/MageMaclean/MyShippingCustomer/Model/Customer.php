<?php
namespace MageMaclean\MyShippingCustomer\Model;

class Customer extends \Magento\Framework\Model\AbstractModel implements \MageMaclean\MyShippingCustomer\Api\Data\CustomerInterface
{
    /**
     * Init resource class
     */
    protected function _construct()
    {
        $this->_init(\MageMaclean\MyShippingCustomer\Model\ResourceModel\Customer::class);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId() : int
    {
        return (int)$this->getData('customer_id');
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(int $customerId)
    {
        return $this->setData('customer_id', $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getMyshippingEnabled() : bool
    {
        return (bool)$this->getData('myshipping_enabled');
    }

    /**
     * @inheritDoc
     */
    public function setMyshippingEnabled(bool $value)
    {
        return $this->setData('myshipping_enabled', $value);
    }

    /**
     * @inheritDoc
     */
    public function getMyshippingNewEnabled() : bool
    {
        return (bool)$this->getData('myshipping_new_enabled');
    }

    /**
     * @inheritDoc
     */
    public function setMyshippingNewEnabled(bool $value)
    {
        return $this->setData('myshipping_new_enabled', $value);
    }
}
