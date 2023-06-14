<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui\SaveDataProcessor;

use MageMaclean\MyShipping\Ui\SaveDataProcessorInterface;

class NullProcessor implements SaveDataProcessorInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        return $data;
    }
}
