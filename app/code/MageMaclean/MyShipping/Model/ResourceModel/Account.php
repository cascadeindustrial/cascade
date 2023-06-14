<?php
namespace MageMaclean\MyShipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Account extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('myshipping_account', 'id');
    }
}