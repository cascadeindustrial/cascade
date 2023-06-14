<?php
namespace MageMaclean\MyShipping\Model\ResourceModel\Account;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use MageMaclean\MyShipping\Model\ResourceModel\Account as ResourceModel;
use MageMaclean\MyShipping\Model\Account as Model;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}