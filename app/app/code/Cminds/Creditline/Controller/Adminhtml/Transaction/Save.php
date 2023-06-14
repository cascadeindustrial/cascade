<?php
namespace Cminds\Creditline\Controller\Adminhtml\Transaction;

use Magento\Framework\Controller\ResultFactory;
use Cminds\Creditline\Controller\Adminhtml\Transaction;
use Cminds\Creditline\Model\Transaction as CCTransaction;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Save extends Transaction
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if ($data = $this->getRequest()->getParams()) {
            $customers = explode(',', $data['customer_id']);

            try {
                if (!(float)$data['balance_delta']) {
                    throw new \Exception(
                        __('Credit Line Balance should be a valid number.')
                    );
                }
                foreach ($customers as $customerId) {
                    $balance = $this->balanceFactory->create()
                        ->loadByCustomer($customerId, $data['currency_code'])
                        ->setTransactionCurrencyCode($data['currency_code'])
                    ;

                    /** @var Customer $customer */
                    $customer = $this->customerFactory->create()->load($customerId);
                    $amount = $data['balance_delta'];
                    $baseAmount = $this->calculationHelper->convertToCurrency(
                        $amount,
                        $balance->getTransactionCurrencyCode(),
                        $balance->getCurrencyCode(),
                        $customer->getStore()
                    );
                    $balance->addTransaction(
                        $amount,
                        $baseAmount,
                        CCTransaction::ACTION_MANUAL,
                        $data['message']
                    );
                }

                $this->messageManager->addSuccessMessage(__('Transaction was successfully saved.'));

                $this->backendSession->setFormData(false);

                if ($this->getRequest()->getParam('referrer_url')) {
                    return $this->resultRedirectFactory->create()
                        ->setUrl($this->getRequest()->getParam('referrer_url'));
                }

                $this->_redirect('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->backendSession->setFormData($data);

                if ($this->getRequest()->getParam('id')) {
                    $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                } else {
                    $this->_redirect('*/*/add');
                }
            }
        }

        $this->messageManager->addErrorMessage(__('Unable to find transaction to save.'));

        $this->_redirect('*/*/');
    }
}
