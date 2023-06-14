<?php
namespace Taxjar\SalesTax\Observer\BackfillTransactions;

/**
 * Proxy class for @see \Taxjar\SalesTax\Observer\BackfillTransactions
 */
class Proxy extends \Taxjar\SalesTax\Observer\BackfillTransactions implements \Magento\Framework\ObjectManager\NoninterceptableInterface
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Proxied instance name
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Proxied instance
     *
     * @var \Taxjar\SalesTax\Observer\BackfillTransactions
     */
    protected $_subject = null;

    /**
     * Instance shareability flag
     *
     * @var bool
     */
    protected $_isShared = null;

    /**
     * Proxy constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     * @param bool $shared
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Taxjar\\SalesTax\\Observer\\BackfillTransactions', $shared = true)
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
        $this->_isShared = $shared;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['_subject', '_isShared', '_instanceName'];
    }

    /**
     * Retrieve ObjectManager from global scope
     */
    public function __wakeup()
    {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * Clone proxied instance
     */
    public function __clone()
    {
        $this->_subject = clone $this->_getSubject();
    }

    /**
     * Get proxied instance
     *
     * @return \Taxjar\SalesTax\Observer\BackfillTransactions
     */
    protected function _getSubject()
    {
        if (!$this->_subject) {
            $this->_subject = true === $this->_isShared
                ? $this->_objectManager->get($this->_instanceName)
                : $this->_objectManager->create($this->_instanceName);
        }
        return $this->_subject;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        return $this->_getSubject()->execute($observer);
    }

    /**
     * {@inheritdoc}
     */
    public function getDateRange() : array
    {
        return $this->_getSubject()->getDateRange();
    }

    /**
     * {@inheritdoc}
     */
    public function setDateRange() : \Taxjar\SalesTax\Observer\BackfillTransactions
    {
        return $this->_getSubject()->setDateRange();
    }

    /**
     * {@inheritdoc}
     */
    public function success(int $count = 0) : void
    {
        $this->_getSubject()->success($count);
    }

    /**
     * {@inheritdoc}
     */
    public function fail($e) : void
    {
        $this->_getSubject()->fail($e);
    }

    /**
     * {@inheritdoc}
     */
    public function syncTransactions(array $orders) : void
    {
        $this->_getSubject()->syncTransactions($orders);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrders(\Magento\Framework\Api\SearchCriteriaInterface $criteria) : array
    {
        return $this->_getSubject()->getOrders($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchCriteria(string $from, string $to) : \Magento\Framework\Api\SearchCriteriaInterface
    {
        return $this->_getSubject()->getSearchCriteria($from, $to);
    }
}
