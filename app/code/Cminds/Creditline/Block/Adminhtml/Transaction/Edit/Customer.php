<?php


namespace Cminds\Creditline\Block\Adminhtml\Transaction\Edit;

use Magento\Backend\Block\Widget;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Customer extends Widget
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('transaction/edit/customer.phtml');
    }

    /**
     * @return null|string
     * @throws LocalizedException
     */
    public function getGridBlock()
    {
        if (!$this->hasGridBlock()) {
            $this->setData(
                'grid_block',
                $this->getLayout()->createBlock('\Cminds\Creditline\Block\Adminhtml\Transaction\Edit\Customer\Grid')
            );
        }

        return $this->getData('grid_block');
    }
}
