<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Tab;


use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;

/**
 * Class PreviousQuotes
 *
 * @package Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Tab
 */
class PreviousQuotes extends \Magento\Backend\Block\Widget implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Cart2Quote\Quotation\Model\ResourceModel\Quote\Collection
     */
    protected $quotes;

    /**
     * @var \Cart2Quote\Quotation\Model\ResourceModel\Quote\CollectionFactory
     */
    protected $_quoteCollectionFactory;

    /**
     * @var \Cart2Quote\Quotation\Model\Quote\Config
     */
    protected $_quoteConfig;

    /**
     * Customer Model
     *
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var \Cart2Quote\Quotation\Model\QuoteRepository
     */
    protected $quotationRepository;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * PreviousQuotes constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Cart2Quote\Quotation\Model\QuoteRepository $quotationRepository
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Cart2Quote\Quotation\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory
     * @param \Cart2Quote\Quotation\Model\Quote\Config $quoteConfig
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\Customer $customer,
        \Cart2Quote\Quotation\Model\QuoteRepository $quotationRepository,
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Cart2Quote\Quotation\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory,
        \Cart2Quote\Quotation\Model\Quote\Config $quoteConfig,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->customer = $customer;
        $this->quotationRepository = $quotationRepository;
        $this->quoteRepository = $quoteRepository;
        $this->_quoteCollectionFactory = $quoteCollectionFactory;
        $this->_quoteConfig = $quoteConfig;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Check if tab can be shown
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Get Tab label
     */
    public function getTabLabel()
    {
        return __('Previous Quotes');
    }

    /**
     * Get Tab title
     */
    public function getTabTitle()
    {
        return __('Previous Quotes');
    }

    /**
     * Check if tab is hidden
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Get quote collection
     *
     * @return bool|\Cart2Quote\Quotation\Model\ResourceModel\Quote\Collection
     */
    public function getQuotes()
    {
        if (!$this->quotes) {
            $this->quotes = $this->_quoteCollectionFactory->create()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'customer_id',
                $this->customer->getId()
            )->addFieldToFilter(
                'is_quote',
                \Cart2Quote\Quotation\Model\Quote::IS_QUOTE
            )->addFieldToFilter(
                'status',
                ['in' => $this->_quoteConfig->getVisibleOnFrontStatuses()]
            )->addFieldToFilter(
                'cloned_quote',
                ['neq' => \Cart2Quote\Quotation\Model\Quote::ORIGINAL_QUOTE]
            )->setOrder(
                'quotation_created_at',
                'desc'
            );
        }

        return $this->quotes;
    }

    /**
     * Get quote view URL
     *
     * @param int $quoteId
     * @return string
     */
    public function getQuoteViewUrl($quoteId)
    {
        return $this->getUrl('quotation/quote/view', ['quote_id' => $quoteId]);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerName()
    {
        return $this->customer->getName();
    }

    /**
     * Retrieve formated price
     *
     * @param float $value
     * @return string
     */
    public function formatPrice($value)
    {
        return $this->priceCurrency->format(
            $value,
            true,
            \Magento\Framework\Pricing\PriceCurrencyInterface::DEFAULT_PRECISION,
            $this->getStore()
        );
    }
}
