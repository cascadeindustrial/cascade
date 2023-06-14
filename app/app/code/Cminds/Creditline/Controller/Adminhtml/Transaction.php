<?php
namespace Cminds\Creditline\Controller\Adminhtml;

use Cminds\Creditline\Helper\Calculation;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action;
use Cminds\Creditline\Model\BalanceFactory;
use Cminds\Creditline\Model\TransactionFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Cminds\Creditline\Helper\Email;
use Magento\Framework\Pricing\PriceCurrencyInterface as PricingHelper;

abstract class Transaction extends Action
{
    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var TransactionFactory
     */
    protected $transactionFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Session
     */
    protected $backendSession;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeEmail;

    /**
     * @var PricingHelper
     */
    protected $pricingHelper;

    /**
     * @param BalanceFactory     $earningFactory
     * @param TransactionFactory $transactionFactory
     * @param Calculation        $calculationHelper
     * @param Registry           $registry
     * @param FileFactory        $fileFactory
     * @param CustomerFactory    $customerFactory
     * @param Context       $context
     */
    public function __construct(
        BalanceFactory $earningFactory,
        TransactionFactory $transactionFactory,
        Calculation $calculationHelper,
        Registry $registry,
        FileFactory $fileFactory,
        CustomerFactory $customerFactory,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Email $scopeEmail,
        PricingHelper $pricingHelper,
        Context $context
    ) {
        $this->balanceFactory = $earningFactory;
        $this->transactionFactory = $transactionFactory;
        $this->calculationHelper = $calculationHelper;
        $this->registry = $registry;
        $this->fileFactory = $fileFactory;
        $this->customerFactory = $customerFactory;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->scopeEmail = $scopeEmail;
        $this->pricingHelper = $pricingHelper;
        $this->context = $context;
        $this->backendSession = $context->getSession();
        $this->resultFactory = $context->getResultFactory();
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     * @param Interceptor $resultPage
     * @return Interceptor
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Cminds_Creditline::creditline_transaction');
        $resultPage->getConfig()->getTitle()->prepend(__('Credit Line'));
       

        return $resultPage;
    }

    /**
     * @return Transaction
     */
    public function initModel()
    {
        $transaction = $this->transactionFactory->create();
        if ($this->getRequest()->getParam('id')) {
            $transaction->load($this->getRequest()->getParam('id'));
        }

        if ($this->getRequest()->getParam('customer_id')) {
            $transaction->setCustomerId($this->getRequest()->getParam('customer_id'));
        }

        $this->registry->register('current_transaction', $transaction);

        return $transaction;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Cminds_Creditline::creditline_transaction');
    }

    /**
     * @return Transaction
     */
    public function sendMail($customerId)
    {
        return $this->scopeEmail->sendInvoice($customerId,2);
    }
}
