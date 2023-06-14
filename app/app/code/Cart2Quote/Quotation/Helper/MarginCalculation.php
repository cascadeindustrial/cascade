<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Class MarginCalculation
 *
 * @package Cart2Quote\Quotation\Helper
 */
class MarginCalculation extends AbstractHelper
{
    /**
     * @var \Cart2Quote\Quotation\Model\Quote
     */
    private $quote;

    /**
     * MarginCalculation constructor.
     *
     * @param Context $context
     * @param \Cart2Quote\Quotation\Model\Quote $quote
     */
    public function __construct(
        Context $context,
        \Cart2Quote\Quotation\Model\Quote $quote
    ) {
        parent::__construct($context);
        $this->quote = $quote;
    }

    /**
     * Caclulate cost percentage
     *
     * @param float $price
     * @param float $cost
     * @return float
     */
    public function calculatePercentage($price, $cost)
    {
        if ($price == $cost || $price == 0) {
            return 0.00;
        }

        return round((($price - $cost) / $price) * 100, 1);
    }

    /**
     * Calculate margin value
     *
     * @param $price
     * @param $cost
     * @return string
     */
    public function calculateValue($price, $cost)
    {
        return $this->quote->formatPrice($price - $cost);
    }

    /**
     * Get item margin
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return float | null
     */
    public function itemMargin(\Magento\Quote\Model\Quote\Item $item)
    {
        $priceAndCost = $this->getItemPriceAndCost($item);
        if (isset($priceAndCost)) {

            /**
             * If cost is not known, no GPMargin is calculated
             */
            if ($priceAndCost['cost'] == null) {
                return null;
            }

            return $this->calculatePercentage($priceAndCost['price'], $priceAndCost['cost']);
        }
    }

    /**
     * Get item price and cost
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return array
     */
    public function getItemPriceAndCost(\Magento\Quote\Model\Quote\Item $item)
    {
        $tierItem = $item->getTierItem();
        $price = $tierItem->getCustomPrice();
        if ($price > 0) {
            if ($item['no_discount'] == false) {
                $price *= ((100 - $item['discount_percent']) / 100);
            }
            $cost = $item->getProduct()->getCost();

            if ($tierItem->getBaseCost()) {
                $cost = $tierItem->getBaseCost();
            }

            $priceAndCost = [
                'price' => $price,
                'cost'  => $cost
                ];

            return $priceAndCost;
        }
    }

    /**
     * Get item margin value
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return string|null
     */
    public function itemMarginValue(\Magento\Quote\Model\Quote\Item $item)
    {
        $priceAndCost = $this->getItemPriceAndCost($item);
        if (isset($priceAndCost)) {

            /**
             * If cost is not known, no GPMargin is calculated
             */
            if ($priceAndCost['cost'] <= 0) {
                return null;
            }

            return $this->calculateValue($priceAndCost['price'], $priceAndCost['cost']);
        }
    }
}
