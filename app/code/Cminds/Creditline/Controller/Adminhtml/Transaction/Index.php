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
class Index extends Transaction
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage);

        $this->_addContent($resultPage->getLayout()
            ->createBlock('Cminds\Creditline\Block\Adminhtml\Transaction'));

        $this->_addContent($resultPage->getLayout()
            ->createBlock('Cminds\Creditline\Block\Adminhtml\Transaction\QuickReport', 'credit.quick.report'));

        $resultPage->getLayout()->setChild('page.main.actions', 'credit.quick.report', 'credit.quick.report');

        return $resultPage;
    }
}
