<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Quote;

use Cart2Quote\Quotation\Model\Quote;

/**
 * Class Totals
 *
 * @package Cart2Quote\Quotation\Block\Quote
 */
class Totals extends \Magento\Sales\Block\Order\Totals
{
    const C2Q_GEN = 'cart2quote_advanced/general/';
    const XML_PATH_CART2QUOTE_QUOTATION_GLOBAL_SHOW_QUOTE_ADJUSTMENT = self::C2Q_GEN . 'show_quote_adjustment';

    /**
     * @var Quote|null
     */
    protected $_quote = null;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Quote|null
     */
    protected $_source = null;

    /**
     * @var \Cart2Quote\Quotation\Helper\Data
     */
    protected $cart2QuoteHelper;

    /**
     * @var \Magento\Weee\Helper\Data
     */
    protected $weeeHelper;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $pricingHelper;

    /**
     * Totals constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Cart2Quote\Quotation\Helper\Data $cart2QuoteHelper
     * @param \Magento\Weee\Helper\Data $weeeHelper
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Cart2Quote\Quotation\Helper\Data $cart2QuoteHelper,
        \Magento\Weee\Helper\Data $weeeHelper,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        array $data = []
    ) {
        $this->cart2QuoteHelper = $cart2QuoteHelper;
        $this->weeeHelper = $weeeHelper;
        $this->pricingHelper = $pricingHelper;
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct(
            $context,
            $registry,
            $data
        );
    }

    /**
     * Format total value based on quote currency
     *
     * @param   \Magento\Framework\DataObject $total
     * @return  string
     */
    public function formatValue($total)
    {
        if (!$total->getIsFormated()) {
            return $this->getQuote()->formatPrice($total->getValue());
        }

        return $total->getValue();
    }

    /**
     * Get quote object
     *
     * @return Quote
     */
    public function getQuote()
    {
        if ($this->_quote === null) {
            if ($this->hasData('quote')) {
                $this->_quote = $this->_getData('quote');
            } elseif ($this->_coreRegistry->registry('current_quote')) {
                $this->_quote = $this->_coreRegistry->registry('current_quote');
            } elseif ($this->getParentBlock()->getQuote()) {
                $this->_quote = $this->getParentBlock()->getQuote();
            }
        }

        return $this->_quote;
    }

    /**
     * Set quote
     *
     * @param Quote $quote
     * @return $this
     */
    public function setQuote($quote)
    {
        $this->_quote = $quote;
        return $this;
    }

    /**
     * Get totals array for visualization
     *
     * @param array|null $area
     * @return array
     */
    public function getTotals($area = null)
    {
        $totals = [];
        if ($area === null) {
            $totals = $this->_totals;
        } else {
            $area = (string)$area;
            foreach ($this->_totals as $total) {
                $totalArea = (string)$total->getArea();
                if ($totalArea == $area) {
                    $totals[] = $total;
                }
            }
        }
        return $totals;
    }

    /**
     * Check disabled product comment field
     *
     * @return bool
     */
    public function isProductRemarkDisabled()
    {
        return $this->cart2QuoteHelper->isProductRemarkDisabled();
    }

    /**
     * Has Optional Products
     *
     * @return bool
     */
    public function hasOptionalProducts()
    {
        return $this->getParentBlock() &&
            $this->getParentBlock()->hasOptionalProducts();
    }

    /**
     * Initialize quote totals array
     *
     * @return $this
     */
    protected function _initTotals()
    {
        /** @var \Cart2Quote\Quotation\Model\Quote $source */
        $source = $this->getSource();
        $this->_totals = [];
        $showSubtotal = false;

        switch ($this->_scopeConfig->getValue(self::XML_PATH_CART2QUOTE_QUOTATION_GLOBAL_SHOW_QUOTE_ADJUSTMENT)) {
            case '1':
                $showSubtotal = true;
                break;
            case '2':
                $showSubtotal = $source->getOriginalSubtotal()
                    && ($source->getSubtotal() - $source->getOriginalSubtotal()) != 0;
                break;
        }

        //calculate WEEE
        $weeeTotal = 0;
        if ($this->weeeHelper->includeInSubtotal($source->getStore())) {
            $items = $source->getItemsCollection();
            foreach ($items as $item) {
                $weeeTotal += (double)$item->getWeeeTaxAppliedRowAmount();
            }
        }

        if ($showSubtotal) {
            $this->_totals['original_subtotal'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'original_subtotal',
                    'value' => $this->getStoreCurrencyValue($source->getOriginalSubtotal()),
                    'label' => __('Original Subtotal')
                ]
            );

            $this->_totals['quote_adjustment'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'quote_adjustment',
                    'value' => ($this->getStoreCurrencyValue($source->getQuoteAdjustment())),
                    'label' => __('Quote Discount')
                ]
            );
        }

        $this->_totals['subtotal'] = new \Magento\Framework\DataObject(
            [
                'code' => 'subtotal',
                'value' => $this->getStoreCurrencyValue($source->getSubtotal()),
                'label' => __('Subtotal')
            ]
        );

        /**
         * Add shipping
         */
        if (!$source->getIsVirtual() && ((double)$source->getShippingAmount() || $source->getShippingDescription())) {
            $this->_totals['shipping'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'shipping',
                    'field' => 'shipping_amount',
                    'value' => $this->getStoreCurrencyValue($this->getSource()->getShippingAmount()),
                    'label' => __('Shipping & Handling'),
                ]
            );
        }

        /**
         * Add discount
         */
        if ((double)$this->getSource()->getDiscountAmount() != 0) {
            if ($this->getSource()->getDiscountDescription()) {
                $discountLabel = __('Discount (%1)', $source->getDiscountDescription());
            } else {
                $discountLabel = __('Discount');
            }
            $this->_totals['discount'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'discount',
                    'field' => 'discount_amount',
                    'value' => $this->getStoreCurrencyValue($source->getDiscountAmount()),
                    'label' => $discountLabel,
                ]
            );
        }

        if ($weeeTotal > 0) {
            $this->_totals['weee'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'weee',
                    'value' => $weeeTotal,
                    'label' => __('FPT')
                ]
            );
        }

        $this->_totals['grand_total'] = new \Magento\Framework\DataObject(
            [
                'code' => 'grand_total',
                'field' => 'grand_total',
                'strong' => true,
                'value' => $this->getQuote()->formatPrice($source->getGrandTotal()),
                'label' => __('Grand Total'),
            ]
        );

        /**
         * Base grandtotal
         */
        if ($this->getQuote()->isCurrencyDifferent()) {
            $this->_totals['base_grandtotal'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'base_grandtotal',
                    'value' => $this->getQuote()->formatBasePrice($source->getBaseGrandTotal()),
                    'label' => __("Grand Total in %1", $this->getQuote()->getCurrency()->getStoreCurrencyCode()),
                    'is_formated' => true,
                ]
            );
        }

        return $this;
    }

    /**
     * @param string $value
     * @return float|int
     * @throws \Exception
     */
    public function getStoreCurrencyValue($value)
    {
        return $this->pricingHelper->currencyByStore($value, $this->getQuote()->getStoreCurrencyCode());
    }

    /**
     * Get totals source object
     *
     * @return Quote
     */
    public function getSource()
    {
        if ($this->_source == null) {
            $quote = $this->getQuote();

            //make a clone to make sure that this merged object is never saved
            $tmpQuote = clone $this->getQuote();

            //a merged object is expected in the tax block
            if ($tmpQuote->isVirtual()) {
                $tmpQuote->addData($this->getQuote()->getBillingAddress()->getData());
            } else {
                $tmpQuote->addData($this->getQuote()->getShippingAddress()->getData());
            }

            //make sure we never lose the customer id
            if ($quote->getCustomerId() != $tmpQuote->getCustomerId()) {
                if ($quote->getCustomerId()) {
                    $tmpQuote->setCustomerId($quote->getCustomerId());
                }
            }

            $this->_source = $tmpQuote;
        }

        return $this->_source;
    }
}
