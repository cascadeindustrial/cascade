<?php
namespace Dcw\Override\Block\Adminhtml\Attribute\Edit\Tab\Options;

class Options extends \Amasty\Orderattr\Block\Adminhtml\Attribute\Edit\Tab\Options\Options
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Dcw_Override::attribute/options.phtml');
    }
}
