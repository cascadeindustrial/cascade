<?php

namespace Dcw\ShippingAccount\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Model\Config;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\DB\Ddl\Table;

class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;

    protected $customerSetupFactory;

    private $attributeSetFactory;


      public function __construct(EavSetupFactory $eavSetupFactory, Config $eavConfig,CustomerSetupFactory $customerSetupFactory,AttributeSetFactory $attributeSetFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavConfig       = $eavConfig;
        $this->customerSetupFactory = $customerSetupFactory;
    }
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
       $installer = $setup;
        $setup->startSetup();
       // $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $saleorderTable = 'sales_order';
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

          $setup->getConnection()
            ->addColumn(
                $setup->getTable('quote'),
            'ups_account_no',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' =>'UPS Account No',
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => 0,
             ]
        );
            $setup->getConnection()
             ->addColumn(
                $setup->getTable('quote'),
            'fedex_account_no',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' =>'Fedex Account No',
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => 0,
             ]
        );
             $setup->getConnection()
              ->addColumn(
                $setup->getTable('quote'),
            'selected_shipping_method',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' =>'Selected Shipping Method',
                'input' => 'select',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => 0,
             ]
        );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($saleorderTable),
            'ups_account_no',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' =>'UPS Account No',
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => 0,
             ]
        );
            $setup->getConnection()
             ->addColumn(
                $setup->getTable($saleorderTable),
            'fedex_account_no',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' =>'Fedex Account No',
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => 0,
             ]
        );
             $setup->getConnection()
              ->addColumn(
                $setup->getTable($saleorderTable),
            'selected_shipping_method',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' =>'Selected Shipping Method',
                'input' => 'select',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => 0,
             ]
        );
         $setup->endSetup();
        }
        if (version_compare($context->getVersion(), '1.0.2') < 0) {

          /** @var CustomerSetup $customerSetup */
          $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
          $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
          $attributeSetId = $customerEntity->getDefaultAttributeSetId();

          /** @var $attributeSet AttributeSet */
          $attributeSet = $this->attributeSetFactory->create();
          $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

          $customerSetup->addAttribute(Customer::ENTITY, 'upsaccountno', [
              'type' => 'text',
              'label' => 'UPS Account No',
              'input' => 'text',
              'required' => true,
              'visible' => true,
              'source' => '',
              'backend' => '',
              'user_defined' => false,
              'is_user_defined' => false,
              'sort_order' => 1000,
              'is_used_in_grid' => false,
              'is_visible_in_grid' => false,
              'is_filterable_in_grid' => false,
              'is_searchable_in_grid' => false,
              'position' => 1000,
              'default' => 0,
              'system' => 0,
           ]);

           $customerSetup->addAttribute(Customer::ENTITY, 'fedexaccountno', [
               'type' => 'text',
               'label' => 'Fedex Account No',
               'input' => 'text',
               'required' => true,
               'visible' => true,
               'source' => '',
               'backend' => '',
               'user_defined' => false,
               'is_user_defined' => false,
               'sort_order' => 1000,
               'is_used_in_grid' => false,
               'is_visible_in_grid' => false,
               'is_filterable_in_grid' => false,
               'is_searchable_in_grid' => false,
               'position' => 1000,
               'default' => 0,
               'system' => 0,
            ]);

           $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'upsaccountno')
              ->addData([
                   'attribute_set_id' => $attributeSetId,
                   'attribute_group_id' => $attributeGroupId,
                   'used_in_forms' => ['adminhtml_customer'],
            ]);

          $attribute1 = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'fedexaccountno')
               ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
             ]);

           $attribute->save();
           $attribute1->save();

        }
        // if (version_compare($context->getVersion(), '1.0.3') < 0) {
        //
        //   $setup->getConnection()
        //       ->addColumn(
        //           $setup->getTable($saleorderTable),
        //       'fedex_stored_account_no',
        //       [
        //           'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        //           'comment' =>'Fedex Stored Account No',
        //           'input' => 'text',
        //           'required' => false,
        //           'visible' => true,
        //           'user_defined' => true,
        //           'system' => 0,
        //        ]
        //   );
        //       $setup->getConnection()
        //        ->addColumn(
        //           $setup->getTable($saleorderTable),
        //       'ups_stored_account_no',
        //       [
        //           'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        //           'comment' =>'UPS Stored Account No',
        //           'input' => 'text',
        //           'required' => false,
        //           'visible' => true,
        //           'user_defined' => true,
        //           'system' => 0,
        //        ]
        //   );
        // }
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
          $tableName = $setup->getTable('order_storedaccount_values');
          if($setup->getConnection()->isTableExists($tableName) != true){
              $table = $setup->getConnection()->newTable($tableName)
                              ->addColumn(
                                  'id',
                                  Table::TYPE_INTEGER,
                                  null,
                       ['identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true]
                                  )
                              ->addColumn(
                                  'increment_id',
                                  Table::TYPE_INTEGER,
                                  255,
                                  ['nullable'=>false]
                                  )
                              ->addColumn(
                                      'ups_account_no',
                                      Table::TYPE_INTEGER,
                                      255,
                                      ['nullable'=>false]
                                      )
                              ->addColumn(
                                      'fedex_account_no',
                                      Table::TYPE_INTEGER,
                                      255,
                                      ['nullable'=>false]
                                    )
                              ->setOption('charset','utf8');
              $setup->getConnection()->createTable($table);
          }
        }
    }
}
