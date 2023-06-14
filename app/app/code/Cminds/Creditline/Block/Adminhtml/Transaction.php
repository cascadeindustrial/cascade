<?php

namespace Cminds\Creditline\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Transaction extends Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_controller = 'adminhtml_transaction';
        $this->_blockGroup = 'Cminds_Creditline';
        $this->_headerText = __('Transactions');
        $this->_addButtonLabel = __('Add New Transaction');
    }

    /**
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/add');
    }

    /**
     * @return string
     */
    public function getAddButtonLabel()
    {
        return __('Add New Transaction');
    }
}
