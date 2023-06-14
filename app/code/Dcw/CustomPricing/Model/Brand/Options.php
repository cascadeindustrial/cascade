<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Dcw\CustomPricing\Model\Brand;

/**
 * Attribute Set Options
 */
class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;
    protected $_connection;

    protected $collectionFactory;

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
     * @inheritDoc
     */
    public function toOptionArray()
    {
      $collection = $this->collectionFactory->create();
      $res = [];
      $connectioninfo = $this->_connection->getConnection();
      $res[] = array("value"=>'',"label"=>'Please select');
      foreach ($collection as $item) {
            //$identifier = $item->getData('identifier');
            $id = $item->getData('value');
            $sql = "SELECT eav.value from eav_attribute_option_value eav WHERE  eav.option_id=$id" ;
            $brandlable = $connectioninfo->fetchRow($sql);
            //return $brandlable['value'];
            $data['value'] = $item->getData('value');
            $data['label'] = $brandlable['value'];

            // if (in_array($identifier, $existingIdentifiers)) {
            //     $data['value'] .= '|' . $item->getData('page_id');
            // } else {
            //     $existingIdentifiers[] = $identifier;
            // }
            //
            // if (!$item->getData('is_active')) {
            //     $data['label'] .= ' [' . __('Disabled') . ']';
            // }

            $res[] = $data;
      }
      $this->options = $res;
      // echo "<pre>";
      // print_r($collection->getData());
      // exit;
      return $this->options;
    }
}
