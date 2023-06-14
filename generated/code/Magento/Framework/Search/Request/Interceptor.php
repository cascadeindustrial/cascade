<?php
namespace Magento\Framework\Search\Request;

/**
 * Interceptor class for @see \Magento\Framework\Search\Request
 */
class Interceptor extends \Magento\Framework\Search\Request implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct($name, $indexName, \Magento\Framework\Search\Request\QueryInterface $query, $from = null, $size = null, array $dimensions = [], array $buckets = [], $sort = [])
    {
        $this->___init();
        parent::__construct($name, $indexName, $query, $from, $size, $dimensions, $buckets, $sort);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getName');
        if (!$pluginInfo) {
            return parent::getName();
        } else {
            return $this->___callPlugins('getName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIndex');
        if (!$pluginInfo) {
            return parent::getIndex();
        } else {
            return $this->___callPlugins('getIndex', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDimensions()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDimensions');
        if (!$pluginInfo) {
            return parent::getDimensions();
        } else {
            return $this->___callPlugins('getDimensions', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregation()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAggregation');
        if (!$pluginInfo) {
            return parent::getAggregation();
        } else {
            return $this->___callPlugins('getAggregation', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQuery');
        if (!$pluginInfo) {
            return parent::getQuery();
        } else {
            return $this->___callPlugins('getQuery', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFrom');
        if (!$pluginInfo) {
            return parent::getFrom();
        } else {
            return $this->___callPlugins('getFrom', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSize');
        if (!$pluginInfo) {
            return parent::getSize();
        } else {
            return $this->___callPlugins('getSize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSort()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSort');
        if (!$pluginInfo) {
            return parent::getSort();
        } else {
            return $this->___callPlugins('getSort', func_get_args(), $pluginInfo);
        }
    }
}
