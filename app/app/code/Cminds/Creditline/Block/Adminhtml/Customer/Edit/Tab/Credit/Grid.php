<?php


namespace Cminds\Creditline\Block\Adminhtml\Customer\Edit\Tab\Credit;

use Magento\Customer\Controller\RegistryConstants;
use Cminds\Creditline\Model\Config\Source\Action;
use Magento\Backend\Block\Widget\Grid\Extended;
use Cminds\Creditline\Model\ResourceModel\Transaction\CollectionFactory;
use Cminds\Creditline\Helper\Renderer;
use Magento\Framework\Registry;
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
     * @var CollectionFactory
     */
    protected $transactionCollectionFactory;

    /**
     * @var Renderer
     */
    protected $creditRenderer;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Action
     */
    protected $action;

    /**
     * @param CollectionFactory $earningCollectionFactory
     * @param Renderer          $creditRenderer
     * @param Action            $action
     * @param Registry          $registry
     * @param Context           $context
     * @param Data              $backendMessageHelper
     */
    public function __construct(
        CollectionFactory $earningCollectionFactory,
        Renderer $creditRenderer,
        Action $action,
        Registry $registry,
        Context $context,
        Data $backendMessageHelper
    ) {
        $this->transactionCollectionFactory = $earningCollectionFactory;
        $this->creditRenderer = $creditRenderer;
        $this->action = $action;
        $this->registry = $registry;
        $this->context = $context;

        parent::__construct($context, $backendMessageHelper);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setDefaultSort('updated_at');
        $this->setDefaultDir('desc');

        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
        $this->setDefaultLimit(100);

        $this->setEmptyText(__('No Transactions Found'));
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->transactionCollectionFactory->create()
            ->addFilterByCustomer($this->registry->registry(RegistryConstants::CURRENT_CUSTOMER_ID));

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('transaction_id', [
            'header'   => __('Transaction #'),
            'type'     => 'number',
            'index'    => 'transaction_id',
            'width'    => '50px',
            'sortable' => false,
        ]);

        $this->addColumn('updated_at', [
            'header'   => __('Purchase On'),
            'index'    => 'updated_at',
            'type'     => 'datetime',
            'sortable' => false,
        ]);

        $this->addColumn('balance_delta', [
            'header'         => __('Balance Change'),
            'index'          => 'balance_delta',
            'frame_callback' => [$this->creditRenderer, 'amountDelta'],
            'sortable'       => false,
        ]);

        $this->addColumn('balance_amount', [
            'header'         => __('Balance Refill'),
            'index'          => 'balance_amount',
            'frame_callback' => [$this->creditRenderer, 'amount'],
            'sortable'       => false,
        ]);

        $this->addColumn('action', [
            'header'       => __('Action'),
            'index'        => 'action',
            'filter_index' => 'main_table.action',
            'sortable'     => false,
            'type'         => 'options',
            'options'      => $this->action->toOptionHash(),
        ]);

        $this->addColumn('message', [
            'header'         => __('Order'),
            'index'          => 'message',
            'filter_index'   => 'main_table.message',
            'sortable'       => false,
            'frame_callback' => [$this->creditRenderer, 'transactionMessage'],
        ]);

        return parent::_prepareColumns();
    }
}
