<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Adminhtml\File;

/**
 * Class Remove
 * @package Cart2Quote\Quotation\Controller\Adminhtml\File
 */
class Remove extends \Magento\Framework\App\Action\Action
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
     * Remove constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Cart2Quote\Quotation\Model\Quote\File $fileModel
     * @param \Magento\Framework\Url\DecoderInterface $urlDecoder
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Cart2Quote\Quotation\Model\Quote\File $fileModel,
        \Magento\Framework\Url\DecoderInterface $urlDecoder
    ) {
        parent::__construct($context);
        $this->fileModel = $fileModel;
        $this->urlDecoder = $urlDecoder;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $quoteId = $this->getRequest()->getParam('quote_id');
            $fileName = $this->getRequest()->getParam('file');
            $fileName = $this->urlDecoder->decode($fileName);

            //add and replace the quotation folder to make sure we are in that folder
            $fileName = str_replace('quotation' . DIRECTORY_SEPARATOR, '', $fileName);
            $filePath = 'quotation' . DIRECTORY_SEPARATOR . $fileName;
            $fileName = $this->fileModel->fileAction($filePath, $this->fileModel::FILE_DELETE);
            $visibleCustomer = $this->fileModel->visible($fileName, $quoteId, $this->fileModel::CUSTOMER_FOLDER);
            $visibleEmail = $this->fileModel->visible($fileName, $quoteId, $this->fileModel::EMAIL_FOLDER);

            if ($visibleCustomer) {
                $path = $this->filePath($quoteId, $this->fileModel::CUSTOMER_FOLDER, $fileName);
                $this->fileModel->fileAction($path, $this->fileModel::FILE_DELETE);
            }

            if ($visibleEmail) {
                $path = $this->filePath($quoteId, $this->fileModel::EMAIL_FOLDER, $fileName);
                $this->fileModel->fileAction($path, $this->fileModel::FILE_DELETE);
            }

            $this->messageManager->addSuccessMessage(__('File %1 removed', $fileName));

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('quotation/quote/view', ['quote_id' => $quoteId]);

        return $resultRedirect;
    }

    /**
     * @param string $quoteId
     * @param string $path
     * @param string $fileName
     * @return string
     */
    protected function filePath($quoteId, $path, $fileName)
    {
        return $this->fileModel::QUOTATION_FOLDER .
            DIRECTORY_SEPARATOR .
            $quoteId .
            DIRECTORY_SEPARATOR .
            $path .
            DIRECTORY_SEPARATOR .
            $fileName;
    }
}
