<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui\Form\DataModifier;

use MageMaclean\MyShipping\Ui\Form\DataModifierInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Serialize\Serializer\Json;

class DynamicRows implements DataModifierInterface
{
    /**
     * @var Json
     */
    private $serializer;
    /**
     * @var array
     */
    private $fields;

    /**
     * DynamicRows constructor.
     * @param Json $serializer
     * @param array $fields
     */
    public function __construct(Json $serializer, array $fields)
    {
        $this->serializer = $serializer;
        $this->fields = $fields;
    }

    /**
     * @param AbstractModel $model
     * @param array $data
     * @return array
     */
    public function modifyData(AbstractModel $model, array $data): array
    {
        foreach ($this->fields as $field) {
            if (array_key_exists($field, $data) && !is_array($data[$field])) {
                try {
                    $data[$field] = $this->serializer->unserialize($data[$field]);
                } catch (\Exception $e) {
                    $data[$field] = [];
                }
            }
        }
        return $data;
    }
}
