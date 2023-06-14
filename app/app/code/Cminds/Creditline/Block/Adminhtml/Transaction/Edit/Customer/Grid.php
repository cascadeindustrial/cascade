<?php


namespace Cminds\Creditline\Block\Adminhtml\Transaction\Edit\Customer;

use Magento\User\Model\ResourceModel\Role\User\CollectionFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\Json\Helper\Data;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Helper\Data as HelperData;
use Magento\Backend\Block\Widget\Grid\Extended;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Grid extends Extended
{
    /**
     * @var Collection
     */
    protected $rolesFactory;

    /**
     * @var CollectionFactory
     */
    protected $customerCollectionFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Data
     */
    protected $jsonEncoder;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Data
     */
    protected $backendHelper;

    /**
     * @param CollectionFactory    $rolesFactory
     * @param CollectionFactory $customerCollectionFactory
     * @param Registry          $registry
     * @param Data              $jsonEncoder
     * @param Context           $context
     * @param Data              $backendMessageHelper
     * @param array             $data
     */
    public function __construct(
        CollectionFactory  $rolesFactory,
        CustomerCollectionFactory $customerCollectionFactory,
        Registry $registry,
        Data $jsonEncoder,
        Context $context,
        HelperData $backendMessageHelper,
        array $data = []
    ) {
        $this->rolesFactory = $rolesFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->context = $context;
        $this->backendHelper = $backendMessageHelper;
        parent::__construct($context, $backendMessageHelper, $data);
    }

    /**
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('creditline_transaction_edit_customer_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareCollection()
    {
        $collection = $this->customerCollectionFactory->create()
            ->addNameToSelect()
            ->addAttributeToSelect('email');

        if (
            $this->registry->registry('current_transaction') &&
            $this->registry->registry('current_transaction')->getCustomerId() > 0
        ) {
            $collection->addFieldToFilter(
                'entity_id',
                $this->registry->registry('current_transaction')->getCustomerId()
            );
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', [
            'header' => __('ID'),
            'width' => '50px',
            'index' => 'entity_id',
            'align' => 'right',
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'index' => 'name',
        ]);

        $this->addColumn('email', [
            'header' => __('Email'),
            'index' => 'email',
        ]);

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('customer_id');

        $this->getMassactionBlock()->addItem('select', [
            'label' => __('Select'),
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/loadCustomerBlock', ['block' => 'customer_grid']);
    }

    /**
     * @param bool|false $json
     * @return array|string
     */
    protected function _getUsers($json = false)
    {
        if ($this->getRequest()->getParam('in_role_user') != '') {
            return $this->getRequest()->getParam('in_role_user');
        }

        $roleId = $this->getRequest()->getParam('rid') > 0
            ? $this->getRequest()->getParam('rid')
            : $this->registry->registry('RID');

        $users = $this->rolesFactory->create()->setId($roleId)->getRoleUsers();

        if (sizeof($users) > 0) {
            if ($json) {
                $jsonUsers = [];

                foreach ($users as $usrid) {
                    $jsonUsers[$usrid] = 0;
                }

                return $this->jsonEncoder->jsonEncode((object) $jsonUsers);
            } else {
                return array_values($users);
            }
        } else {
            if ($json) {
                return '{}';
            } else {
                return [];
            }
        }
    }
}
