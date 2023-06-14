<?php
namespace MageMaclean\MyShipping\Model\ResourceModel\Myshipping;

class Quote extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('myshipping_quote', 'id');
    }
}
