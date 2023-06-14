<?php
namespace Dcw\FeaturedProducts\Setup;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $eavSetup= $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->removeAttribute(ProductAttributeInterface::ENTITY_TYPE_CODE,'model_no');
            $eavSetup->addAttribute(
                ProductAttributeInterface::ENTITY_TYPE_CODE,
                'model_no',
                [
                    'group' => 'General',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Model Number', 
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => true,
                    'user_defined' => false,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false
                ]
            );      
        }

        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $eavSetup= $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->removeAttribute(ProductAttributeInterface::ENTITY_TYPE_CODE,'featured_sort_no');
            $eavSetup->addAttribute(
                ProductAttributeInterface::ENTITY_TYPE_CODE,
                'featured_sort_no',
                [
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Featured Sort Number', 
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false
                ]
            );      
        }
    }
}