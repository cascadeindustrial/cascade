<?php
namespace Amasty\ProductAttachment\Controller\File\Download;

/**
 * Interceptor class for @see \Amasty\ProductAttachment\Controller\File\Download
 */
class Interceptor extends \Amasty\ProductAttachment\Controller\File\Download implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Amasty\ProductAttachment\Api\FileRepositoryInterface $fileRepository, \Amasty\ProductAttachment\Model\File\FileScope\FileScopeDataProvider $fileScopeDataProvider, \Magento\Downloadable\Helper\Download $downloadHelper, \Amasty\ProductAttachment\Model\ConfigProvider $configProvider, \Magento\Store\Model\StoreManagerInterface $storeManager, \Amasty\ProductAttachment\Model\Report\ItemFactory $reportItemFactory, \Magento\Customer\Model\Session $customerSession, \Amasty\Base\Model\Response\OctetResponseInterfaceFactory $fileResponseFactory, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($fileRepository, $fileScopeDataProvider, $downloadHelper, $configProvider, $storeManager, $reportItemFactory, $customerSession, $fileResponseFactory, $filesystem, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function processFile($file)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'processFile');
        if (!$pluginInfo) {
            return parent::processFile($file);
        } else {
            return $this->___callPlugins('processFile', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute();
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveStat()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveStat');
        if (!$pluginInfo) {
            return parent::saveStat();
        } else {
            return $this->___callPlugins('saveStat', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActionFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionFlag');
        if (!$pluginInfo) {
            return parent::getActionFlag();
        } else {
            return $this->___callPlugins('getActionFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequest');
        if (!$pluginInfo) {
            return parent::getRequest();
        } else {
            return $this->___callPlugins('getRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResponse');
        if (!$pluginInfo) {
            return parent::getResponse();
        } else {
            return $this->___callPlugins('getResponse', func_get_args(), $pluginInfo);
        }
    }
}
