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
class LoadCustomerBlock extends Transaction
{
    /**
     * @return void
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $result = $resultPage->getLayout()
            ->createBlock('\Cminds\Creditline\Block\Adminhtml\Transaction\Edit\Customer')->toHtml();

        $this->getResponse()->setBody($result);
    }
}
