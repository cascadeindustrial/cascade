<?php
namespace Dcw\CreditForm\Setup;


use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;

		$installer->startSetup();

		if(version_compare($context->getVersion(), '1.2.5', '<')) {
			$installer->getConnection()->addColumn(
				$installer->getTable( 'creditform' ),
				'customer_id',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					'nullable' => true,
					'length' => '200',
					'comment' => 'test'
				]
			);
		}



		$installer->endSetup();
	}
}
