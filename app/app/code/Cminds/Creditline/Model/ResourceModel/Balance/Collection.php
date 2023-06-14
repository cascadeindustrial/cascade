<?php


namespace Cminds\Creditline\Model\ResourceModel\Balance;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\DB\Select;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Cminds\Creditline\Model\Balance', 'Cminds\Creditline\Model\ResourceModel\Balance');
    }

    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->joinCustomer();

        return $this;
    }

    /**
     * @return $this
     */
    protected function joinCustomer()
    {
        $nameExpr = new \Zend_Db_Expr('CONCAT(customer.firstname, " ", customer.lastname)');

        $this->getSelect()->joinLeft(
            ['customer' => $this->getTable('customer_entity')],
            'main_table.customer_id = customer.entity_id',
            ['email' => 'email', 'name' => $nameExpr]
        );

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalBalance()
    {
        $this->_renderFilters();

        $select = clone $this->getSelect();
        $select->reset(Select::ORDER)
            ->reset(Select::LIMIT_COUNT)
            ->reset(Select::LIMIT_OFFSET)
            ->reset(Select::COLUMNS)
            ->columns('SUM(amount)');

        return $this->getConnection()->fetchOne($select);
    }
}
