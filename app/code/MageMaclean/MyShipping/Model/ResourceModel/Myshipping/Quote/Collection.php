<?php
namespace MageMaclean\MyShipping\Model\ResourceModel\Myshipping\Quote;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            \MageMaclean\MyShipping\Model\Myshipping\Quote ::class,
            \MageMaclean\MyShipping\Model\ResourceModel\Myshipping\Quote::class
        );
    }
}
