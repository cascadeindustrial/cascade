<?php
namespace Magento\Framework\Event\Config\Converter;

/**
 * Interceptor class for @see \Magento\Framework\Event\Config\Converter
 */
class Interceptor extends \Magento\Framework\Event\Config\Converter implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function convert($source)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convert');
        if (!$pluginInfo) {
            return parent::convert($source);
        } else {
            return $this->___callPlugins('convert', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function _convertObserverConfig($observerConfig)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '_convertObserverConfig');
        if (!$pluginInfo) {
            return parent::_convertObserverConfig($observerConfig);
        } else {
            return $this->___callPlugins('_convertObserverConfig', func_get_args(), $pluginInfo);
        }
    }
}
