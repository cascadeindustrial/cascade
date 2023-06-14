<?php
namespace Magento\Braintree\Model\Ui\ConfigProvider;

/**
 * Interceptor class for @see \Magento\Braintree\Model\Ui\ConfigProvider
 */
class Interceptor extends \Magento\Braintree\Model\Ui\ConfigProvider implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Braintree\Gateway\Config\Config $config, \Magento\Braintree\Model\Adapter\BraintreeAdapterFactory $adapterFactory, \Magento\Framework\Session\SessionManagerInterface $session)
    {
        $this->___init();
        parent::__construct($config, $adapterFactory, $session);
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
    public function getClientToken()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getClientToken');
        if (!$pluginInfo) {
            return parent::getClientToken();
        } else {
            return $this->___callPlugins('getClientToken', func_get_args(), $pluginInfo);
        }
    }
}
