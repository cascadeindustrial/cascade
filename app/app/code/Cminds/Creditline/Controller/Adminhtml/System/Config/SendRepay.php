<?php
namespace Cminds\Creditline\Controller\Adminhtml\System\Config;

use Magento\Catalog\Model\Product\Visibility;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;
use Cminds\Creditline\Helper\Email;
use Magento\Customer\Model\Customer;
use Magento\Store\Model\StoreManagerInterface;
use Cminds\Creditline\Model\BalanceFactory;
use Magento\Framework\Controller\ResultFactory;

class SendRepay extends Action
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeEmail;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Email $scopeEmail,
        Customer $customer,
        StoreManagerInterface $storeManager,
        BalanceFactory $earningFactory,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->scopeEmail = $scopeEmail;
        $this->customer = $customer;
        $this->storeManager = $storeManager;
        $this->balanceFactory = $earningFactory;
        parent::__construct($context);
    }


    /**
     * Send Repay Email
     *
     * @return void
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $customerObj = $this->customer->getCollection();
        $icount = 0;
        foreach($customerObj as $customer )
        {
            $customerId = $customer->getId();
            $baseCurrencyCode = $this->storeManager->getStore()->getBaseCurrencyCode();
            $balance = $this->balanceFactory->create()
                        ->loadByCustomer($customerId, $baseCurrencyCode)
                        ->setTransactionCurrencyCode($baseCurrencyCode);

            if ($balance->getBalanceId()) {
                $amount = abs($balance->getAmount() - $balance->getLimitAmount());
                if($amount > 0){
                    if($this->scopeEmail->sendInvoice($customerId,2)){
                        $icount++;
                    }
                }
            }
        }
        $this->messageManager->addSuccessMessage(__($icount.' No of Emails successfully sent.'));
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}