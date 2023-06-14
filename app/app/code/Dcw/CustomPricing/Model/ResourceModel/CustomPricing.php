<?php 

namespace Dcw\CustomPricing\Model\ResourceModel;

class CustomPricing extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

 public function _construct()
 {

 $this->_init("dcw_custom_price_rules","id");

 }
}
 ?>