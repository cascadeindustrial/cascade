<?php
namespace Dcw\CustomPricing\Model;
class CustomPricing extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface {
    const CACHE_TAG = 'dcw_custompricing';
    protected $_cacheTag = 'dcw_custompricing';
    protected $_eventPrefix = 'dcw_custompricing';
    protected function _construct() {
        $this->_init('Dcw\CustomPricing\Model\ResourceModel\CustomPricing');	
    }
    public function getIdentities() {
        return [self::CACHE_TAG . '_' . $this->getId() ];
    }
    public function getDefaultValues() {
        $values = [];
        return $values;
    }
}