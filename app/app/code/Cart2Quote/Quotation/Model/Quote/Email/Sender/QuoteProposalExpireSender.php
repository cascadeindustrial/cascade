<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Model\Quote\Email\Sender;

/**
 * Class QuoteProposalExpireSender
 */
class QuoteProposalExpireSender extends Sender
{
    use \Cart2Quote\Features\Traits\Model\Quote\Email\Sender\QuoteProposalExpireSender {
    }

    /**
     * QuoteProposalExpireSender constructor
     *
     * @param \Magento\Sales\Model\Order\Email\Container\Template $templateContainer
     * @param \Magento\Sales\Model\Order\Email\Container\Container $identityContainer
     * @param \Cart2Quote\Quotation\Model\Quote\Email\SenderBuilderFactory $senderBuilderFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Cart2Quote\Quotation\Model\Quote\Address\Renderer $addressRenderer
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig
     * @param \Cart2Quote\Quotation\Model\Quote\Pdf\Quote $pdfModel
     * @param string $sendEmailIdentifier
     * @param string $emailSentIdentifier
     */
    public function __construct(
        \Magento\Sales\Model\Order\Email\Container\Template $templateContainer,
        \Magento\Sales\Model\Order\Email\Container\Container $identityContainer,
        \Cart2Quote\Quotation\Model\Quote\Email\SenderBuilderFactory $senderBuilderFactory,
        \Psr\Log\LoggerInterface $logger,
        \Cart2Quote\Quotation\Model\Quote\Address\Renderer $addressRenderer,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
        \Cart2Quote\Quotation\Model\Quote\Pdf\Quote $pdfModel,
        $sendEmailIdentifier = \Cart2Quote\Quotation\Api\Data\QuoteInterface::SEND_PROPOSAL_EXPIRED_EMAIL,
        $emailSentIdentifier = \Cart2Quote\Quotation\Api\Data\QuoteInterface::PROPOSAL_EXPIRED_EMAIL_SENT
    ) {
        parent::__construct(
            $templateContainer,
            $identityContainer,
            $senderBuilderFactory,
            $logger,
            $addressRenderer,
            $eventManager,
            $globalConfig,
            $pdfModel,
            $sendEmailIdentifier,
            $emailSentIdentifier
        );
    }
}
