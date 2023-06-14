<?php
namespace Cminds\Creditline\Plugin\Customer\Controller\Adminhtml\Index;

use Magento\Customer\Controller\Adminhtml\Index\Save as SaveCustomer;
use Cminds\Creditline\Model\Balance;

class Save extends Balance
{
    public function afterExecute(
        SaveCustomer $subject,
        $result
    ) {
        $customer = $subject->getRequest()->getParam('customer');

        if (isset($customer['entity_id'])) {
            $customerId     = $customer['entity_id'];
            $creditTerm     = $subject->getRequest()->getParam('credit_term');
            $paymentType    = $subject->getRequest()->getParam('payment_type');
            $creditReminders = $subject->getRequest()->getParam('credit_reminders');
            $amountCredit   = $subject->getRequest()->getParam('amount_of_credit');

            $baseCurrencyCode = $this->storeManager->getStore()->getBaseCurrencyCode();

            /** @var Balance $balance */
            $balance = $this->balanceCollectionFactory->create()
                ->addFieldToFilter('customer_id', $customerId)
                ->addFieldToFilter('currency_code', $baseCurrencyCode)
                ->getFirstItem();

            if ($balance->getBalanceId()) {
                $this->setBalanceId($balance->getBalanceId())
                    ->setCreditTerm($creditTerm)
                    ->setReminders($creditReminders)
                    ->setLimitAmount($amountCredit)
                    ->setPaymentType($paymentType)
                    ->save();
            }
        }

        return $result;
    }
}
