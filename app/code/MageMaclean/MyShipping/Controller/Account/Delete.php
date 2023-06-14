<?php
namespace MageMaclean\MyShipping\Controller\Account;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;

class Delete extends AbstractAccount implements HttpPostActionInterface, HttpGetActionInterface
{
    public function execute()
    {
        $accountId = $this->getRequest()->getParam('id', false);

        if ($accountId && $this->_formKeyValidator->validate($this->getRequest())) {
            try {
                $account = $this->_accountRepository->getById($accountId);
                if ($account->getCustomerId() == $this->_getSession()->getCustomerId()) {
                    $this->_accountRepository->delete($account);
                    $this->messageManager->addSuccess(__('You deleted the account.'));
                } else {
                    $this->messageManager->addError(__('We can\'t delete the account right now.'));
                }
            } catch (\Exception $other) {
                $this->messageManager->addException($other, __('We can\'t delete the account right now.'));
            }
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
}
