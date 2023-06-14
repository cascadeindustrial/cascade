<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Tab;

use Magento\Sales\Block\Adminhtml\Order\View\Tab\History as MageHistory;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Class File
 *
 * @package Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Tab
 */
class File extends \Cart2Quote\Quotation\Block\Adminhtml\Quote\AbstractQuote implements TabInterface
{
    /**
     * Template
     *
     * @var string
     */
    protected $_template = 'quote/view/details/quote/uploadedfiles.phtml';

    /**
     * @var \Cart2Quote\Quotation\Model\Quote\File
     */
    protected $fileModel;

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $urlEncoder;

    /**
     * File constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Helper\Admin $adminHelper
     * @param \Cart2Quote\Quotation\Model\Quote\File $fileModel
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        \Cart2Quote\Quotation\Model\Quote\File $fileModel,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        array $data = []
    ) {
        parent::__construct($context, $registry, $adminHelper, $data);
        $this->fileModel = $fileModel;
        $this->urlEncoder = $urlEncoder;
    }

    /**
     * Retrieve quote model instance
     *
     * @return \Cart2Quote\Quotation\Model\Quote
     */
    public function getQuote()
    {
        return $this->_coreRegistry->registry('current_quote');
    }

    /**
     * Get Table Title
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getTabTitle()
    {
        return __('Quote Files');
    }

    /**
     * Get Tab label
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getTabLabel()
    {
        return __('Uploaded Files');
    }

    /**
     * Check if tab can be shown
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Check if tab is hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return array
     */
    public function getUploadedFiles()
    {
        return $this->fileModel->getFileDataFromQuotation();
    }

    /**
     * @param string $file
     * @return string
     */
    public function getDownloadUrl($file)
    {
        $file = $this->urlEncoder->encode($file);
        return $this->getUrl('quotation/file/download', ['file' => $file]);
    }

    /**
     * @param string $file
     * @return string
     */
    public function getDeleteUrl($file)
    {
        $file = $this->urlEncoder->encode($file);
        $quoteId = $this->getRequest()->getParam('quote_id');

        return $this->getUrl('quotation/file/remove', ['file' => $file, 'quote_id' => $quoteId]);
    }

    /**
     * @param string $file
     * @return string
     */
    public function getCheckboxId($file)
    {
        return $this->trimFileName($file);
    }

    /**
     * @param string $file
     * @return bool|string
     */
    public function trimFileName($file)
    {
        return basename($file);
    }

    /**
     * @param string $file
     * @param string $location
     * @return bool
     */
    public function isChecked($file, $location)
    {
        $quoteId = $this->getRequest()->getParam('quote_id');
        $file = basename($file);

        return $this->fileModel->visible($file, $quoteId, $location);
    }

    /**
     * @return string
     */
    public function getUrlAction()
    {
        return $this->getUrl('quotation/file/save');
    }
}
