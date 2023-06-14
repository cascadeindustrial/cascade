<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\FileUpload;

/**
 * Class Upload
 * @package Cart2Quote\Quotation\Controller\FileUpload
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
            $results = $this->fileModel->uploadFiles($filesAmount);
            foreach ($results as $result) {
                $this->messageManager->addSuccessMessage(__('File %1 added', $result['name']));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $defaultUrl = $this->_url->getUrl('*/*');

        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl($defaultUrl));
    }
}
