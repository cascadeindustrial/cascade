<?php
namespace Magento\Deploy\Package\Bundle\RequireJs;

/**
 * Interceptor class for @see \Magento\Deploy\Package\Bundle\RequireJs
 */
class Interceptor extends \Magento\Deploy\Package\Bundle\RequireJs implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Filesystem $filesystem, \Magento\Deploy\Config\BundleConfig $bundleConfig, \Magento\Framework\View\Asset\Minification $minification, $area, $theme, $locale, array $contentPools = [])
    {
        $this->___init();
        parent::__construct($filesystem, $bundleConfig, $minification, $area, $theme, $locale, $contentPools);
    }

    /**
     * {@inheritdoc}
     */
    public function addFile($filePath, $sourcePath, $contentType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addFile');
        if (!$pluginInfo) {
            return parent::addFile($filePath, $sourcePath, $contentType);
        } else {
            return $this->___callPlugins('addFile', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'flush');
        if (!$pluginInfo) {
            return parent::flush();
        } else {
            return $this->___callPlugins('flush', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clear');
        if (!$pluginInfo) {
            return parent::clear();
        } else {
            return $this->___callPlugins('clear', func_get_args(), $pluginInfo);
        }
    }
}
