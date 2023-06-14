<?php
namespace Cminds\Creditline\Controller\Adminhtml\Invoice;

use Magento\Framework\Controller\ResultFactory;
use Cminds\Creditline\Controller\Adminhtml\Transaction;
use Cminds\Creditline\Model\Transaction as CCTransaction;
use Magento\Store\Model\ScopeInterface;

class Send extends Transaction{

	/**
	 * @return void
	 */
	public function execute()
	{
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
	    if ($customerId = $this->getRequest()->getParam('customer_id')){
	    	$baseCurrencyCode = $this->storeManager->getStore()->getBaseCurrencyCode();
            $balance = $this->balanceFactory->create()
                        ->loadByCustomer($customerId, $baseCurrencyCode)
                        ->setTransactionCurrencyCode($baseCurrencyCode);

            if ($balance->getBalanceId()) {
            	$amount = abs($balance->getAmount() - $balance->getLimitAmount());
                if($amount > 0){
                	if($this->sendMail($customerId)){
                		$this->messageManager->addSuccessMessage(__('Email successfully sent.'));
                	}else{
                		$this->messageManager->addErrorMessage(__('Email not sent.'));
                	}
                }else{
                	$this->messageManager->addErrorMessage(__('Amount Must Greater Than 0.'));
                }
            }
	    }
	    $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
	}
}