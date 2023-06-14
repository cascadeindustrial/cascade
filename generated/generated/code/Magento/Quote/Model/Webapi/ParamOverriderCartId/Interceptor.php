<?php
namespace Magento\Quote\Model\Webapi\ParamOverriderCartId;

/**
 * Interceptor class for @see \Magento\Quote\Model\Webapi\ParamOverriderCartId
 */
class Interceptor extends \Magento\Quote\Model\Webapi\ParamOverriderCartId implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Authorization\Model\UserContextInterface $userContext, \Magento\Quote\Api\CartManagementInterface $cartManagement)
    {
        $this->___init();
        parent::__construct($userContext, $cartManagement);
    }

    /**
     * {@inheritdoc}
     */
    public function getOverriddenValue()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOverriddenValue');
        if (!$pluginInfo) {
            return parent::getOverriddenValue();
        } else {
            return $this->___callPlugins('getOverriddenValue', func_get_args(), $pluginInfo);
        }
    }
}
