<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Quote;

use Magento\Framework\View\Element\Template;

/**
 * Class Files
 * @package Cart2Quote\Quotation\Block\Quote
 */
class Files extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Cart2Quote\Quotation\Model\Quote\File
     */
    protected $fileModel;

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $urlEncoder;

    /**
     * Files constructor.
     *
     * @param \Cart2Quote\Quotation\Model\Quote\File $fileModel
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Cart2Quote\Quotation\Model\Quote\File $fileModel,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->fileModel = $fileModel;
        $this->urlEncoder = $urlEncoder;
    }

    /**
     * @return array|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getFiles()
    {
        $quoteId = $this->getRequest()->getParam('quote_id');
        return $this->fileModel->getFiles($quoteId, $this->fileModel::CUSTOMER_FOLDER);
    }

    /**
     * @param string $file
     * @return string
     */
    public function getDownloadUrl($file)
    {
        $file = $this->urlEncoder->encode($file);
        return $this->getUrl('quotation/fileupload/download', ['file' => $file]);
    }

    /**
     * @param string $file
     * @return bool|string
     */
    public function trimFileName($file)
    {
        return basename($file);
    }
}
