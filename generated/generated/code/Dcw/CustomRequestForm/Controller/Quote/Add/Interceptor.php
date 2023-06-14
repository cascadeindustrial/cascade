<?php
namespace Dcw\CustomRequestForm\Controller\Quote\Add;

/**
 * Interceptor class for @see \Dcw\CustomRequestForm\Controller\Quote\Add
 */
class Interceptor extends \Dcw\CustomRequestForm\Controller\Quote\Add implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Cart2Quote\Quotation\Model\QuotationCart $cart, \Cart2Quote\Quotation\Model\Session $quotationSession, \Cart2Quote\Quotation\Model\QuoteFactory $quoteFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Catalog\Helper\Product $productHelper, \Cart2Quote\Quotation\Helper\Data $quotationDataHelper, \Magento\Framework\Locale\ResolverInterface $resolverInterface, \Magento\Framework\Escaper $escaper, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Serialize\SerializerInterface $serializer, \Magento\Framework\Json\Helper\Data $jsonHelper, \Cart2Quote\Quotation\Model\Quote\Request\Strategy\Provider $strategyProvider, \Magento\Customer\Model\Session $customerSession, \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory, \Magento\Framework\Image\AdapterFactory $adapterFactory, \Magento\Framework\Filesystem $filesystem)
    {
        $this->___init();
        parent::__construct($context, $scopeConfig, $storeManager, $formKeyValidator, $cart, $quotationSession, $quoteFactory, $resultPageFactory, $productRepository, $productHelper, $quotationDataHelper, $resolverInterface, $escaper, $logger, $serializer, $jsonHelper, $strategyProvider, $customerSession, $uploaderFactory, $adapterFactory, $filesystem);
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
    public function getProductId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductId');
        if (!$pluginInfo) {
            return parent::getProductId();
        } else {
            return $this->___callPlugins('getProductId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuotationSession()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQuotationSession');
        if (!$pluginInfo) {
            return parent::getQuotationSession();
        } else {
            return $this->___callPlugins('getQuotationSession', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValidQuoteRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isValidQuoteRequest');
        if (!$pluginInfo) {
            return parent::isValidQuoteRequest();
        } else {
            return $this->___callPlugins('isValidQuoteRequest', func_get_args(), $pluginInfo);
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
