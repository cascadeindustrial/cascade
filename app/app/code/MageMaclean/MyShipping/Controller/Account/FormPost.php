<?php
namespace MageMaclean\MyShipping\Controller\Account;

use Magento\Framework\Exception\InputException;


class FormPost extends AbstractAccount implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    protected function _extractAccount()
    {
        $formAccountData = $this->getFormAccountData();
        $accountData = array();

        $accountDataObject = $this->_accountFactory->create();
        if ($accountId = $this->getRequest()->getParam('id')) {

            $accountDataObject->load($accountId);
            if($accountDataObject->getCustomerId() != $this->_getSession()->getCustomerId()) {
                throw new \Exception();
            }
        } else {
            $accountDataObject->setCustomerId($this->_getSession()->getCustomerId());
        }

        $this->dataObjectHelper->populateWithArray(
            $accountDataObject,
            $formAccountData,
            \MageMaclean\MyShipping\Api\Data\AccountInterface::class
        );


        return $accountDataObject;
    }

    protected function getFormAccountData() {
        $formAccountData = [];
        $postData = $this->getRequest()->getPost();
        if($postData) {
            $formAccountData["myshipping_courier_id"] = $postData["myshipping_courier_id"];
            $formAccountData["myshipping_account"] = $postData["myshipping_account"];
        }
        return $formAccountData;
    }

    public function execute()
    {
        $redirectUrl = null;
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        if (!$this->getRequest()->isPost()) {
            $this->_getSession()->setMyshippingAccountFormData($this->getRequest()->getPostValue());
            return $this->resultRedirectFactory->create()->setUrl(
                $this->_redirect->error($this->_buildUrl('*/*/edit'))
            );
        }

        try {
            $account = $this->_extractAccount();
            $this->_accountRepository->save($account);
            $this->messageManager->addSuccessMessage(__('You saved the account.'));
            $url = $this->_buildUrl('*/*/index', ['_secure' => true]);
            return $this->resultRedirectFactory->create()->setUrl($this->_redirect->success($url));
        } catch (InputException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            foreach ($e->getErrors() as $error) {
                $this->messageManager->addErrorMessage($error->getMessage());
            }
        } catch (\Exception $e) {
            $redirectUrl = $this->_buildUrl('*/*/index');
            $this->messageManager->addExceptionMessage($e, __('We can\'t save the account.'));
        }

        $url = $redirectUrl;
        if (!$redirectUrl) {
            $this->_getSession()->setMyshippingAccountFormData($this->getRequest()->getPostValue());
            $url = $this->_buildUrl('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->error($url));
    }
}
