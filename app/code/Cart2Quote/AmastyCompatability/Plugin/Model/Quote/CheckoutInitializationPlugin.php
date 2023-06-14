<?php

namespace Cart2Quote\AmastyCompatability\Plugin\Model\Quote;

use Amasty\Checkout\Model\Config;
use Amasty\Checkout\Model\FieldsDefaultProvider;
use Amasty\Checkout\Model\Quote\CheckoutInitialization;
use Amasty\Checkout\Model\Quote\Shipping;
use Magento\Framework\Webapi\ServiceOutputProcessor;
use Magento\Payment\Model\MethodList;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Class CheckoutInitializationPlugin
 * @package Cart2Quote\AmastyCompatability\Plugin\Model\Quote
 */
class CheckoutInitializationPlugin extends CheckoutInitialization
{
    /**
     * @var \Cart2Quote\Quotation\Model\Session
     */
    private $quotationSession;

    /**
     * CheckoutInitializationPlugin constructor.
     * @param Config $checkoutConfig
     * @param FieldsDefaultProvider $defaultProvider
     * @param ServiceOutputProcessor $outputProcessor
     * @param CartRepositoryInterface $quoteRepository
     * @param Shipping\AddressMethods $addressMethods
     * @param MethodList $paymentMethodList
     * @param CartTotalRepositoryInterface $cartTotalsRepository
     * @param \Cart2Quote\Quotation\Model\Session $quotationSession
     */
    public function __construct(
        Config $checkoutConfig,
        FieldsDefaultProvider $defaultProvider,
        ServiceOutputProcessor $outputProcessor,
        CartRepositoryInterface $quoteRepository,
        Shipping\AddressMethods $addressMethods,
        MethodList $paymentMethodList,
        CartTotalRepositoryInterface $cartTotalsRepository,
        \Cart2Quote\Quotation\Model\Session $quotationSession
    ) {
        parent::__construct(
            $checkoutConfig,
            $defaultProvider,
            $outputProcessor,
            $quoteRepository,
            $addressMethods,
            $paymentMethodList,
            $cartTotalsRepository
        );
        $this->quotationSession = $quotationSession;
    }

    /**
     * Loads in quote for quotation/quote page
     * @param CheckoutInitialization $subject
     * @param CartInterface $quote
     */
    public function beforeInitializeShipping(CheckoutInitialization $subject, CartInterface $quote)
    {
        $quoteId = $quote->getId();
        if (isset($quoteId)) {
            // do nothing
        } else {
            $quote = $this->quotationSession->getQuote();
        }

        return [$quote];
    }
}
