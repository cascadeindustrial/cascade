<?php
declare(strict_types=1);

namespace MageMaclean\MyShipping\Model\ResourceModel\Courier;

use MageMaclean\MyShipping\Model\ResourceModel\Collection\StoreAwareAbstractCollection;

/**
 * @api
 */
class Collection extends StoreAwareAbstractCollection
{
    /**
     * @var string
     * phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore,PSR12.Classes.PropertyDeclaration.Underscore
     */
    protected $_idFieldName = 'courier_id';
    //phpcs: enable

    /**
     * Define resource model
     *
     * @return void
     * @codeCoverageIgnore
     * //phpcs:disable PSR2.Methods.MethodDeclaration.Underscore,PSR12.Methods.MethodDeclaration.Underscore
     */
    protected function _construct()
    {
        $this->_init(
            \MageMaclean\MyShipping\Model\Courier::class,
            \MageMaclean\MyShipping\Model\ResourceModel\Courier::class
        );
        $this->_map['fields']['store_id'] = 'store_table.store_id';
        $this->_map['fields']['courier_id'] = 'main_table.courier_id';
        //phpcs: enable
    }
}
