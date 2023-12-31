<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Quote\Item\Renderer;

/**
 * Quote item render block
 */
class DefaultRenderer extends \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer
{
    /**
     * Quotation Helper
     *
     * @var \Cart2Quote\Quotation\Helper\Data
     */
    protected $quotationHelper;

    /**
     * @var \Cart2Quote\Quotation\Block\Quote\TierItem
     */
    protected $tierItemBlock;

    /**
     * DefaultRenderer constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory
     * @param \Cart2Quote\Quotation\Helper\Data $quotationHelper
     * @param \Cart2Quote\Quotation\Block\Quote\TierItem $tierItemBlock
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Cart2Quote\Quotation\Helper\Data $quotationHelper,
        \Cart2Quote\Quotation\Block\Quote\TierItem $tierItemBlock,
        array $data
    ) {
        $this->quotationHelper = $quotationHelper;
        $this->tierItemBlock = $tierItemBlock;
        parent::__construct(
            $context,
            $string,
            $productOptionFactory,
            $data
        );
    }

    /**
     * Check disable product comment field
     *
     * @return bool
     */
    public function isProductRemarkDisabled()
    {
        return $this->quotationHelper->isProductRemarkDisabled();
    }

    /**
     * Has Optional Products
     *
     * @return bool
     */
    public function hasOptionalProducts()
    {
        return $this->getParentBlock()
            && $this->getParentBlock()->getParentBlock()
            && $this->getParentBlock()->getParentBlock()->hasOptionalProducts();
    }

    /**
     * Check quote can accept
     *
     * @return bool
     */
    public function canAccept()
    {
        return $this->quotationHelper->canAccept($this->getQuote());
    }

    /**
     * Retrieve current quote model instance
     *
     * @return \Cart2Quote\Quotation\Model\Quote
     */
    public function getQuote()
    {
        return $this->getQuoteItem()->getQuote();
    }

    /**
     * Get the quote item
     *
     * @return array|null
     */
    public function getQuoteItem()
    {
        if ($this->getItem() instanceof \Magento\Quote\Model\Quote\Item) {
            return $this->getItem();
        } else {
            return $this->getItem()->getQuoteItem();
        }
    }

    /**
     * Get delete item url
     *
     * @param int $tierItemId
     * @return string
     */
    public function getDeleteUrl($tierItemId)
    {
        return $this->getUrl(
            'quotation/quote/deleteQuotationItem',
            [
                'id' => $tierItemId,
                'quote_id' => $this->getQuote()->getId()
            ]
        );
    }
}
