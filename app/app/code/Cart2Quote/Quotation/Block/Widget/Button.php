<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Widget;

/**
 * Class Button
 * @package Cart2Quote\Quotation\Plugin\Magento\Backend\Block\Widget
 */
class Button extends \Magento\Backend\Block\Widget\Button
{
    /**
     * @var bool
     */
    private $labelInSpan;

    /**
     * Define block template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Cart2Quote_Quotation::widget/button.phtml');
    }

    /**
     * Function to set labelInSpan boolean
     * @param bool $labelInSpan
     * @return $this
     */
    public function setLabelInSpan($labelInSpan)
    {
        $this->labelInSpan = $labelInSpan;
        return $this;
    }

    /**
     * Function to get labelInSpan boolean
     * @return bool
     */
    public function getLabelInSpan()
    {
        return $this->labelInSpan;
    }
}
