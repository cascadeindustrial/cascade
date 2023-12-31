<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\ViewModel\Formatter;

use Magento\Framework\View\Element\Block\ArgumentInterface;

interface FormatterInterface extends ArgumentInterface
{
    /**
     * @param $value
     * @param array $arguments
     * @return string
     */
    public function formatHtml($value, $arguments = []): string;
}
