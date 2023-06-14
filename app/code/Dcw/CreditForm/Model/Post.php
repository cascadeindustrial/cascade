<?php
namespace Dcw\CreditForm\Model;
class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'creditform';

	protected $_cacheTag = 'creditform';

	protected $_eventPrefix = 'creditform';

	protected function _construct()
	{
		$this->_init('Dcw\CreditForm\Model\ResourceModel\Post');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}
