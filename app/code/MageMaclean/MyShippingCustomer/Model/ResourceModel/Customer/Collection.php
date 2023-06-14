<?php
namespace MageMaclean\MyShippingCustomer\Model\ResourceModel\Customer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            \MageMaclean\MyShippingCustomer\Model\Customer ::class,
            \MageMaclean\MyShippingCustomer\Model\ResourceModel\Customer::class
        );
    }
}
