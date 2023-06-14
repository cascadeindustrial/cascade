<?php
namespace Magento\RequireJs\Model\FileManager;

/**
 * Interceptor class for @see \Magento\RequireJs\Model\FileManager
 */
class Interceptor extends \Magento\RequireJs\Model\FileManager implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\RequireJs\Config $config, \Magento\Framework\Filesystem $appFilesystem, \Magento\Framework\App\State $appState, \Magento\Framework\View\Asset\Repository $assetRepo)
    {
        $this->___init();
        parent::__construct($config, $appFilesystem, $appState, $assetRepo);
    }

    /**
     * {@inheritdoc}
     */
    public function createRequireJsConfigAsset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createRequireJsConfigAsset');
        if (!$pluginInfo) {
            return parent::createRequireJsConfigAsset();
        } else {
            return $this->___callPlugins('createRequireJsConfigAsset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createMinResolverAsset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createMinResolverAsset');
        if (!$pluginInfo) {
            return parent::createMinResolverAsset();
        } else {
            return $this->___callPlugins('createMinResolverAsset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createRequireJsMixinsAsset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createRequireJsMixinsAsset');
        if (!$pluginInfo) {
            return parent::createRequireJsMixinsAsset();
        } else {
            return $this->___callPlugins('createRequireJsMixinsAsset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createRequireJsAsset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createRequireJsAsset');
        if (!$pluginInfo) {
            return parent::createRequireJsAsset();
        } else {
            return $this->___callPlugins('createRequireJsAsset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createUrlResolverAsset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createUrlResolverAsset');
        if (!$pluginInfo) {
            return parent::createUrlResolverAsset();
        } else {
            return $this->___callPlugins('createUrlResolverAsset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createRequireJsMapConfigAsset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createRequireJsMapConfigAsset');
        if (!$pluginInfo) {
            return parent::createRequireJsMapConfigAsset();
        } else {
            return $this->___callPlugins('createRequireJsMapConfigAsset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createStaticJsAsset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createStaticJsAsset');
        if (!$pluginInfo) {
            return parent::createStaticJsAsset();
        } else {
            return $this->___callPlugins('createStaticJsAsset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createBundleJsPool()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createBundleJsPool');
        if (!$pluginInfo) {
            return parent::createBundleJsPool();
        } else {
            return $this->___callPlugins('createBundleJsPool', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearBundleJsPool()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearBundleJsPool');
        if (!$pluginInfo) {
            return parent::clearBundleJsPool();
        } else {
            return $this->___callPlugins('clearBundleJsPool', func_get_args(), $pluginInfo);
        }
    }
}
