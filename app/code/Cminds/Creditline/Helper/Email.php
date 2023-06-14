<?php
namespace Cminds\Creditline\Helper;

use Cminds\Creditline\Helper\Calculation;
use Cminds\Creditline\Model\Config;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Pricing\PriceCurrencyInterface as PricingHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Escaper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Cminds\Creditline\Model\BalanceFactory;
use Cminds\Creditline\Model\ResourceModel\Transaction\CollectionFactory;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Email extends AbstractHelper
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var PricingHelper
     */
    protected $pricingHelper;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
    * @var StateInterface
    */
    protected $inlineTranslation;

    /**
    * @var ScopeConfigInterface
    */
    protected $scopeConfig;

    /**
    * @var StoreManagerInterface
    */
    protected $storeManager;
    /**
    * @var Escaper
    */
    protected $escaper;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var TransactionFactory
     */
    protected $transactionFactory;

    /**
     * @param Calculation      $calculationHelper
     * @param Config           $config
     * @param PricingHelper    $pricingHelper
     * @param TransportBuilder $transportBuilder
     * @param Context          $context
     */
    public function __construct(
        Calculation $calculationHelper,
        Config $config,
        PricingHelper $pricingHelper,
        TransportBuilder $transportBuilder,
        BalanceFactory $earningFactory,
        CollectionFactory $transactionFactory,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager,
        Escaper $escaper,
        Context $context
    ) {
        $this->calculationHelper = $calculationHelper;
        $this->balanceFactory = $earningFactory;
        $this->transactionFactory = $transactionFactory;
        $this->config = $config;
        $this->pricingHelper = $pricingHelper;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->escaper = $escaper;

        parent::__construct($context);
    }

    /**
     * @param Transaction $transaction
     * @param bool        $force
     * @return bool
     */
    public function sendBalanceUpdateEmail($transaction, $force = false)
    {
        $balance = $transaction->getBalance();

        if (!$balance->getIsSubscribed() || !$this->config->isSendBalanceUpdate()) {
            if (!$force) {
                return false;
            }
        }

        $customer = $balance->getCustomer();
        $recipientEmail = $customer->getEmail();
        $recipientName = $customer->getName();
        $storeId = $customer->getStore()->getId();

        $amount = $this->calculationHelper->convertToCurrency(
            $balance->getAmount(),
            $balance->getCurrencyCode(),
            $transaction->getCurrencyCode(),
            $customer->getStore()
        );
        $balanceAmount = $this->pricingHelper->format(
            $amount,
            false,
            PricingHelper::DEFAULT_PRECISION,
            null,
            $transaction->getCurrencyCode()
        );
        $transactionAmount = $this->pricingHelper->format(
            $transaction->getBalanceDelta(),
            false,
            PricingHelper::DEFAULT_PRECISION,
            null,
            $transaction->getCurrencyCode()
        );
        $variables = [
            'customer'            => $customer,
            'store'               => $customer->getStore(),
            'transaction'         => $transaction,
            'balance'             => $balance,
            'transaction_amount'  => $transactionAmount,
            'balance_amount'      => $balanceAmount,
            'transaction_message' => $transaction->getEmailMessage(),
        ];

        $transport = $this->transportBuilder
            ->setTemplateIdentifier($this->config->getEmailBalanceUpdateTemplate($storeId))
            ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $storeId])
            ->setTemplateVars($variables)
            ->setFrom($this->config->getEmailSenderIdentity($storeId))
            ->addTo($recipientEmail, $recipientName)
            ->getTransport();

        $transport->sendMessage();

        return true;
    }

    public function sendInvoice($customerId,$templateId=''){

        switch ($templateId) {
            case 1:
                $templateId = 'creditline_invoice_email_template';
                break;
            case 2:
                $templateId = 'creditline_reminder_before_email_template';
                break;
            case 3:
                $templateId = 'creditline_reminder_after_email_template';
        }
        $storeId = $this->storeManager->getStore()->getId();
        $baseCurrencyCode = $this->storeManager->getStore()->getBaseCurrencyCode();
        $balance = $this->balanceFactory->create()
                        ->loadByCustomer($customerId, $baseCurrencyCode)
                        ->setTransactionCurrencyCode($baseCurrencyCode);
        $amount = abs($balance->getAmount() - $balance->getLimitAmount());
        $dueDate = $this->getDaysNumber($balance);
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        $variables = array();
        $variables['balance'] = $this->pricingHelper->format($amount, false);
        $variables['repay'] = $baseUrl.'creditline/account/';
        $variables['dueDate'] = $dueDate['due_date'];
        $this->inlineTranslation->suspend();
        $customer = $this->customerRepository->getById($customerId);
        $emailName = $this->scopeConfig->getValue('creditline/email/email_name',ScopeInterface::SCOPE_STORE);
        $emailSubject = $this->scopeConfig->getValue('creditline/email/email_subject',ScopeInterface::SCOPE_STORE);
        $senderEmail = $this->config->getEmailSender($storeId);
        $variables['emailSubject'] = $emailSubject;
        $addto = $customer->getEmail();
        try {

            $sender = [
             'name' => $emailName,
             'email' => $senderEmail,
            ];

            $storeScope = ScopeInterface::SCOPE_STORE;
            $transport = $this->transportBuilder
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                  'area' => Area::AREA_FRONTEND,
                  'store' => $storeId,
                ]
            )
            ->setTemplateVars($variables)
            ->setFrom($sender)
            ->addTo($addto)
            ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            return true;
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            return $e->getMessage();
        }
        return false;
    }

    public function paymentReminder(){
        $creditlineCollection = $this->balanceFactory->create()
            ->getCollection();
        foreach ($creditlineCollection as $balance) {
            $amount = abs($balance->getAmount() - $balance->getLimitAmount());

            if ($amount > 0) {
                $term = $balance->getCreditTerm();
                $reminders = $balance->getReminders();
                $reminder = array();
                if (strpos($reminders,',')) {
                    $reminder = explode(',', $reminders);
                }
                $customerId = $balance->getCustomerId();
                $daysNumber = $this->getDaysNumber($balance)['days_number'];
                if ($daysNumber < $term) {
                    // send reminder before term ends
                    if (in_array($daysNumber,$reminder)) {
                        $this->sendInvoice($customerId, 2);
                    }
                } else {
                    // send reminder after term ended
                    if (in_array($daysNumber,$reminder)) {
                        $this->sendInvoice($customerId,3);
                    }
                }
            }
        }
        return $this;
    }

    public function sendInvoiceReminder()
    {
        $dayNumber = $this->scopeConfig->getValue('creditline/general/number_of_days',ScopeInterface::SCOPE_STORE);
        if($dayNumber == '' || $dayNumber <= 0){
            $dayNumber = 30;
        }

        $creditlineCollection = $this->balanceFactory->create()->getCollection();
        foreach ($creditlineCollection as $balance) {
            $amount = abs($balance->getAmount() - $balance->getLimitAmount());
            if ($amount > 0) {
                $lastInvoice = $this->getUsedTrasDate($balance);
                if ($lastInvoice != 0) {
                    $customerPaymentType = $balance->getPaymentType();
                    $customerId = $balance->getCustomerId();
                    $now = date("Y-m-d H:i:s");
                    switch ($customerPaymentType) {
                        case 1:
                            if (strtotime($now) - $lastInvoice > 2629743) {
                                $this->sendInvoice($customerId,1);
                            }
                            break;
                        case 2:
                            if (strtotime($now) - $lastInvoice > $dayNumber) {
                               $this->sendInvoice($customerId,1);
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        return $this;
    }

    public function getUsedTrasDate($balance){
        $lastInvoice = 0;
        $transaction = $this->transactionFactory->create()
                ->addFieldToFilter('main_table.balance_id', $balance->getBalanceId())
                ->addFieldToFilter('main_table.action', 'used')
                ->setOrder('main_table.created_at', 'desc')
                ->getFirstItem();
        if($transaction && $transaction->getId()) {
            $lastInvoice = $transaction->getCreatedAt();
        }
        return $lastInvoice;
    }

    public function getDaysNumber($balance)
    {
        $termStarted = $this->getUsedTrasDate($balance);
        if($termStarted == 0){
            $termStarted = date('Y-m-d h:i:s');
        }

        $now = time();
        $dateDiff = $now - strtotime($termStarted);
        $daysNumber = floor($dateDiff / (60 * 60 * 24));
        $dueDate = date('Y-m-d', strtotime($termStarted. ' + '.$balance->getCreditTerm().' days'));
        $result = [
            'days_number' => $daysNumber,
            'term_started' => $termStarted,
            'term' => $balance->getCreditTerm(),
            'due_date' => $dueDate
        ];
        return $result;
    }
}
