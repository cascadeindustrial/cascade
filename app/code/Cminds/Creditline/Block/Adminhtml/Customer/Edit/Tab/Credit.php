<?php


namespace Cminds\Creditline\Block\Adminhtml\Customer\Edit\Tab;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Backend\Block\Widget\Form;
use Cminds\Creditline\Helper\Data;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Data\FormFactory;
use Magento\Backend\Model\Url;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;
use Magento\Customer\Model\Customer;
/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Credit extends Form
{
    /**
     * @var Data
     */
    protected $creditHelper;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Url
     */
    protected $backendUrlManager;

    /**
     * @var Registry
     */
    protected $registry;


    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Customer
     */
    private $customerRepository;

    /**
     * @var CurrencyInterface
     */
    private $currency;

    public function __construct(
        Data $creditHelper,
        DateTime $date,
        FormFactory $formFactory,
        Url $backendUrlManager,
        Registry $registry,
        Context $context,
        Customer $customerRepository,
        CurrencyInterface $currency,
        array $data = []
    ) {
        $this->creditHelper = $creditHelper;
        $this->date = $date;
        $this->formFactory = $formFactory;
        $this->backendUrlManager = $backendUrlManager;
        $this->registry = $registry;
        $this->context = $context;
        $this->customerRepository = $customerRepository;
        $this->currency = $currency;

        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTitle(__('Credit Line'));

        $this->setTemplate('customer/edit/tab/credit.phtml');
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->getTitle();
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTitle();
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getCustomer()) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getAfter()
    {
        return 'tags';
    }

    /**
     * @return Customer|bool
     */
    public function getCustomer()
    {
        if ($customerId = $this->registry->registry(RegistryConstants::CURRENT_CUSTOMER_ID)) {
            $customerData = $this->customerRepository->load($customerId);

            return $customerData;
        }

        return false;
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareLayout()
    {
        if ($this->getCustomer()) {
            $this->setChild(
                'form',
                $this->getLayout()
                    ->createBlock('\Cminds\Creditline\Block\Adminhtml\Customer\Edit\Tabs', 'credit.form')
            );
            $this->setChild(
                'grid',
                $this->getLayout()
                    ->createBlock('\Cminds\Creditline\Block\Adminhtml\Customer\Edit\Tab\Credit\Grid', 'credit.grid')
            );
        }

        return parent::_prepareLayout();
    }

    /**
     * @return Collection
     */
    public function getBalances()
    {
        return $this->creditHelper->getCustomerBalances($this->getCustomer()->getId());
    }

    /**
     * @param Balance $balance
     * @return string
     */
    public function getBalanceAmount($balance)
    {
        return $this->currency->getCurrency($balance->getCurrencyCode())
            ->toCurrency($balance->getAmount());
    }

    /**
     * @param Balance $balance
     * @return string
     */
    public function getBalanceLimitAmount($balance)
    {
        return $this->currency->getCurrency($balance->getCurrencyCode())
            ->toCurrency($balance->getLimitAmount());
    }

    /**
     * @param Balance $balance
     * @return string
     */
    public function getBalanceCurrencyName($balance)
    {
        return $this->currency->getCurrency($balance->getCurrencyCode())
            ->getName();
    }

    /**
     * @return string
     */
    public function getLastChanged()
    {
        $updatedAt = strtotime($this->getBalance()->getUpdatedAt());

        return $updatedAt > 0 ? $this->date->date('M, d Y h:i A', $updatedAt) : '-';
    }

    /**
     * @return Phrase
     */
    public function getIsSubscribed()
    {
        return $this->getBalance()->getIsSubscribed() ? __('Yes') : __('No');
    }

    /**
     * @return Balance
     */
    public function getCreditTerm()
    {
        return $this->getBalance()->getCreditTerm() == '' ? 0 : $this->getBalance()->getCreditTerm();
    }

    /**
     * @return Balance
     */
    public function getCreditReminders()
    {
        return $this->getBalance()->getReminders() == '' ? 0 : $this->getBalance()->getReminders();
    }

    /**
     * @return Balance
     */
    public function getCustomerBalance()
    {
        return $this->getBalance()->getAmount() == '' ? 0 : $this->getBalance()->getAmount();
    }

    /**
     * @return Customer Limit Amount
     */
    public function getCustomerLimitAmount()
    {
        return $this->getBalance()->getLimitAmount() == '' ? 0 : $this->getBalance()->getLimitAmount();
    }

    /**
     * @return Balance
     */
    public function getSendInvoice()
    {
        return $this->getBalance()->getPaymentType() == '' ? 0 : $this->getBalance()->getPaymentType();
    }

    /**
     * @return string|void
     */
    public function getStatusChangedDate()
    {
        $subscriber = $this->registry->registry('subscriber');
        if ($subscriber->getChangeStatusAt()) {
            return $this->formatDate(
                $subscriber->getChangeStatusAt(),
                \IntlDateFormatter::MEDIUM,
                true
            );
        }

        return;
    }

    /**
     * Tab should be loaded trough Ajax call.
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    /**
     * @return Balance
     */
    private function getBalance()
    {
        return $this->creditHelper->getBalance($this->getCustomer()->getId(), 'USD');
    }
}
