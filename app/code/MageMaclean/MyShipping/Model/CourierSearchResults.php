<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Model;

use MageMaclean\MyShipping\Api\Data\CourierSearchResultInterface;
use Magento\Framework\Api\SearchResults;

class CourierSearchResults extends SearchResults implements CourierSearchResultInterface
{

}
