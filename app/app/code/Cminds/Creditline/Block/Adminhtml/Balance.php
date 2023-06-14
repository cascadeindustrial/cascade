<?php

namespace Cminds\Creditline\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Balance extends Container
{
    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_controller = 'adminhtml_balance';
        $this->_blockGroup = 'Cminds_Creditline';
        $this->_headerText = __('Customers');
        $this->buttonList->remove('add');
    }
}
