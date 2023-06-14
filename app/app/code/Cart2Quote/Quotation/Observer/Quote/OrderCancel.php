<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Observer\Quote;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class OrderCancel
 *
 * @package Cart2Quote\Quotation\Observer\Quote
 */
class OrderCancel implements ObserverInterface
{
    /**
     * Quotation factory
     *
     * @var \Cart2Quote\Quotation\Model\QuoteFactory
     */
    protected $quotationFactory;

    /**
     * Quote Repository
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * OrderCancel constructor
     *
     * @param \Cart2Quote\Quotation\Model\QuoteFactory $quotationFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        \Cart2Quote\Quotation\Model\QuoteFactory $quotationFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->quotationFactory = $quotationFactory;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Set the quote to canceled
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $order = $observer->getOrder();
        $quoteId = $order->getQuoteId();
        $quotationId = $this->getLinkedQuotation($quoteId);

        if (!empty($quotationId)) {
            $quotationQuote = $this->quotationFactory->create()->load($quotationId);
            $this->changeQuoteStatus($quotationQuote);
        }
    }

    /**
     * Change Cart2Quote quote status
     *
     * @param \Cart2Quote\Quotation\Model\Quote $quotationQuote
     * @throws \Exception
     */
    protected function changeQuoteStatus($quotationQuote)
    {
        $quotationQuote->setState(\Cart2Quote\Quotation\Model\Quote\Status::STATE_CANCELED)
            ->setStatus(\Cart2Quote\Quotation\Model\Quote\Status::STATUS_CANCELED)->save();
    }

    /**
     * Get linked quote number
     *
     * @param int $quoteId
     * @return int|null $linkedQuoteId
     */
    public function getLinkedQuotation($quoteId)
    {
        $quote = $this->quoteRepository->get($quoteId);
        $linkedQuoteId = $quote->getLinkedQuotationId();

        return $linkedQuoteId;
    }
}

