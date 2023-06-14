<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Adminhtml\Quote\Ajax;

/**
 * Class AbstractCoupon
 *
 * @package Cart2Quote\Quotation\Controller\Adminhtml\Quote\Ajax
 */
abstract class AbstractCoupon extends AbstractAction
{
    /**
     * @var \Cart2Quote\Quotation\Helper\QuoteRule
     */
    protected $quoteRule;

    /**
     * AbstractCoupon constructor.
     *
     * @param \Cart2Quote\Quotation\Helper\QuoteRule $quoteRule
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param \Magento\Store\Model\Store $store
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Translate\InlineInterface $translateInline
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Cart2Quote\Quotation\Helper\Data $helperData
     * @param \Cart2Quote\Quotation\Model\QuoteFactory $quoteFactory
     * @param \Cart2Quote\Quotation\Model\ResourceModel\Status\Collection $statusCollection
     * @param \Cart2Quote\Quotation\Model\Admin\Quote\Create $quoteCreate
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Cart2Quote\Quotation\Helper\Cloning $cloningHelper
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Backend\Model\Session\Quote $backendQuoteSession
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\GiftMessage\Model\Save $giftMessageSave
     * @param \Magento\Framework\Json\Helper\Data $jsonDataHelper
     */
    public function __construct(
        \Cart2Quote\Quotation\Helper\QuoteRule $quoteRule,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Store\Model\Store $store,
        \Magento\Framework\Escaper $escaper,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Cart2Quote\Quotation\Helper\Data $helperData,
        \Cart2Quote\Quotation\Model\QuoteFactory $quoteFactory,
        \Cart2Quote\Quotation\Model\ResourceModel\Status\Collection $statusCollection,
        \Cart2Quote\Quotation\Model\Admin\Quote\Create $quoteCreate,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Cart2Quote\Quotation\Helper\Cloning $cloningHelper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Backend\Model\Session\Quote $backendQuoteSession,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\GiftMessage\Model\Save $giftMessageSave,
        \Magento\Framework\Json\Helper\Data $jsonDataHelper
    ) {
        parent::__construct(
            $json,
            $customerRepositoryInterface,
            $store,
            $escaper,
            $context,
            $coreRegistry,
            $fileFactory,
            $translateInline,
            $resultPageFactory,
            $resultJsonFactory,
            $resultLayoutFactory,
            $resultRawFactory,
            $helperData,
            $quoteFactory,
            $statusCollection,
            $quoteCreate,
            $scopeConfig,
            $cloningHelper,
            $logger,
            $backendQuoteSession,
            $productHelper,
            $giftMessageSave,
            $jsonDataHelper
        );
        $this->quoteRule = $quoteRule;
    }
}