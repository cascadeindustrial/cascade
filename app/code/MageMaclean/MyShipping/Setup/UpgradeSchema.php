<?php 
namespace MageMaclean\MyShipping\Setup;
 
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
 
        if(!$context->getVersion()) {
            $setup->endSetup();
            return;
        }
 
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $courierRepository = $objectManager->create('MageMaclean\MyShipping\Model\Repository\CourierRepository');

            $defaultMethod = [
                "method_code" => "standard",
                "method_name" => "Standard",
                "method_price" => 0,
                "method_price_type" => "O",
                "method_handling_fee" => 0,
                "method_handling_type" => "F",
                "method_handling_action" => "O",
                "position" => 1
            ];
            
            $defaultSallowspecific = 0;

            $accountTable = $setup->getConnection()->getTableName("myshipping_account");
            $myshippingCarriers = $setup->getConnection()->fetchAll("SELECT * FROM $accountTable GROUP BY myshipping_carrier");
            if($myshippingCarriers && sizeof($myshippingCarriers)) {
                $sortOrder = 0;
                foreach($myshippingCarriers as $row) {
                    $sortOrder++;
                    $myshippingCarrier = $row['myshipping_carrier'];
                    $_courier = $objectManager->create('MageMaclean\MyShipping\Model\Courier');
                    $_courier->setTitle($myshippingCarrier);
                    $_courier->setIsEnabled(true);
                    $_courier->setMethods([$defaultMethod]);
                    $_courier->setSallowspecific($defaultSallowspecific);
                    $_courier->setSortOrder($sortOrder);
                    $_courier->setStoreId([0]);
                    $_courier = $courierRepository->save($_courier);
                    $courierId = $_courier->getId();

                    $setup->getConnection()->query("UPDATE $accountTable SET myshipping_courier_id = '$courierId' WHERE myshipping_carrier = '$myshippingCarrier'");
                }
            }
        }
 
        $setup->endSetup();
    }
}