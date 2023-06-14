<?php
namespace Cart2Quote\Quotation\Plugin\Magento\Quote\Model\Webapi\ParamOverriderCartId;

/**
 * Interceptor class for @see \Cart2Quote\Quotation\Plugin\Magento\Quote\Model\Webapi\ParamOverriderCartId
 */
class Interceptor extends \Cart2Quote\Quotation\Plugin\Magento\Quote\Model\Webapi\ParamOverriderCartId implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Authorization\Model\UserContextInterface $userContext, \Magento\Quote\Api\CartManagementInterface $cartManagement, \Magento\Framework\App\RequestInterface $request, \Cart2Quote\Quotation\Model\Session $quoteSession)
    {
        $this->___init();
        parent::__construct($userContext, $cartManagement, $request, $quoteSession);
    }

    /**
     * {@inheritdoc}
     */
    public function aroundGetOverriddenValue($subject)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'aroundGetOverriddenValue');
        if (!$pluginInfo) {
            return parent::aroundGetOverriddenValue($subject);
        } else {
            return $this->___callPlugins('aroundGetOverriddenValue', func_get_args(), $pluginInfo);
        }
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
