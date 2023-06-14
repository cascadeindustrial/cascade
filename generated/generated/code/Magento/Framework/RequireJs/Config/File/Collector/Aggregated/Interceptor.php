<?php
namespace Magento\Framework\RequireJs\Config\File\Collector\Aggregated;

/**
 * Interceptor class for @see \Magento\Framework\RequireJs\Config\File\Collector\Aggregated
 */
class Interceptor extends \Magento\Framework\RequireJs\Config\File\Collector\Aggregated implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Filesystem $filesystem, \Magento\Framework\View\File\Factory $fileFactory, \Magento\Framework\View\File\CollectorInterface $baseFiles, \Magento\Framework\View\File\CollectorInterface $themeFiles, \Magento\Framework\View\File\CollectorInterface $themeModularFiles)
    {
        $this->___init();
        parent::__construct($filesystem, $fileFactory, $baseFiles, $themeFiles, $themeModularFiles);
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles(\Magento\Framework\View\Design\ThemeInterface $theme, $filePath)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFiles');
        if (!$pluginInfo) {
            return parent::getFiles($theme, $filePath);
        } else {
            return $this->___callPlugins('getFiles', func_get_args(), $pluginInfo);
        }
    }
}
