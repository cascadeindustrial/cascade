<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Plugin\Magento\Backend\Block;

/**
 * Class Widget
 * @package Cart2Quote\Quotation\Plugin\Magento\Backend\Block
 */
class Widget
{
    /**
     * Function after getButtonHtml to set span or not
     * @param \Magento\Backend\Block\Widget $subject
     * @param string $result
     * @param string $label
     * @param string $onclick
     * @param string $class
     * @param string|null $buttonId
     * @param array $dataAttr
     * @param bool $labelInSpan
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterGetButtonHtml(
        \Magento\Backend\Block\Widget $subject,
        $result,
        $label,
        $onclick,
        $class = '',
        $buttonId = null,
        $dataAttr = [],
        $labelInSpan = true
    ) {
        return $subject->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            ['label' => $label, 'onclick' => $onclick, 'class' => $class, 'type' => 'button', 'id' => $buttonId]
        )->setDataAttribute(
            $dataAttr
        )->setLabelInSpan(
            $labelInSpan
        )->toHtml();
    }
}
