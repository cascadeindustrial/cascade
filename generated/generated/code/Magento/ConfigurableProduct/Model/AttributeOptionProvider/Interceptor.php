<?php
namespace Magento\ConfigurableProduct\Model\AttributeOptionProvider;

/**
 * Interceptor class for @see \Magento\ConfigurableProduct\Model\AttributeOptionProvider
 */
class Interceptor extends \Magento\ConfigurableProduct\Model\AttributeOptionProvider implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable\Attribute $attributeResource, \Magento\Framework\App\ScopeResolverInterface $scopeResolver, \Magento\ConfigurableProduct\Model\ResourceModel\Attribute\OptionSelectBuilderInterface $optionSelectBuilder)
    {
        $this->___init();
        parent::__construct($attributeResource, $scopeResolver, $optionSelectBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeOptions(\Magento\Eav\Model\Entity\Attribute\AbstractAttribute $superAttribute, $productId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributeOptions');
        if (!$pluginInfo) {
            return parent::getAttributeOptions($superAttribute, $productId);
        } else {
            return $this->___callPlugins('getAttributeOptions', func_get_args(), $pluginInfo);
        }
    }
}
