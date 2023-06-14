<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui\SaveDataProcessor;

use MageMaclean\MyShipping\Ui\SaveDataProcessorInterface;
use Magento\Framework\Serialize\Serializer\Json;

class DynamicRows implements SaveDataProcessorInterface
{
    /**
     * @var Json
     */
    private $serializer;
    /**
     * @var array
     */
    private $fields = [];
    /**
     * @var bool
     */
    private $strict;

    /**
     * DynamicRows constructor.
     * @param Json $serializer
     * @param array $fields
     */
    public function __construct(Json $serializer, array $fields, bool $strict)
    {
        $this->serializer = $serializer;
        $this->fields = $fields;
        $this->strict = $strict;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        foreach ($this->fields as $field) {
            if (!array_key_exists($field, $data) && $this->strict) {
                $data[$field] = [];
            }
            if (array_key_exists($field, $data) && is_array($data[$field])) {
                $data[$field] = $this->serializer->serialize($data[$field]);
            }
        }
        return $data;
    }
}
