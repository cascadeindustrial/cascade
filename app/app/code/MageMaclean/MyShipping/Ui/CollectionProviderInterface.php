<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui;

interface CollectionProviderInterface
{
    /**
     * @return \MageMaclean\MyShipping\Model\ResourceModel\AbstractCollection
     */
    public function getCollection();
}
