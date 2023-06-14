<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Adminhtml\File;

/**
 * Class Download
 * @package Cart2Quote\Quotation\Controller\Adminhtml\File
 */
class Download extends \Magento\Backend\App\Action
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
     * Download constructor.
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
            $fileName = $this->getRequest()->getParam('file');
            $fileName = $this->urlDecoder->decode($fileName);

            //add and replace the quotation folder to make sure we are in that folder
            $fileName = str_replace('quotation' . DIRECTORY_SEPARATOR, '', $fileName);
            $filePath = 'quotation' . DIRECTORY_SEPARATOR . $fileName;

            $this->fileModel->fileAction($filePath, $this->fileModel::FILE_DOWNLOAD);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }
}
