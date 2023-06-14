<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $options;
    /**
     * @var array
     */
    private $processed;

    /**
     * Options constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->processed === null) {
            $filteredOptions = array_filter(
                $this->options,
                function ($option) {
                    if (!is_array($option)) {
                        return false;
                    }
                    return array_key_exists('label', $option)
                    && array_key_exists('value', $option)
                    && (!array_key_exists('disabled', $option) || !$option['disabled']);
                }
            );
            $this->processed = array_values(
                array_map(
                    function ($option) {
                        return [
                            'label' => $option['label'],
                            'value' => $option['value']
                        ];
                    },
                    $filteredOptions
                )
            );
        }
        return $this->processed;
    }
}
