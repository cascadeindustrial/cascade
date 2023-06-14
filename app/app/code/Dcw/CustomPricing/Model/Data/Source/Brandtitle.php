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

class Brandtitle implements OptionSourceInterface
{

  protected $collectionFactory;
  protected $_connection;

  /**
   * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $collectionFactory
   * @param \Magento\Catalog\Model\ResourceModel\Product $product
   */
  public function __construct(
      \Amasty\ShopbyBase\Model\ResourceModel\OptionSetting\CollectionFactory $collectionFactory,
      \Magento\Framework\App\ResourceConnection $connection
  ) {
      $this->collectionFactory = $collectionFactory;
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
        $collection = $this->collectionFactory->create();
        $res = [];
        $connectioninfo = $this->_connection->getConnection();

        //$res[] = array("value"=>'',"label"=>'Please select');
        foreach ($collection as $item) {

              $id = $item->getData('value');
              $sql = "SELECT eav.value from eav_attribute_option_value eav WHERE  eav.option_id=$id" ;
              $brandlable = $connectioninfo->fetchRow($sql);
              $data['value'] = $item->getData('value');
              $data['label'] = $brandlable['value'];
              $res[] = $data;
        }
        $this->options = $res;
        return $this->options;
    }
}
