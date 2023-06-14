<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Quote\View;

/**
 * Class PrintQuote
 *
 * @package Cart2Quote\Quotation\Controller\Quote\View
 */
class PrintQuote extends \Cart2Quote\Quotation\Controller\AbstractController\View
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Cart2Quote\Quotation\Model\QuoteFactory
     */
    private $quotationFactory;

    /**
     * @var \Cart2Quote\Quotation\Model\Session
     */
    private $quoteSession;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Cart2Quote\Quotation\Controller\AbstractController\QuoteLoaderInterface
     */
    protected $quoteLoader;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Cart2Quote\Quotation\Helper\Pdf\Download
     */
    protected $downloadHelper;

    /**
     * @var \Cart2Quote\Quotation\Model\Quote\Pdf\Quote
     */
    protected $pdfModel;

    /**
     * PrintQuote constructor.
     *
     * @param \Cart2Quote\Quotation\Model\Quote\Pdf\Quote $pdfModel
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Cart2Quote\Quotation\Model\Session $quoteSession
     * @param \Cart2Quote\Quotation\Model\QuoteFactory $quotationFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Cart2Quote\Quotation\Controller\AbstractController\QuoteLoaderInterface $quoteLoader
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Cart2Quote\Quotation\Helper\Pdf\Download $downloadHelper
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Cart2Quote\Quotation\Model\Quote\Pdf\Quote $pdfModel,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Cart2Quote\Quotation\Model\Session $quoteSession,
        \Cart2Quote\Quotation\Model\QuoteFactory $quotationFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Cart2Quote\Quotation\Controller\AbstractController\QuoteLoaderInterface $quoteLoader,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Cart2Quote\Quotation\Helper\Pdf\Download $downloadHelper,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context, $quoteLoader, $resultPageFactory);
        $this->pdfModel = $pdfModel;
        $this->checkoutSession = $checkoutSession;
        $this->quotationFactory = $quotationFactory;
        $this->quoteSession = $quoteSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->quoteLoader = $quoteLoader;
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->downloadHelper = $downloadHelper;
    }

    /**
     * Execute (controller entrypoint)
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $result = $this->quoteLoader->load($this->_request);
        if ($result instanceof \Magento\Framework\Controller\ResultInterface) {
            return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRefererUrl());
        }

        $quote = $this->registry->registry('current_quote');

        //write pdf to var/export_quotation/pdf directory
        $ds = DIRECTORY_SEPARATOR;
        $storageDir = $this->downloadHelper->getFileHelper()->getStorageDir();
        $filePath = sprintf(
            'export_quotation' . $ds . 'pdf' . $ds . '%s.pdf',
            $quote->getIncrementId()
        );
        $filePath = $storageDir . $ds . $filePath;
        if (!file_exists($filePath)) {
            $filePath = $this->pdfModel->createQuotePdf([$quote]);
        }
        $this->downloadHelper->setResource($filePath, \Magento\Downloadable\Helper\Download::LINK_TYPE_FILE);
        $fileName = $this->downloadHelper->getFilename();
        $contentType = $this->downloadHelper->getContentType();
        $contentDisposition = 'attachment';

        $this->getResponse()->setHttpResponseCode(200)
            ->setHeader('target', '_blank', true)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true);

        $fileSize = $this->downloadHelper->getFileSize();
        if ($fileSize) {
            $this->getResponse()->setHeader('Content-Length', $fileSize);
        }

        $this->getResponse()->setHeader('Content-Disposition', $contentDisposition . '; filename=' . $fileName);
        $this->getResponse()->clearBody();
        $this->getResponse()->sendHeaders();

        //output PDF
        $this->downloadHelper->output();
    }
}
