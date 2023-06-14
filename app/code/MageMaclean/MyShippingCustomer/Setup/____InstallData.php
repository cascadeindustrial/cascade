<?php
namespace MageMaclean\MyShippingCustomer\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

use Magento\Eav\Model\Config;
use Magento\Customer\Model\Customer;
use Magento\Customer\Api\CustomerMetadataInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory, Config $eavConfig)
	{
		$this->eavSetupFactory = $eavSetupFactory;
		$this->eavConfig = $eavConfig;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
		$eavSetup->addAttribute(
			\Magento\Customer\Model\Customer::ENTITY,
			'myshipping_enabled',
			[
				'type'         => 'int',
				'label'        => 'Enable My Shipping Stored Accounts',
				'input'        => 'boolean',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
				'required'     => false,
				'visible'      => true,
				'user_defined' => true,
				'position'     => 800,
				'system'       => 0,
			]
		);
		$myshippingEnabledAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'myshipping_enabled');

        $eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
            "myshipping_enabled"
        );

		// more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
		$myshippingEnabledAttribute->setData(
			'used_in_forms',
			['adminhtml_customer', 'adminhtml_checkout']
		);
		$myshippingEnabledAttribute->save();

        $eavSetup->addAttribute(
			\Magento\Customer\Model\Customer::ENTITY,
			'myshipping_new_enabled',
			[
				'type'         => 'int',
				'label'        => 'Enable My Shipping New Accounts',
				'input'        => 'boolean',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
				'required'     => false,
				'visible'      => true,
				'user_defined' => true,
				'position'     => 801,
				'system'       => 0,
			]
		);
		$myshippingNewEnabledAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'myshipping_new_enabled');

        $eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
            "myshipping_new_enabled"
        );

		// more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
		$myshippingNewEnabledAttribute->setData(
			'used_in_forms',
			['adminhtml_customer', 'adminhtml_checkout']
		);
        
        
		$myshippingNewEnabledAttribute->save();
    }
}