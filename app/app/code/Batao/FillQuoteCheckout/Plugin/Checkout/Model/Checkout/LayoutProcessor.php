<?php

namespace Batao\FillQuoteCheckout\Plugin\Checkout\Model\Checkout;

/**
 * Class LayoutProcessor
 * @package Batao\FillQuoteCheckout\Plugin\Checkout\Model\Checkout
 */
class LayoutProcessor
{
    /**
     * @var \Cart2Quote\Quotation\Model\Session
     */
    protected $quotationSession;

    /**
     * @var \Magento\Catalog\Helper\Product\Configuration
     */
    protected $productHelper;

    /**
     * LayoutProcessor constructor.
     *
     * @param \Cart2Quote\Quotation\Model\Session $quotationSession
     * @param \Magento\Catalog\Helper\Product\Configuration $productHelper
     */
    public function __construct(
        \Cart2Quote\Quotation\Model\Session $quotationSession,
        \Magento\Catalog\Helper\Product\Configuration $productHelper
    ) {
        $this->quotationSession = $quotationSession;
        $this->productHelper = $productHelper;
    }

    /**
     * @param \Cart2Quote\Quotation\Block\Quote\QuoteCheckout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Cart2Quote\Quotation\Block\Quote\QuoteCheckout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
        $quote = $this->getQuote();

        if (isset($quote)) {
            $items = $quote->getAllVisibleItems();
            $names = false;

            foreach ($items as $item) {
                if ($item->getSku() == "custom-request-form") {
                    $options = $this->productHelper->getOptions($item);
                    foreach ($options as $option) {
                        if ($option['label'] == "First Name") {
                            $names['firstname'] = $option['value'];
                            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['quotation-fields']['children']['guest-fieldset']['children']['firstname']['value'] = $option['value'];
                        }
                        if ($option['label'] == "Last Name") {
                            $names['lastname'] = $option['value'];
                            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['quotation-fields']['children']['guest-fieldset']['children']['lastname']['value'] = $option['value'];
                        }
                    }
                }
            }

            if (is_array($names)) {
                $this->quotationSession->addGuestFieldData($names);
            }
        }

        return $jsLayout;
    }

    /**
     * @return \Cart2Quote\Quotation\Model\Quote
     */
    protected function getQuote()
    {
        return $this->quotationSession->getQuote();
    }
}