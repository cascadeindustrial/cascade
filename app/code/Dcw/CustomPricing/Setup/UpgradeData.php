<?php

namespace Dcw\CustomPricing\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;


class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;


    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @var SalesSetup
     */
    private $salesSetupFactory;


      public function __construct(EavSetupFactory $eavSetupFactory,
      QuoteSetupFactory $quoteSetupFactory,
      SalesSetupFactory $salesSetupFactory
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)

    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /** @var QuoteSetup $quoteSetup */
        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);

        /** @var SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'shipping_option',
                [
                    'type'                    => 'text',
                    'label'                   => 'Shipping Option',
                    'input'                   => 'text',
                    'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => true,
                    'default'                 => '',
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => true,
                    'unique'                  => false,
                    'option'                  => [
                        'values' => [],
                    ]
                ]
            );

            $attributeSetId = $eavSetup->getDefaultAttributeSetId('catalog_product');
            $eavSetup->addAttributeToSet(
                'catalog_product',
                $attributeSetId,
                'General',
                'shipping_option'
            );

            $attributeOptions = [
                'visible'  => true,
                'required' => false
            ];
            $quoteSetup->addAttribute('quote_item', 'shipping_option', $attributeOptions);
            $salesSetup->addAttribute('order_item', 'shipping_option', $attributeOptions);
        }
        if (version_compare($context->getVersion(), '1.0.5') < 0) {
          $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY,'expedited_shipping_price');
          $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY,'standard_shipping_price');
        }
        if (version_compare($context->getVersion(), '1.0.6') < 0) {
             $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'enable_delivery_options',
                [
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Enable Delivery Options',
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
    }
}
