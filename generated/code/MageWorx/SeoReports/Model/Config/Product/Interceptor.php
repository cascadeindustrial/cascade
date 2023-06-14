<?php
namespace MageWorx\SeoReports\Model\Config\Product;

/**
 * Interceptor class for @see \MageWorx\SeoReports\Model\Config\Product
 */
class Interceptor extends \MageWorx\SeoReports\Model\Config\Product implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfig');
        if (!$pluginInfo) {
            return parent::getConfig();
        } else {
            return $this->___callPlugins('getConfig', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldList()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFieldList');
        if (!$pluginInfo) {
            return parent::getFieldList();
        } else {
            return $this->___callPlugins('getFieldList', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigProblemSections()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfigProblemSections');
        if (!$pluginInfo) {
            return parent::getConfigProblemSections();
        } else {
            return $this->___callPlugins('getConfigProblemSections', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDuplicateColumnData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDuplicateColumnData');
        if (!$pluginInfo) {
            return parent::getDuplicateColumnData();
        } else {
            return $this->___callPlugins('getDuplicateColumnData', func_get_args(), $pluginInfo);
        }
    }
}
