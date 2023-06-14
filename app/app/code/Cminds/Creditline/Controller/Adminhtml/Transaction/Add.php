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
class Add extends Transaction
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->getConfig()->getTitle()->prepend(__('New Transaction'));

        $this->initModel();

        $this->registry->register('referrer_url', $this->_redirect->getRefererUrl());

        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(__('New Transaction'));

        $this->_addContent($resultPage->getLayout()->createBlock('Cminds\Creditline\Block\Adminhtml\Transaction\Edit'));

        return $resultPage;
    }
}
