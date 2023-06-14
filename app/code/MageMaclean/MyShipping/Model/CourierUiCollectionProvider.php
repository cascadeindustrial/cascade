<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model;

use MageMaclean\MyShipping\Model\ResourceModel\Courier\CollectionFactory;
use MageMaclean\MyShipping\Ui\CollectionProviderInterface;

class CourierUiCollectionProvider implements CollectionProviderInterface
{
    /**
     * @var CollectionFactory
     */
    private $factory;

    /**
     * @param CollectionFactory $factory
     */
    public function __construct(CollectionFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getCollection()
    {
        return $this->factory->create();
    }
}
