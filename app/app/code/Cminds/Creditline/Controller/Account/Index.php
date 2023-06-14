<?php


namespace Cminds\Creditline\Controller\Account;

use Magento\Framework\Controller\ResultFactory;
use Cminds\Creditline\Controller\Account;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Index extends Account
{
    /**
     * @return Page
     */
    public function execute()
    {

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if ($head = $resultPage->getLayout()->getBlock('head')) {
            $head->setTitle(__('My Credit Line'));
        }

        $resultPage->getConfig()->getTitle()->set(__('My Credit Line'));

        return $resultPage;
    }
}
