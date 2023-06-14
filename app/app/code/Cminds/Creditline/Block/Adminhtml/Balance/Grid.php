<?php

namespace Cminds\Creditline\Block\Adminhtml\Balance;

use Magento\Backend\Block\Widget\Grid\Extended;
use Cminds\Creditline\Model\BalanceFactory;
use Cminds\Creditline\Helper\Renderer;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Helper\Data;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Grid extends Extended
{
    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var Renderer
     */
    protected $creditRenderer;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Data
     */
    protected $backendHelper;

    /**
     * @param BalanceFactory $balanceFactory
     * @param Renderer       $creditRenderer
     * @param Context        $context
     * @param Data           $backendHelper
     * @param array          $data
     */
    public function __construct(
        BalanceFactory $balanceFactory,
        Renderer $creditRenderer,
        Context $context,
        Data $backendHelper,
        array $data = []
    ) {
        $this->balanceFactory = $balanceFactory;
        $this->creditRenderer = $creditRenderer;
        $this->context = $context;
        $this->backendHelper = $backendHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid');
        $this->setDefaultSort('balance_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->balanceFactory->create()
            ->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('name', [
            'header' => __('Customer Name'),
            'index' => 'name',
            'filter_index' => new \Zend_Db_Expr('CONCAT(customer.firstname, " ", customer.lastname)'),
        ]);

        $this->addColumn('email', [
            'header' => __('Customer Email'),
            'index' => 'email',
        ]);

        $this->addColumn('amount', [
            'header' => __('Balance'),
            'index' => 'amount',
            'frame_callback' => [$this->creditRenderer, 'amount'],
        ]);

        $this->addColumn('updated_at', [
            'header' => __('Updated At'),
            'index' => 'updated_at',
            'type' => 'datetime',
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @param DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('customer/index/edit', ['id' => $row->getCustomerId()]);
    }
}
