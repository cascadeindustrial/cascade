<?php
namespace Cminds\Creditline\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class InstallData implements InstallDataInterface
{
	public function __construct(
		ScopeConfigInterface $storeManager
	) {
		$this->storeManager = $storeManager;
	}

	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
		$setup->startSetup();
		
		$currencyCode = $this->storeManager->getValue(
            'currency/options/base'
        );
        $setup->getConnection()->update(
            $setup->getTable('cminds_creditline_balance'),
            ['currency_code' => $currencyCode]
        );
        $setup->getConnection()->update(
            $setup->getTable('cminds_creditline_transaction'),
            ['currency_code' => $currencyCode]
        );
			
		$setup->endSetup();
	}
}
