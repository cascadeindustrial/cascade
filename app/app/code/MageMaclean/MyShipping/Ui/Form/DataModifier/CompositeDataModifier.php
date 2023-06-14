<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui\Form\DataModifier;

use MageMaclean\MyShipping\Ui\Form\DataModifierInterface;
use Magento\Framework\Model\AbstractModel;

class CompositeDataModifier implements DataModifierInterface
{
    /**
     * @var DataModifierInterface[]
     */
    private $modifiers;

    /**
     * CompositeDataModifier constructor.
     * @param DataModifierInterface[] $modifiers
     */
    public function __construct(array $modifiers)
    {
        foreach ($modifiers as $modifier) {
            if (!($modifier instanceof DataModifierInterface)) {
                throw new \InvalidArgumentException(
                    "Form data modifier must implemenet " . DataModifierInterface::class
                );
            }
        }
        $this->modifiers = $modifiers;
    }

    /**
     * @param AbstractModel $model
     * @param array $data
     * @return array
     */
    public function modifyData(AbstractModel $model, array $data): array
    {
        foreach ($this->modifiers as $modifier) {
            $data = $modifier->modifyData($model, $data);
        }
        return $data;
    }
}
