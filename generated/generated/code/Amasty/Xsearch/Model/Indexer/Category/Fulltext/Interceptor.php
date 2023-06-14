<?php
namespace Amasty\Xsearch\Model\Indexer\Category\Fulltext;

/**
 * Interceptor class for @see \Amasty\Xsearch\Model\Indexer\Category\Fulltext
 */
class Interceptor extends \Amasty\Xsearch\Model\Indexer\Category\Fulltext implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Amasty\Xsearch\Model\Indexer\Category\Fulltext\Action\FullFactory $fullActionFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Search\Request\DimensionFactory $dimensionFactory, \Amasty\Xsearch\Model\Indexer\Category\IndexerHandlerFactory $indexerHandlerFactory, \Magento\Framework\Search\Request\Config $searchRequestConfig, array $data)
    {
        $this->___init();
        parent::__construct($fullActionFactory, $storeManager, $dimensionFactory, $indexerHandlerFactory, $searchRequestConfig, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function executeFull()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'executeFull');
        if (!$pluginInfo) {
            return parent::executeFull();
        } else {
            return $this->___callPlugins('executeFull', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute($ids)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($ids);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function executeList(array $ids)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'executeList');
        if (!$pluginInfo) {
            return parent::executeList($ids);
        } else {
            return $this->___callPlugins('executeList', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function executeRow($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'executeRow');
        if (!$pluginInfo) {
            return parent::executeRow($id);
        } else {
            return $this->___callPlugins('executeRow', func_get_args(), $pluginInfo);
        }
    }
}
