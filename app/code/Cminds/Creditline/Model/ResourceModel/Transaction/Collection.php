<?php


namespace Cminds\Creditline\Model\ResourceModel\Transaction;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\DB\Select;
use Cminds\Creditline\Model\Transaction;

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
        $this->_init('Cminds\Creditline\Model\Transaction', 'Cminds\Creditline\Model\ResourceModel\Transaction');
    }

    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->joinBalance()
            ->joinCustomer();

        return $this;
    }

    /**
     * @return $this
     */
    protected function joinBalance()
    {
        $this->getSelect()->joinLeft(
            ['balance' => $this->getTable('cminds_creditline_balance')],
            'main_table.balance_id = balance.balance_id',
            ['balance_currency_code' => 'currency_code']
        );

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
            'balance.customer_id = customer.entity_id',
            ['email' => 'email', 'customer_id' => 'entity_id', 'name' => $nameExpr, 'customer.website_id']
        );

        return $this;
    }

    /**
     * @param int $customerId
     * @return $this
     */
    public function addFilterByCustomer($customerId)
    {
        $this->getSelect()
            ->where('balance.customer_id = ?', intval($customerId));

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalUsedAmount()
    {
        $this->_renderFilters();

        $select = clone $this->getSelect();
        $select->reset(Select::ORDER)
            ->reset(Select::LIMIT_COUNT)
            ->reset(Select::LIMIT_OFFSET)
            ->reset(Select::COLUMNS)
            ->columns('SUM(balance_delta)')
            ->where('action=?', Transaction::ACTION_USED);

        return abs($this->getConnection()->fetchOne($select));
    }
}
