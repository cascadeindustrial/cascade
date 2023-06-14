<?php

namespace Dcw\ConfigOptions\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;


class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;

      public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
       $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'configurable_attr_options',
                [
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Configurable attribute options',
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
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'option' => [
                        'values' => [],
                    ]
                ]
            );
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'hydralic_fluid',
                [
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Hydraulic fluid',
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
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'option' => [
                        'values' => [],
                    ]
                ]
            );
            $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'solenoid',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Solenoid',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '0',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'option' => [ 
                    'values' => [],
                ]
            ]    
        );  
            $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'mounting_dimensions',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Mounting dimensions',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '0',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'option' => [ 
                    'values' => [],
                ]
            ]    
        );  
             $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'spool_type',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Spoll type',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '0',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'option' => [ 
                    'values' => [],
                ]
            ]    
        );  
             $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'spool_spring_arrangement',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Spoll/spring arrangements',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'option' => [ 
                    'values' => [],
                ]
            ]    
        );  
             if (version_compare($context->getVersion(), '1.0.1') < 0) {
             $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'enable_advanced_layout',
                [
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Enable Advanced Layout',
                    'input' => 'boolean',
                    'class' => '',
                    'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '0',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => ''
                ]
            ); 
         }
         if (version_compare($context->getVersion(), '1.0.3') < 0) {
             $eavSetup->removeAttribute(
             \Magento\Catalog\Model\Product::ENTITY,
             'solenoid');
              $eavSetup->removeAttribute(
             \Magento\Catalog\Model\Product::ENTITY,
             'mounting_dimensions');
               $eavSetup->removeAttribute(
             \Magento\Catalog\Model\Product::ENTITY,
             'spool_type');
                $eavSetup->removeAttribute(
             \Magento\Catalog\Model\Product::ENTITY,
             'spool_spring_arrangement');
         }
        
    }
}
