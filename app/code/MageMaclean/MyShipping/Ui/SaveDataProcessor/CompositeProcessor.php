<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui\SaveDataProcessor;

use MageMaclean\MyShipping\Ui\SaveDataProcessorInterface;

class CompositeProcessor implements SaveDataProcessorInterface
{
    /**
     * @var SaveDataProcessorInterface[]
     */
    private $modifiers;

    /**
     * CompositeModifier constructor.
     * @param SaveDataProcessorInterface[] $modifiers
     */
    public function __construct(array $modifiers)
    {
        foreach ($modifiers as $modifier) {
            if (!($modifier instanceof SaveDataProcessorInterface)) {
                throw new \InvalidArgumentException(
                    "Data modifier must be instance of " . SaveDataProcessorInterface::class
                );
            }
        }
        $this->modifiers = $modifiers;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        foreach ($this->modifiers as $modifier) {
            $data = $modifier->modifyData($data);
        }
        return $data;
    }
}
