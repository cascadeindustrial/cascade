<?php
namespace MageMaclean\MyShipping\Controller\Account;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
class Index extends AbstractAccount implements HttpGetActionInterface
{
    public function execute()
    {
        $accounts = $this->_accountRepository->getListByCustomerId($this->_getSession()->getCustomerId());
        if (count($accounts)) {
            /** @var \Magento\Framework\View\Result\Page $resultPage */
            $resultPage = $this->resultPageFactory->create();
            $block = $resultPage->getLayout()->getBlock('myshipping_account_list');
            if ($block) {
                $block->setRefererUrl($this->_redirect->getRefererUrl());
            }
            return $resultPage;
        } else {
            return $this->resultRedirectFactory->create()->setPath('*/*/new');
        }
    }
}
