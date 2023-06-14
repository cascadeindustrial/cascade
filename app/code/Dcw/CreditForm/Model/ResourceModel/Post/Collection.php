<?php
namespace Dcw\CreditForm\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'creditform';
	protected $_eventObject = 'post_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Dcw\CreditForm\Model\Post', 'Dcw\CreditForm\Model\ResourceModel\Post');
	}

}
