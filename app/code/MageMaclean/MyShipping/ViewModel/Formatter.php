<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\ViewModel;

use MageMaclean\MyShipping\ViewModel\Formatter\FormatterInterface;
use Magento\Framework\Escaper;

class Formatter implements FormatterInterface
{
    /**
     * @var FormatterInterface[]
     */
    private $formatterMap;
    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * Formatter constructor.
     * @param FormatterInterface[] $formatterMap
     * @param Escaper $escaper
     */
    public function __construct(array $formatterMap, Escaper $escaper)
    {
        foreach ($formatterMap as $formatter) {
            if (!($formatter instanceof FormatterInterface)) {
                throw new \InvalidArgumentException("Formatter must implement " . FormatterInterface::class);
            }
        }
        $this->formatterMap = $formatterMap;
        $this->escaper = $escaper;
    }

    /**
     * @param $value
     * @param array $arguments
     * @return string
     */
    public function formatHtml($value, $arguments = []): string
    {
        $type = $arguments['type'] ?? null;
        return $type === null
            ? $this->escaper->escapeHtml($value)
            : $this->getFormatter($type)->formatHtml($value, $arguments);
    }

    /**
     * @param $type
     * @return FormatterInterface|null
     */
    private function getFormatter($type)
    {
        $formatter = $this->formatterMap[$type] ?? null;
        if ($formatter === null) {
            throw new \InvalidArgumentException("Missing formatter for type {$type}");
        }
        return $formatter;
    }
}
