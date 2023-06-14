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
class Repaymanually extends Transaction
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($customerId = $this->getRequest()->getParam('customer_id')) {
            
            try {

                $baseCurrencyCode = $this->storeManager->getStore()->getBaseCurrencyCode();
                $balance = $this->balanceFactory->create()
                            ->loadByCustomer($customerId, $baseCurrencyCode)
                            ->setTransactionCurrencyCode($baseCurrencyCode);

                $amount = abs($balance->getAmount() - $balance->getLimitAmount());
                if($amount > 0){
                    $customer = $this->customerFactory->create()->load($customerId);
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
                        'Paid By Admin'
                    );
                    $this->messageManager->addSuccessMessage(__('Transaction was successfully saved.'));
                 }else{
                    $this->messageManager->addSuccessMessage(__('Amount Should more than 0.'));
                 }
                 $this->_redirect('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_redirect('*/*/');
            }
        }else{
            $this->messageManager->addErrorMessage(__('Unable to find transaction to save.'));
            $this->_redirect('*/*/');
        }
    }
}
