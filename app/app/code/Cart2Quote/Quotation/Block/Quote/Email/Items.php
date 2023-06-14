<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */


/**
 * Quotation Email quote items
 */

namespace Cart2Quote\Quotation\Block\Quote\Email;

/**
 * Class Items
 *
 * @package Cart2Quote\Quotation\Block\Quote\Email
 */
class Items extends \Magento\Sales\Block\Items\AbstractItems
{
    /**
     * @var \Cart2Quote\Quotation\Helper\Data
     */
    protected $quotationHelper;

    /**
     * @var \Magento\GiftMessage\Helper\Message
     */
    protected $giftMessageHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Items constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Cart2Quote\Quotation\Helper\Data $quotationHelper
     * @param \Magento\GiftMessage\Helper\Message $giftMessageHelper
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Cart2Quote\Quotation\Helper\Data $quotationHelper,
        \Magento\GiftMessage\Helper\Message $giftMessageHelper,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->quotationHelper = $quotationHelper;
        $this->giftMessageHelper = $giftMessageHelper;
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Check disabled product comment field
     *
     * @return bool
     */
    public function isProductRemarkDisabled()
    {
        return $this->quotationHelper->isProductRemarkDisabled();
    }

    /**
     * Get sections form the quote
     *
     * @return array
     */
    public function getSections()
    {
        return $this->getQuote()->getSections();
    }

    /**
     * Check hide item price in request email configuration
     *
     * @return bool
     */
    public function hidePrice()
    {
        return $this->quotationHelper->isHideEmailRequestPrice();
    }

    /**
     * Getter for the gift message helper
     *
     * @return \Magento\GiftMessage\Helper\Message
     */
    public function getGiftMessageHelper()
    {
        return $this->giftMessageHelper;
    }

    /**
     * Retrieve quote model object
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        if ($this->hasQuote()) {
            return $this->getData('quote');
        }
        if ($this->coreRegistry->registry('current_quote')) {
            return $this->coreRegistry->registry('current_quote');
        }
        if ($this->coreRegistry->registry('quote')) {
            return $this->coreRegistry->registry('quote');
        }
        if ($this->coreRegistry->registry('c2qLastLoadedQuote')) {
            return $this->coreRegistry->registry('c2qLastLoadedQuote');
        }
        throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t get the quote instance right now.'));
    }
}
