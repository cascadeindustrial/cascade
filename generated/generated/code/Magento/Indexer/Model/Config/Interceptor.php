<?php
namespace Magento\Indexer\Model\Config;

/**
 * Interceptor class for @see \Magento\Indexer\Model\Config
 */
class Interceptor extends \Magento\Indexer\Model\Config implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Indexer\Model\Config\Data $configData)
    {
        $this->___init();
        parent::__construct($configData);
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexers()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIndexers');
        if (!$pluginInfo) {
            return parent::getIndexers();
        } else {
            return $this->___callPlugins('getIndexers', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexer($indexerId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIndexer');
        if (!$pluginInfo) {
            return parent::getIndexer($indexerId);
        } else {
            return $this->___callPlugins('getIndexer', func_get_args(), $pluginInfo);
        }
    }
}
