<?php
namespace Sendinblue\Sendinblue\Model\Transport;

/**
 * Interceptor class for @see \Sendinblue\Sendinblue\Model\Transport
 */
class Interceptor extends \Sendinblue\Sendinblue\Model\Transport implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct($message, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, $parameters = null)
    {
        $this->___init();
        parent::__construct($message, $scopeConfig, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sendMessage');
        if (!$pluginInfo) {
            return parent::sendMessage();
        } else {
            return $this->___callPlugins('sendMessage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMessage');
        if (!$pluginInfo) {
            return parent::getMessage();
        } else {
            return $this->___callPlugins('getMessage', func_get_args(), $pluginInfo);
        }
    }
}
