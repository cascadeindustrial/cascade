<?php

namespace MageMaclean\MyShippingCustomer\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class CustomerAttributes implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        /*
        $customerSetup->addAttribute(Customer::ENTITY, 'myshipping_enabled', [
            'type'         => 'int',
            'label'        => 'Enable Stored Accounts',
            'input'        => 'boolean',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'required'     => false,
            'visible'      => false,
            'user_defined' => true,
            'position'     => 800,
            'system'       => 0
        ]);
        $myshippingEnabledAttribute = $customerSetup->getEavConfig()->getAttribute('customer', 'myshipping_enabled')->addData([
            'used_in_forms' => [
                'adminhtml_customer'
            ]
        ]);
        $myshippingEnabledAttribute->save();

        $customerSetup->addAttribute(Customer::ENTITY, 'myshipping_new_enabled', [
            'type'         => 'int',
            'label'        => 'Enable New Accounts',
            'input'        => 'boolean',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'required'     => false,
            'visible'      => false,
            'user_defined' => true,
            'position'     => 801,
            'system'       => 0
        ]);
         
        $myshippingNewEnabledAttribute = $customerSetup->getEavConfig()->getAttribute('customer', 'myshipping_new_enabled')->addData([
            'used_in_forms' => [
                'adminhtml_customer'
            ]
        ]);
        $myshippingNewEnabledAttribute->save();

        */
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        #$customerSetup->removeAttribute(\Magento\Customer\Model\Customer::ENTITY, 'myshipping_enabled');
        #$customerSetup->removeAttribute(\Magento\Customer\Model\Customer::ENTITY, 'myshipping_new_enabled');
 
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}