<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dcw\CustomPricing\Setup;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /**
         * Create table 'dcw_custom_price_rules'
         */
        if (!$installer->tableExists('dcw_custom_price_rules')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('dcw_custom_price_rules')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'ID'
            )->addColumn(
                'customer_group',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable => false',
                ],
                'Customer Group'
            )->addColumn(
                'discount_percentage',
                Table::TYPE_DECIMAL,
                null,
                [
                    'nullable => false',
                ],
                'Discount Percentage'
            )->addColumn(
                'category',
                Table::TYPE_TEXT,
                '255',
                [
                    'nullable' => false,
                ],
                'Category'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => Table::TIMESTAMP_INIT,
                ],
                'Created At'
            )->setComment('Custom Pricing Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}