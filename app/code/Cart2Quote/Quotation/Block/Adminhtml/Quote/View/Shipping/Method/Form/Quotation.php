<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Shipping\Method\Form;

/**
 * Class Quotation
 *
 * @package Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Shipping\Method\Form
 */
class Quotation extends \Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Shipping\Method\Form
{

    /**
     * Get method title
     *
     * @return string
     */
    public function getMethodTitle()
    {
        if ($this->getRate()->getMethodTitle()) {
            $methodTitle = $this->getRate()->getMethodTitle();
        } else {
            $methodTitle = $this->getRate()->getMethodDescription();
        }

        return $this->escapeHtml($methodTitle);
    }

    /**
     * Get the shipping rate
     *
     * @return \Magento\Quote\Model\Quote\Address\Rate
     */
    public function getRate()
    {
        return $this->getData('rate');
    }

    /**
     * Get tax helper
     *
     * @return \Magento\Tax\Helper\Data
     */
    public function getTaxHelper()
    {
        return $this->getData('tax_helper');
    }

    /**
     * Set tax helper
     *
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @return $this
     */
    public function setTaxHelper(\Magento\Tax\Helper\Data $taxHelper)
    {
        $this->setData('tax_helper', $taxHelper);
        return $this;
    }

    /**
     * Set the shipping rate
     *
     * @param \Magento\Quote\Model\Quote\Address\Rate $rate
     * @return $this
     */
    public function setRate(\Magento\Quote\Model\Quote\Address\Rate $rate)
    {
        $this->setData('rate', $rate);
        return $this;
    }

    /**
     * Get the shipping code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->getData('code');
    }

    /**
     * Set the shipping code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->setData('code', $code);
        return $this;
    }

    /**
     * Get the shipping button property
     *
     * @return string
     */
    public function getRadioProperty()
    {
        return $this->getData('radio_property');
    }

    /**
     * Set the radio button property
     *
     * @param string $code
     * @return $this
     */
    public function setRadioProperty($code)
    {
        $this->setData('radio_property', $code);
        return $this;
    }
}
