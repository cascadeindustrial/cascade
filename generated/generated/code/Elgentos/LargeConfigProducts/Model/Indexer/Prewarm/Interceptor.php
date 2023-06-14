<?php
namespace Elgentos\LargeConfigProducts\Model\Indexer\Prewarm;

/**
 * Interceptor class for @see \Elgentos\LargeConfigProducts\Model\Indexer\Prewarm
 */
class Interceptor extends \Elgentos\LargeConfigProducts\Model\Indexer\Prewarm implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Message\ManagerInterface $messageManager, \Symfony\Component\Console\Output\ConsoleOutput $output, \Magento\Framework\App\State $state, \Elgentos\LargeConfigProducts\Model\MessageQueues\Publisher $publisher, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory)
    {
        $this->___init();
        parent::__construct($storeManager, $messageManager, $output, $state, $publisher, $productCollectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($productIds)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($productIds);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
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
