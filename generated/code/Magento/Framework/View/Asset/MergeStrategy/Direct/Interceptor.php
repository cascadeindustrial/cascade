<?php
namespace Magento\Framework\View\Asset\MergeStrategy\Direct;

/**
 * Interceptor class for @see \Magento\Framework\View\Asset\MergeStrategy\Direct
 */
class Interceptor extends \Magento\Framework\View\Asset\MergeStrategy\Direct implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Filesystem $filesystem, \Magento\Framework\View\Url\CssResolver $cssUrlResolver, ?\Magento\Framework\Math\Random $mathRandom = null)
    {
        $this->___init();
        parent::__construct($filesystem, $cssUrlResolver, $mathRandom);
    }

    /**
     * {@inheritdoc}
     */
    public function merge(array $assetsToMerge, \Magento\Framework\View\Asset\LocalInterface $resultAsset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'merge');
        if (!$pluginInfo) {
            return parent::merge($assetsToMerge, $resultAsset);
        } else {
            return $this->___callPlugins('merge', func_get_args(), $pluginInfo);
        }
    }
}
