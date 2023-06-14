<?php
namespace Magento\CatalogUrlRewrite\Observer\ProductProcessUrlRewriteRemovingObserver;

/**
 * Interceptor class for @see \Magento\CatalogUrlRewrite\Observer\ProductProcessUrlRewriteRemovingObserver
 */
class Interceptor extends \Magento\CatalogUrlRewrite\Observer\ProductProcessUrlRewriteRemovingObserver implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\UrlRewrite\Model\UrlPersistInterface $urlPersist)
    {
        $this->___init();
        parent::__construct($urlPersist);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($observer);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }
}
