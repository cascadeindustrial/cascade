<?php
namespace Cminds\Creditline\Controller\Adminhtml\Transaction;

use Magento\Framework\Controller\ResultFactory;
use Cminds\Creditline\Controller\Adminhtml\Transaction;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Export extends Transaction
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        /** @var Grid $grid */
        $grid = $resultPage->getLayout()
            ->createBlock('Cminds\Creditline\Block\Adminhtml\Transaction\Grid', 'transaction.grid');

        if ($this->getRequest()->getParam('type') == 'xml') {
            return $this->fileFactory->create('export.xml', $grid->getXmlFile(), 'var');
        } else {
            return $this->fileFactory->create('export.csv', $grid->getCsvFile(), 'var');
        }
    }
}
