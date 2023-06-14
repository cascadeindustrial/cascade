<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Model\ResourceModel\Quote\Item;

/**
 * Section resourcemodel
 */
class Section extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    use \Cart2Quote\Features\Traits\Model\ResourceModel\Quote\Item\Section {
        _construct as private _traitConstruct;
    }

    /**
     * Items table
     *
     * @var string
     */
    protected $itemsTable;

    /**
     * @var string
     */
    protected $connectionName = 'checkout';

    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_traitConstruct();
    }

    /**
     * Section constructor.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null|string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = 'checkout'
    ) {
        parent::__construct($context, $connectionName);
    }
}
