<?php

namespace Dcw\CustomPricing\Setup;;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.1', '<')) {

            $installer->getConnection()->addColumn(
                $installer->getTable('dcw_custom_price_rules'),
                'standard_discount_percentage',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    'nullable' => false,
                    'comment' => 'Standard Discount Percentage',
                    'identity' => false
                ]
            );

        }
        if (version_compare($context->getVersion(), '1.0.2', '<')) {

            $installer->getConnection()->addColumn(
                $installer->getTable('dcw_custom_price_rules'),
                'status',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    'nullable' => false,
                    'comment' => 'Status',
                    'identity' => false
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.3', '<')) {

            $installer->getConnection()->addColumn(
                $installer->getTable('dcw_custom_price_rules'),
                'title',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Title',
                    'identity' => false
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.7', '<')) {

            $installer->getConnection()->addColumn(
                $installer->getTable('dcw_custom_price_rules'),
                'brand',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Brand',
                    'identity' => false
                ]
            );
        }
        $installer->endSetup();

    }

}
