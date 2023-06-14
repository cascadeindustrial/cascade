<?php
namespace Amasty\Checkout\Cache\CachedLayoutProcessor\AddressFormAttributes\DefaultAttributeValueUpdate;

/**
 * Interceptor class for @see \Amasty\Checkout\Cache\CachedLayoutProcessor\AddressFormAttributes\DefaultAttributeValueUpdate
 */
class Interceptor extends \Amasty\Checkout\Cache\CachedLayoutProcessor\AddressFormAttributes\DefaultAttributeValueUpdate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Customer\Helper\Address $addressHelper, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Directory\Helper\Data $directoryHelper)
    {
        $this->___init();
        parent::__construct($addressHelper, $customerSession, $customerRepository, $directoryHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function updateDefaultValuesOfLayoutJs(array $layoutJsAttributes) : array
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateDefaultValuesOfLayoutJs');
        if (!$pluginInfo) {
            return parent::updateDefaultValuesOfLayoutJs($layoutJsAttributes);
        } else {
            return $this->___callPlugins('updateDefaultValuesOfLayoutJs', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function merge($elements, $providerName, $dataScopePrefix, array $fields = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'merge');
        if (!$pluginInfo) {
            return parent::merge($elements, $providerName, $dataScopePrefix, $fields);
        } else {
            return $this->___callPlugins('merge', func_get_args(), $pluginInfo);
        }
    }
}
