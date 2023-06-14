<?php


namespace Cminds\Creditline\Block\Adminhtml;

use Magento\Framework\DataObject;
use Magento\Backend\Block\Template\Context;
use Cminds\Creditline\Block\Adminhtml\AbstractMenu;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Menu extends AbstractMenu
{
    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->visibleAt(['credit']);

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildMenu()
    {
        $this->addItem([
            'resource' => 'Cminds_Creditline::creditline_transaction',
            'title'    => __('Transactions'),
            'url'      => $this->urlBuilder->getUrl('creditline/transaction/index'),
        ])->addItem([
            'resource' => 'Cminds_Creditline::creditline_balance',
            'title'    => __('Customers'),
            'url'      => $this->urlBuilder->getUrl('creditline/balance/index'),
        ]);

        $this->addSeparator();

        $this->addItem([
            'resource' => 'Cminds_Creditline::creditline_config',
            'title'    => __('Settings'),
            'url'      => $this->urlBuilder->getUrl('adminhtml/system_config/edit/section/credit'),
        ]);

        return $this;
    }
}
