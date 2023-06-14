<?php
namespace Cminds\Creditline\Block\Adminhtml\Customer\Edit;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;
use Cminds\Creditline\Helper\Data;

/**
 * Customer account form block
 */
class Tabs extends Generic implements TabInterface
{
    /**
     * @var Store
     */
    protected $systemsTore;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $corerEgistry;

    /**
     * @var Data
     */
    protected $creditHelper;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Data $creditHelper,
        array $data = []
    ) {
        $this->corerEgistry = $registry;
        $this->systemsTore = $systemStore;
        $this->creditHelper = $creditHelper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->corerEgistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    /**
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('Credit Line Settings');
    }

    /**
     * @return Phrase
     */
    public function getTabTitle()
    {
        return __('Credit Line Settings');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        if ($this->getCustomerId()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }

    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }
    
    /**
     * InitForm
     */
    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }

        /**@var Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('creditline_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Credit Line Settings')]);

        $balance = $this->getBalance();
        $creditTerm = $this->getCreditTerm();
        $creditReminders = $this->getCreditReminders();
        $paymentType = $this->getSendInvoice();
        $limitAmount = $this->getCustomerLimitAmount();
        $amount = $balance->getAmount();
        $totalbalance = abs($balance->getAmount() - $balance->getLimitAmount());

        $repayManualUrl = $this->getUrl('creditline/transaction/repaymanually', ['customer_id' => $this->getCustomerId() ]);
        $defaultSettings = $this->getUrl('creditline/transaction/defaultSettings', ['customer_id' => $this->getCustomerId() ]);
        $sendReplayEmail = $this->getUrl('creditline/invoice/send', ['customer_id' => $this->getCustomerId() ]);
        
        $fieldset->addField(
            'amount_of_credit',
            'text',
            [
                'name' => 'amount_of_credit',
                'label' => __('Credit Limit'),
                'title' => __('Credit Limit'),
                'data-form-part' => 'customer_form',
                'value' => $limitAmount,
                'note' => 'eg 10 credits = 10$'
            ]
        );
        $fieldset->addField(
            'credit_term',
            'text',
            [
                'name' => 'credit_term',
                'label' => __('Credit Term'),
                'title' => __('Credit Term'),
                'data-form-part' => 'customer_form',
                'value' => $creditTerm,
                'note' => 'Number of days for Credit Repayment'
            ]
        );
        $fieldset->addField(
            'amount',
            'text',
            [
                'name' => 'amount',
                'label' => __('Credit Line'),
                'title' => __('Credit Line'),
                'data-form-part' => 'customer_form',
                'value' => $amount,
                'readonly' => true,
                'note' => 'The rest of Current Credit Line for use.'
            ]
        );
        $fieldset->addField(
            'current_balance',
            'text',
            [
                'name' => 'current_balance',
                'label' => __('Customer Balance'),
                'title' => __('Customer Balance'),
                'data-form-part' => 'customer_form',
                'value' => $totalbalance,
                'readonly' => true,
                'note' => 'Current balance of credit used by this customer.'
            ]
        );
        $fieldset->addField(
            'repay_credit_balance_manually',
            'button',
            [
                'class' => 'action-secondary',
                'label' => '',
                'name' => 'repay_credit_balance',
                'value' => __('Repay Credit Balanace Manually'),
                'onclick' => "window.location='".$repayManualUrl."'"
            ]
        );
        $fieldset->addField(
            'payment_type',
            'select',
            [
                'data-form-part' => 'customer_form',
                'name' => 'payment_type',
                'label' => __('Send Invoice'),
                'title' => __('Send Invoice'),
                'value' => $paymentType,
                'options' => $this->paymentTypeOptions()
            ]
        );
        $fieldset->addField(
            'credit_reminders',
            'text',
            [
                'name' => 'credit_reminders',
                'label' => __('Credit Email Reminders'),
                'title' => __('Credit Email Reminders'),
                'data-form-part' => 'customer_form',
                'value' => $creditReminders,
                'note' => 'Number of days between email reminders.'
            ]
        );
        $fieldset->addField(
            'send_repay_email_manually',
            'button',
            [
                'class' => 'action-secondary',
                'label' => '',
                'name' => 'send_repay_email_manually',
                'value' => __('Send Repayment Email Manually'),
                'onclick' => "window.location='".$sendReplayEmail."'"
            ]
        );
        $afterElementHtml = '<p>You can choose to set default values for all the customer under: <strong>Configuration</strong> -&gt; <strong>Cminds</strong> -&gt; <strong>Credit Line</strong></p>';
        $fieldset->addField(
            'field_name', 
            'note', 
            [
             'label' => __('Default Settings'),
             'after_element_html' => $afterElementHtml,
            ]
        );
        $fieldset->addField(
            'revert_customer_setting',
            'button',
            [
                'class' => 'action-secondary',
                'label' => __('Revert customer to default settings'),
                'name' => 'revert_customer_setting',
                'value' => __('Set Default Values'),
                'onclick' => "window.location='".$defaultSettings."'"
            ]
        );
        
        $this->setForm($form);
        return $this;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }

    /**
     * @return Balance
     */
    private function getBalance()
    {
        return $this->creditHelper->getBalance($this->getCustomerId(), 'USD');
    }

    /**
     * @return CreditTerm
     */
    public function getCreditTerm()
    {
        return $this->getBalance()->getCreditTerm() == '' ? 0 : $this->getBalance()->getCreditTerm();
    }

    /**
     * @return SendInvoice
     */
    public function getSendInvoice()
    {
        return $this->getBalance()->getPaymentType() == '' ? 0 : $this->getBalance()->getPaymentType();
    }

    /**
     * @return Reminder
     */
    public function getCreditReminders()
    {
        return $this->getBalance()->getReminders() == '' ? 0 : $this->getBalance()->getReminders();
    }

    /**
     * @return PaymentType Options
     */
    public function paymentTypeOptions()
    {
        return [
            1   => __('Once a month'),
            2   => __('Every X days'),
            3   => __('Manually'),
            4   => __('No need to repay')
        ];
    }

    /**
     * @return Amount
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
}