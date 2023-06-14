<?php
namespace Cminds\Creditline\Controller\Adminhtml\Transaction;

use Magento\Framework\Controller\ResultFactory;
use Cminds\Creditline\Controller\Adminhtml\Transaction;
use Cminds\Creditline\Model\Transaction as CCTransaction;
use Magento\Store\Model\ScopeInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Defaultsettings extends Transaction
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($customerId = $this->getRequest()->getParam('customer_id')){
            
            try {

                $baseCurrencyCode = $this->storeManager->getStore()->getBaseCurrencyCode();
                $balance = $this->balanceFactory->create()
                            ->loadByCustomer($customerId, $baseCurrencyCode)
                            ->setTransactionCurrencyCode($baseCurrencyCode);

                if ($balance->getBalanceId()) {
                    $limitamount = $this->scopeConfig->getValue('creditline/general/creditline_default',ScopeInterface::SCOPE_STORE);

                    $creditterm = $this->scopeConfig->getValue('creditline/general/credit_term',ScopeInterface::SCOPE_STORE);
                    

                    $pmtreminder = $this->scopeConfig->getValue('creditline/general/creditline_select',ScopeInterface::SCOPE_STORE);

                    $reminders = $this->scopeConfig->getValue('creditline/general/number_of_days',ScopeInterface::SCOPE_STORE);

                    $balance->setBalanceId($balance->getBalanceId())
                        ->setLimitAmount($limitamount)
                        ->setCreditTerm($creditterm)
                        ->setReminders($reminders)
                        ->setPaymentType($pmtreminder)
                        ->save();
                    
                    $this->messageManager->addSuccessMessage(__('Transaction was successfully saved.'));
                    $this->_redirect('*/*/');
                }else{
                    $this->messageManager->addErrorMessage(__('Unable to find transaction to save.'));
                    $this->_redirect('*/*/');
                }

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
