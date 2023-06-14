<?php
namespace Amasty\Xsearch\Controller\Autocomplete\Index;

/**
 * Interceptor class for @see \Amasty\Xsearch\Controller\Autocomplete\Index
 */
class Interceptor extends \Amasty\Xsearch\Controller\Autocomplete\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Search\Model\QueryFactory $queryFactory, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Framework\Registry $coreRegistry, \Amasty\Xsearch\Helper\Data $helper, \Magento\Framework\Url\DecoderInterface $urlDecoder, \Magento\Framework\Url\Helper\Data $urlHelper, \Magento\CatalogSearch\Helper\Data $searchHelper, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Amasty\Xsearch\Block\MultipleWishlist\Behavior $behavior)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $queryFactory, $layerResolver, $resultJsonFactory, $layoutFactory, $coreRegistry, $helper, $urlDecoder, $urlHelper, $searchHelper, $scopeConfig, $formKeyValidator, $behavior);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute();
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActionFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionFlag');
        if (!$pluginInfo) {
            return parent::getActionFlag();
        } else {
            return $this->___callPlugins('getActionFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequest');
        if (!$pluginInfo) {
            return parent::getRequest();
        } else {
            return $this->___callPlugins('getRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResponse');
        if (!$pluginInfo) {
            return parent::getResponse();
        } else {
            return $this->___callPlugins('getResponse', func_get_args(), $pluginInfo);
        }
    }
}
