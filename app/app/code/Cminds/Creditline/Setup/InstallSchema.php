<?php
namespace Cminds\Creditline\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $connection = $installer->getConnection();

        $installer->startSetup();
        $table = $connection->newTable(
            $installer->getTable('cminds_creditline_balance')
        )->addColumn(
            'balance_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Balance Id'
        )->addColumn(
            'customer_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Customer Id'
        )->addColumn(
            'amount',
            Table::TYPE_DECIMAL,
            '12,4',
            ['unsigned' => false, 'nullable' => true],
            'Amount'
        )->addColumn(
            'limit_amount',
            Table::TYPE_DECIMAL,
            '12,4',
            ['unsigned' => false, 'nullable' => true, 'default' => 0],
            'Limit Amount'
        )->addColumn(
            'credit_term',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Number of days for credit term'
        )->addColumn(
            'payment_type',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Payment Type'
        )->addColumn(
            'reminders',
            Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => false],
            'Number of days between email payment reminders'
        )->addColumn(
            'is_subscribed',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'default' => 0],
            'Is Subscribed'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['unsigned' => false, 'nullable' => true],
            'Created At'
        )->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['unsigned' => false, 'nullable' => true],
            'Updated At'
        )->addIndex(
            $installer->getIdxName('cminds_creditline_balance', ['customer_id']),
            ['customer_id']
        )->addForeignKey(
            $installer->getFkName(
                'cminds_creditline_balance',
                'customer_id',
                'customer_entity',
                'entity_id'
            ),
            'customer_id',
            $installer->getTable('customer_entity'),
            'entity_id',
            Table::ACTION_SET_NULL
        );
        $connection->createTable($table);

        $table = $connection->newTable(
            $installer->getTable('cminds_creditline_transaction')
        )->addColumn(
            'transaction_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Transaction Id'
        )->addColumn(
            'balance_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Balance Id'
        )->addColumn(
            'balance_amount',
            Table::TYPE_DECIMAL,
            '12,4',
            ['unsigned' => false, 'nullable' => true],
            'Balance Amount'
        )->addColumn(
            'balance_delta',
            Table::TYPE_DECIMAL,
            '12,4',
            ['unsigned' => false, 'nullable' => true],
            'Balance Delta'
        )->addColumn(
            'action',
            Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => false],
            'Action'
        )->addColumn(
            'message',
            Table::TYPE_TEXT,
            '64K',
            ['unsigned' => false, 'nullable' => true],
            'Message'
        )->addColumn(
            'is_notified',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'default' => 0],
            'Is Notified'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['unsigned' => false, 'nullable' => true],
            'Created At'
        )->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['unsigned' => false, 'nullable' => true],
            'Updated At'
        )->addIndex(
            $installer->getIdxName('cminds_creditline_transaction', ['balance_id']),
            ['balance_id']
        )->addForeignKey(
            $installer->getFkName(
                'cminds_creditline_transaction',
                'balance_id',
                'cminds_creditline_balance',
                'balance_id'
            ),
            'balance_id',
            $installer->getTable('cminds_creditline_balance'),
            'balance_id',
            Table::ACTION_CASCADE
        );
        $connection->createTable($table);
        
        // 102
        $table = $installer->getConnection()->newTable(
            $installer->getTable('cminds_creditline_product_option_type_credit')
        )
            ->addColumn(
                'option_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
                'Option Id')
            ->addColumn(
                'option_product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => false],
                'Product ID')
            ->addColumn(
                'store_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => true],
                'Store Id')
            ->addColumn(
                'option_price_type',
                Table::TYPE_TEXT,
                20,
                ['unsigned' => false, 'nullable' => false, 'default' => 'fixed'],
                'Option Price Type')
            ->addColumn(
                'option_price_options',
                Table::TYPE_TEXT,
                20,
                ['unsigned' => false, 'nullable' => false],
                'Option Price Options')
            ->addColumn(
                'option_price',
                Table::TYPE_FLOAT,
                null,
                ['unsigned' => false, 'nullable' => false],
                'Option Price')
            ->addColumn(
                'option_credits',
                Table::TYPE_FLOAT,
                null,
                ['unsigned' => false, 'nullable' => true, 'default' => 0],
                'Option Credits')
            ->addColumn(
                'option_min_credits',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => true, 'default' => 0],
                'Option Min Credits')
            ->addColumn(
                'option_max_credits',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => true, 'default' => 0],
                'Option Max Credits');
        $installer->getConnection()->createTable($table);

        //103
        $installer->getConnection()->addColumn($installer->getTable('quote'), 'manual_used_credit', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'maluan used credit',
        ]);

        //104
        $installer->getConnection()->addColumn($installer->getTable('cminds_creditline_balance'), 'currency_code', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => false,
            'length'   => '3',
            'comment'  => 'Balance currency code',
        ]);

        $installer->getConnection()->addColumn(
            $installer->getTable('cminds_creditline_transaction'),
            'currency_code',
            [
                'type'     => Table::TYPE_TEXT,
                'nullable' => false,
                'length'   => '3',
                'comment'  => 'Transaction currency code',
            ]
        );

        $connection->addColumn($installer->getTable('sales_creditmemo'), 'base_creditline_amount', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'base credit amount',
        ]);
        $connection->addColumn($installer->getTable('sales_creditmemo'), 'creditline_amount', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'credit amount',
        ]);
        $connection->addColumn($installer->getTable('sales_creditmemo'), 'base_creditline_total_refunded', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'base credit total refunded',
        ]);
        $connection->addColumn($installer->getTable('sales_creditmemo'), 'creditline_total_refunded', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'credit total refunded',
        ]);
        $connection->addColumn($installer->getTable('sales_invoice'), 'base_creditline_amount', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'base credit amount',
        ]);
        $connection->addColumn($installer->getTable('sales_invoice'), 'creditline_amount', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'credit amount',
        ]);
        $connection->addColumn($installer->getTable('sales_order'), 'base_creditline_amount', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'base credit amount',
        ]);
        $connection->addColumn($installer->getTable('sales_order'), 'creditline_amount', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'credit amount',
        ]);
        $connection->addColumn($installer->getTable('sales_order'), 'base_creditline_invoiced', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'base credit invoced',
        ]);
        $connection->addColumn($installer->getTable('sales_order'), 'creditline_invoiced', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'credit invoced',
        ]);
        $connection->addColumn($installer->getTable('sales_order'), 'base_creditline_refunded', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'base credit refunded',
        ]);
        $connection->addColumn($installer->getTable('sales_order'), 'creditline_refunded', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'credit refunded',
        ]);
        $connection->addColumn($installer->getTable('sales_order'), 'base_creditline_total_refunded', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'base credit total refunded',
        ]);
        $connection->addColumn($installer->getTable('sales_order'), 'creditline_total_refunded', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'credit total refunded',
        ]);
        $connection->addColumn($installer->getTable('quote'), 'use_credit', [
            'type'     => Table::TYPE_SMALLINT,
            'nullable' => false,
            'length'   => 1,
            'default'  => 0,
            'comment'  => 'use credit',
        ]);
        $connection->addColumn($installer->getTable('quote'), 'base_creditline_amount_used', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'base credit amount used',
        ]);
        $connection->addColumn($installer->getTable('quote'), 'creditline_amount_used', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'credit amount used',
        ]);
        $connection->addColumn($installer->getTable('quote_address'), 'base_creditline_amount', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'base credit amount',
        ]);
        $connection->addColumn($installer->getTable('quote_address'), 'creditline_amount', [
            'type'     => Table::TYPE_DECIMAL,
            'nullable' => false,
            'length'   => '12,4',
            'default'  => 0,
            'comment'  => 'credit amount',
        ]);
    }
}
