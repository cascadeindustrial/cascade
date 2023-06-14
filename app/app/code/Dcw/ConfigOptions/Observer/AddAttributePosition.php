<?php
 
namespace Dcw\ConfigOptions\Observer;
 
use Magento\Framework\Event\ObserverInterface;
 
class AddAttributePosition implements ObserverInterface
{
    protected $_options;
    protected $objectmanager;
 
    public function __construct(
        \Magento\Catalog\Model\Product\Option $options,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute
    ) {
        $this->_options = $options;
        $this->_objectManager = $objectmanager;
        $this->_eavAttribute = $eavAttribute;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    { 
        $product = $observer->getProduct();
        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $productId = $product->getId();

        $configAttrData = $product->getConfigurableAttrOptions();

        $positionText = str_replace("*", "", $configAttrData);
          $positionArray = explode('|', $positionText);
           foreach ($positionArray as $key => $arr) 
            {
              $attrposition = explode(',', $arr);
               if(count($attrposition)>1)
                {
                $attributeCode = $attrposition[0];
                $attributeId = $this->_eavAttribute->getIdByCode('catalog_product', $attributeCode);
                 $connection->exec("UPDATE catalog_product_super_attribute SET position = $attrposition[1]  WHERE product_id = ".$productId." and attribute_id = ".$attributeId);
                }   
            }
           
    }
}