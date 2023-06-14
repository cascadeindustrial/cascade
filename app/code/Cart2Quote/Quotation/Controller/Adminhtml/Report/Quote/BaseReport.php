<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Adminhtml\Report\Quote;

/**
 * Class BaseReport
 * @package Cart2Quote\Quotation\Controller\Adminhtml\Report\Quote
 */
class BaseReport extends \Magento\Reports\Controller\Adminhtml\Report\Sales
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        //empty base function
    }

    /**
     * Create Grid Block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    public function createGrid()
    {
        $grid = $this->_view->getLayout()->createBlock(\Cart2Quote\Quotation\Block\Adminhtml\Report\Quotereport\Grid::class);
        $this->_initReportAction($grid);

        return $grid;
    }
}
