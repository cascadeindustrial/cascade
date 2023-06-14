<?php
namespace Dcw\CustomPricing\Model\ResourceModel\CustomPricing;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
        protected $_idFieldName = 'id';
        protected $_eventPrefix = 'Dcw\CustomPricing_custompricing_collection';
        protected $_eventObject = 'custompricing_collection';
        
        protected function _construct()      {
			$this->_init('Dcw\CustomPricing\Model\CustomPricing', 'Dcw\CustomPricing\Model\ResourceModel\CustomPricing');
            
    }
}