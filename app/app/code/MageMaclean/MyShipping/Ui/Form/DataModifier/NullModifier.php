<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui\Form\DataModifier;

use MageMaclean\MyShipping\Ui\Form\DataModifierInterface;
use Magento\Framework\Model\AbstractModel;

class NullModifier implements DataModifierInterface
{
    /**
     * @param AbstractModel $model
     * @param array $data
     * @return array
     */
    public function modifyData(AbstractModel $model, array $data): array
    {
        return $data;
    }
}
