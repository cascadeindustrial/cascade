<?php
/*
 * SussexDev_Sample

 * @category   SussexDev
 * @package    SussexDev_Sample
 * @copyright  Copyright (c) 2019 Scott Parsons
 * @license    https://github.com/ScottParsons/module-sampleuicomponent/blob/master/LICENSE.md
 * @version    1.1.2
 */
namespace Dcw\CustomPricing\Model\Data\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CategoryName implements OptionSourceInterface
{

  protected $_connection;

  /**
   * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $collectionFactory
   * @param \Magento\Catalog\Model\ResourceModel\Product $product
   */
  public function __construct(
      \Magento\Framework\App\ResourceConnection $connection
  ) {
      $this->_connection = $connection;
  }
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        // return [
        //     ['value' => 1, 'label' => __('Enabled')],
        //     ['value' => 0, 'label' => __('Disabled')]
        // ];
        $res = $catNames = [];
        $connectionInfo = $this->_connection->getConnection();
        $sql = "SELECT * from dcw_custom_price_rules" ;
        $customRules = $connectionInfo->fetchAll($sql);

        // $data['value'] = "56";
        // $data['label'] = "Test";
//        $res[] = $data;
        //$res[] = array("value"=>'',"label"=>'Please select');
        foreach ($customRules as $rule) {

              $categoryList = explode(',',$rule['category']);
              foreach($categoryList as $catId)
              {
                $sql = "SELECT value FROM `catalog_category_entity_varchar` WHERE `attribute_id` = 45 AND `entity_id` = ".$catId ;
                $catName = $connectionInfo->fetchRow($sql);
                $catNames[] = $catName['value'];
                //printLog($catNames);
                //$catNames[] = $catName[]
              }
              // echo "<pre>";
              // print_r($catNames);
              if(count($catNames)>1)
                $catNameFormatted = implode(',',$catNames);
              else
                $catNameFormatted = $catNames[0];

              //printLog($catNameFormatted);
              $data['value'] = $rule['category'];
              $data['label'] = $catNameFormatted;
              $res[] = $data;
        }
        $this->options = $res;
        return $this->options;
    }
}
