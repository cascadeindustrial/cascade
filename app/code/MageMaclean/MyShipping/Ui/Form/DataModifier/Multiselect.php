<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui\Form\DataModifier;

use MageMaclean\MyShipping\Ui\Form\DataModifierInterface;
use Magento\Framework\Model\AbstractModel;

class Multiselect implements DataModifierInterface
{
    /**
     * @var array
     */
    private $fields;

    /**
     * Multiselect constructor.
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param AbstractModel $model
     * @param array $data
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function modifyData(AbstractModel $model, array $data): array
    {
        foreach ($this->fields as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }
            if ($data[$field] === null) {
                $data[$field] = [];
            }
            if (is_string($data[$field])) {
                $data[$field] = explode(',', $data[$field]);
            }
        }
        return $data;
    }
}