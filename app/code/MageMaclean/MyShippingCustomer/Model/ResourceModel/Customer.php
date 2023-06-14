<?php
namespace MageMaclean\MyShippingCustomer\Model\ResourceModel;

class Customer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('myshipping_customer', 'id');
    }
}
