<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Adminhtml\File;

/**
 * Class Upload
 * @package Cart2Quote\Quotation\Controller\Adminhtml\File
 */
class Upload extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Cart2Quote\Quotation\Model\Quote\File
     */
    protected $fileModel;

    /**
     * Upload constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Cart2Quote\Quotation\Model\Quote\File $fileModel
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Cart2Quote\Quotation\Model\Quote\File $fileModel
    ) {
        parent::__construct($context);
        $this->fileModel = $fileModel;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $filesAmount = count($this->getRequest()->getFiles());
            $quoteId = $this->getRequest()->getParam('quote_id');

            if (isset($quoteId)) {
                $results = $this->fileModel->uploadFiles($filesAmount, true, $quoteId);
                foreach ($results as $result) {
                    $this->messageManager->addSuccessMessage(__('File %1 added', $result['name']));
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('quotation/quote/view', ['quote_id' => $quoteId]);

        return $resultRedirect;
    }
}
