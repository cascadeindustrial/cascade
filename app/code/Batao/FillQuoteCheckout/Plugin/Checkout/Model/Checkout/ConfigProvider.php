<?php

namespace Batao\FillQuoteCheckout\Plugin\Checkout\Model\Checkout;

/**
 * Class LayoutProcessor
 * @package Batao\FillQuoteCheckout\Plugin\Checkout\Model\Checkout
 */
class ConfigProvider
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
     * @param \Magento\Checkout\Model\DefaultConfigProvider $subject
     * @param $result
     * @return array
     */
    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        $result
    ) {
        $quote = $this->getQuote();

        if (isset($quote)) {
            $items = $quote->getAllVisibleItems();

            foreach ($items as $item) {
                if ($item->getSku() == "custom-request-form") {
                    $options = $this->productHelper->getOptions($item);
                    foreach ($options as $option) {
                        if ($option['label'] == "Email Address") {
                            if (is_array($result)) {
                                $result['validatedEmailValue'] = $option['value'];;
                            }
                            return $result;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return \Cart2Quote\Quotation\Model\Quote
     */
    protected function getQuote()
    {
        return $this->quotationSession->getQuote();
    }
}