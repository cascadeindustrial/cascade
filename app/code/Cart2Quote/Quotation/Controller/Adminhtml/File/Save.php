<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Adminhtml\File;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Save
 * @package Cart2Quote\Quotation\Controller\Adminhtml\File
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Cart2Quote\Quotation\Model\Quote\File
     */
    protected $fileModel;

    /**
     * @var \Magento\Framework\Url\DecoderInterface
     */
    protected $urlDecoder;

    /**
     * Save constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Cart2Quote\Quotation\Model\Quote\File $fileModel
     * @param \Magento\Framework\Url\DecoderInterface $urlDecoder
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Cart2Quote\Quotation\Model\Quote\File $fileModel,
        \Magento\Framework\Url\DecoderInterface $urlDecoder
    ) {
        parent::__construct($context);
        $this->fileModel = $fileModel;
        $this->urlDecoder = $urlDecoder;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        try {
            $addedFileNames = [];
            $filesCustomer = $this->getRequest()->getPost('upload_customer');
            $filesEmail = $this->getRequest()->getPost('upload_email');
            $quoteId = $this->getRequest()->getParam('quote_id');

            if (is_array($filesCustomer)) {
                array_push(
                    $addedFileNames,
                    $this->fileModel->addTo(
                        $filesCustomer,
                        $quoteId,
                        $this->fileModel::CUSTOMER_FOLDER
                    )
                );
            }

            if (is_array($filesEmail)) {
                array_push(
                    $addedFileNames,
                    $this->fileModel->addTo(
                        $filesEmail,
                        $quoteId,
                        $this->fileModel::EMAIL_FOLDER
                    )
                );
            }

            foreach ($addedFileNames as $message) {
                foreach ($message as $messageText) {
                    $this->messageManager->addSuccessMessage($messageText);
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }
}
