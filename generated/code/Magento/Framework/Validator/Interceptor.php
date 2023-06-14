<?php
namespace Magento\Framework\Validator;

/**
 * Interceptor class for @see \Magento\Framework\Validator
 */
class Interceptor extends \Magento\Framework\Validator implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function addValidator(\Magento\Framework\Validator\ValidatorInterface $validator, $breakChainOnFailure = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addValidator');
        if (!$pluginInfo) {
            return parent::addValidator($validator, $breakChainOnFailure);
        } else {
            return $this->___callPlugins('addValidator', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isValid');
        if (!$pluginInfo) {
            return parent::isValid($value);
        } else {
            return $this->___callPlugins('isValid', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslator($translator = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTranslator');
        if (!$pluginInfo) {
            return parent::setTranslator($translator);
        } else {
            return $this->___callPlugins('setTranslator', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslator()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTranslator');
        if (!$pluginInfo) {
            return parent::getTranslator();
        } else {
            return $this->___callPlugins('getTranslator', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasTranslator()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasTranslator');
        if (!$pluginInfo) {
            return parent::hasTranslator();
        } else {
            return $this->___callPlugins('hasTranslator', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMessages');
        if (!$pluginInfo) {
            return parent::getMessages();
        } else {
            return $this->___callPlugins('getMessages', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasMessages()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasMessages');
        if (!$pluginInfo) {
            return parent::hasMessages();
        } else {
            return $this->___callPlugins('hasMessages', func_get_args(), $pluginInfo);
        }
    }
}
