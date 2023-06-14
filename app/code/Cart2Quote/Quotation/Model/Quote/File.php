<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Model\Quote;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;

/**
 * Class File
 * @package Cart2Quote\Quotation\Model\Quote
 */
class File
{
    use \Cart2Quote\Features\Traits\Model\Quote\File {
        uploadFiles as private traitUploadFiles;
        removeFile as private traitRemoveFile;
        setImageDataToSession as private traitSetImageDataToSession;
        getFileDataFromSession as private traitGetFileDataFromSession;
        saveFileQuotationQuote as private traitSaveFileQuotationQuote;
        getFileDataFromQuotation as private traitGetFileDataFromQuotation;
        fileAction as private traitFileAction;
        addTo as private traitAddTo;
        visible as private traitVisible;
        getFiles as private traitGetFiles;
    }

    /**
     * Download action
     */
    const FILE_DOWNLOAD = 'download';

    /**
     * Delete action
     */
    const FILE_DELETE = 'delete';

    /**
     * customer folder
     */
    const CUSTOMER_FOLDER = 'customer';

    /**
     * Email folder
     */
    const EMAIL_FOLDER = 'email';

    /**
     * If show customer checkbox is selected
     */
    const SHOW_CUSTOMER = 'show_customer';

    /**
     * If attach to email is selected
     */
    const SHOW_EMAIL = 'show_email';

    /**
     * If attach email is not selected
     */
    const DONT_EMAIL = 'dont_email';

    /**
     * quotation folder
     */
    const QUOTATION_FOLDER = 'quotation';

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var \Cart2Quote\Quotation\Model\Session
     */
    private $quoteSession;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $fileDriver;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $ioFile;

    /**
     * @var \Magento\Backend\Model\Session\Quote
     */
    private $backendSessionQuote;

    /**
     * @var Magento\Framework\App\Response\Http\FileFactory
     */
    private $fileFactory;

    /**
     * @var \Cart2Quote\Quotation\Helper\FileUpload
     */
    private $fileUploadHelper;

    /**
     * Download helper
     *
     * @var \Magento\Downloadable\Helper\Download
     */
    protected $downloadHelper;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * File constructor.
     *
     * @param \Magento\Downloadable\Helper\Download $downloadHelper
     * @param \Cart2Quote\Quotation\Helper\FileUpload $fileUploadHelper
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Backend\Model\Session\Quote $backendSessionQuote
     * @param \Magento\Framework\Filesystem\Io\File $io
     * @param \Cart2Quote\Quotation\Model\Session $quoteSession
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Driver\File $fileDriver
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Downloadable\Helper\Download $downloadHelper,
        \Cart2Quote\Quotation\Helper\FileUpload $fileUploadHelper,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Backend\Model\Session\Quote $backendSessionQuote,
        \Magento\Framework\Filesystem\Io\File $io,
        \Cart2Quote\Quotation\Model\Session $quoteSession,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->downloadHelper = $downloadHelper;
        $this->fileUploadHelper = $fileUploadHelper;
        $this->fileFactory = $fileFactory;
        $this->backendSessionQuote = $backendSessionQuote;
        $this->ioFile = $io;
        $this->fileDriver = $fileDriver;
        $this->quoteSession = $quoteSession;
        $this->filesystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->request = $request;
    }

    /**
     * @param int $fileAmount
     * @param bool $backend
     * @param int $quoteId
     * @return array
     * @throws \Exception
     */
    public function uploadFiles($fileAmount, $backend = false, $quoteId = null)
    {
        return $this->traitUploadFiles($fileAmount, $backend, $quoteId);
    }

    /**
     * @param string $fileName
     * @param int $fileName
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function removeFile($fileName)
    {
        $this->traitRemoveFile($fileName);
    }

    /**
     * @param array $imageData
     */
    public function setImageDataToSession($imageData)
    {
        $this->traitSetImageDataToSession($imageData);
    }

    /**
     * @return array|null
     */
    public function getFileDataFromSession()
    {
        return $this->traitGetFileDataFromSession();
    }

    /**
     * @param int $quoteId
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function saveFileQuotationQuote($quoteId)
    {
        $this->traitSaveFileQuotationQuote($quoteId);
    }

    /**
     * @return array
     */
    public function getFileDataFromQuotation()
    {
        return $this->traitGetFileDataFromQuotation();
    }

    /**
     * @param string $fileName
     * @param string $action
     * @return \Magento\Framework\App\ResponseInterface|string
     * @throws \Exception
     */
    public function fileAction($fileName, $action)
    {
        return $this->traitFileAction($fileName, $action);
    }

    /**
     * @param array $files
     * @param string $quoteId
     * @param string $location
     * @return array|bool
     * @throws FileSystemException
     */
    public function addTo($files, $quoteId, $location)
    {
        return $this->traitAddTo($files, $quoteId, $location);
    }

    /**
     * @param string $file
     * @param string $quoteId
     * @param string $location
     * @return bool
     */
    public function visible($file, $quoteId, $location)
    {
        return $this->traitVisible($file, $quoteId, $location);
    }

    /**
     * @param string $quoteId
     * @param string $location
     * @return array|null
     */
    public function getFiles($quoteId, $location)
    {
        return $this->traitGetFiles($quoteId, $location);
    }
}
