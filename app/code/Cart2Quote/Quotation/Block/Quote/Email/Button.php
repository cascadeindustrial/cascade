<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */


/**
 * Quotation Email quote Button
 */

namespace Cart2Quote\Quotation\Block\Quote\Email;

use Cart2Quote\Quotation\Model\Quote;

/**
 * Class Button
 *
 * @package Cart2Quote\Quotation\Block\Quote\Email
 */
class Button extends \Magento\Framework\View\Element\Template
{
    /**
     * Data Helper
     *
     * @var \Cart2Quote\Quotation\Helper\Data
     */
    protected $quotationHelper;

    /**
     * URL builder
     *
     * @var \Magento\Framework\Url
     */
    protected $url;

    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * Button constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Cart2Quote\Quotation\Helper\Data $quotationHelper
     * @param \Magento\Framework\Url $url
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Cart2Quote\Quotation\Helper\Data $quotationHelper,
        \Magento\Framework\Url $url,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->url = $url;
        $this->quotationHelper = $quotationHelper;
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Get Url for checkout button
     * - return # to the href for email preview
     *
     * @return string
     */
    public function getUrlForCheckoutButton()
    {
        if ($this->getQuote()) {
            /** @var Quote $quote */
            $quote = $this->getQuote();
            $route = $this->getRoute($quote);
            $params = ['quote_id' => $quote->getQuoteId()];

            if ($this->isAutoLoginEnabled()) {
                $params['hash'] = $quote->getUrlHash();
            }

            return $this->url->getUrl($route, $params);
        }

        return '#';
    }

    /**
     * Returns the url for the more options controller
     *  - return # to the href for email preview
     *
     * @return string
     */
    public function getMoreOptionsUrl()
    {
        if ($this->getQuote()) {
            /** @var Quote $quote */
            $quote = $this->getQuote();
            $route = 'quotation/quote/checkout_moreoptions';
            $params = ['quote_id' => $quote->getQuoteId()];
            if ($this->isAutoLoginEnabled()) {
                $params['hash'] = $quote->getUrlHash();
            }

            return $this->url->getUrl($route, $params);
        }

        return '#';
    }

    /**
     * Get route depending on the checkout being enabled and the customer being a guest.
     *
     * @param Quote $quote
     * @return string
     */
    private function getRoute(Quote $quote)
    {
        $route = 'quotation/quote/checkout_customer';
        if ($this->isCheckoutDisabled()) {
            $route = 'quotation/quote_checkout/acceptwithoutcheckout';
        } elseif ($quote->getCustomerIsGuest()) {
            $route = 'quotation/quote/checkout_guest';
        }

        return $route;
    }

    /**
     * Get the Label for the button on the proposal email
     *
     * @return string
     */
    public function getFrontendButtonLabel()
    {
        $buttonLabel = __('Proceed to checkout');
        if ($this->isCheckoutDisabled()) {
            $buttonLabel = __('Accept Quotation');
        }

        return $buttonLabel;
    }

    /**
     * Check enabled auto login
     *
     * @return bool
     */
    public function isAutoLoginEnabled()
    {
        return $this->quotationHelper->isAutoLoginEnabled();
    }

    /**
     * Check disabled checkout
     *
     * @return bool
     */
    public function isCheckoutDisabled()
    {
        return $this->quotationHelper->isCheckoutDisabled();
    }

    /**
     * Returns true if customer is a guest user
     *
     * @return bool
     */
    public function customerIsGuest()
    {
        $quote = $this->getQuote();
        if (isset($quote)) {
            return (bool)$quote->getCustomerIsGuest();
        }

        return false;
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
