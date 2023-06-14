<?php


namespace Cminds\Creditline\Block\Adminhtml\Transaction\Edit;

use Magento\Backend\Block\Widget\Form as WidgetForm;
use Magento\Customer\Model\CustomerFactory;
use Magento\Directory\Model\Currency;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Form extends WidgetForm
{
    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var Currency
     */
    protected $currencyModel;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param CustomerFactory $customerFactory
     * @param Currency       $currencyModel
     * @param FormFactory    $formFactory
     * @param Registry       $registry
     * @param Context   $context
     * @param array          $data
     */
    public function __construct(
        CustomerFactory $customerFactory,
        Currency $currencyModel,
        FormFactory $formFactory,
        CurrencyInterface $currency,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->customerFactory = $customerFactory;
        $this->currencyModel = $currencyModel;
        $this->formFactory = $formFactory;
        $this->currency = $currency;
        $this->registry = $registry;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->formFactory->create()->setData([
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/save', ['id' => $this->getRequest()->getParam('id')]),
            'method'  => 'post',
            'enctype' => 'multipart/form-data',
        ]);

        $transaction = $this->registry->registry('current_transaction');

        $fieldset = $form->addFieldset('edit_fieldset', ['legend' => __('General Information')]);

        if ($transaction->getId()) {
            $fieldset->addField('transaction_id', 'hidden', [
                'name'  => 'transaction_id',
                'value' => $transaction->getId(),
            ]);
        }

        $customerFieldset = $form->addFieldset('customer_id_set', []);
        $customerFieldset->addField('customer_id', 'hidden', [
            'label'               => __('Customer ID'),
            'required'            => true,
            'name'                => 'customer_id',
            'value'               => $transaction->getCustomerId(),
            'class'               => 'admin__field',
            'before_element_html' => '<div>',
            'after_element_html'  => '<br></div>',
        ]);

        if ($transaction->getCustomerId() > 0) {
            $customer = $this->customerFactory->create()->load($transaction->getCustomerId());

            $fieldset->addField('customer_name', 'label', [
                'label' => __('Customer'),
                'value' => $customer->getFirstname()
                    . ' '
                    . $customer->getLastname()
                    . ' <'
                    . $customer->getEmail()
                    . '>',
            ]);
        }

        $fieldset->addField('balance_delta', 'text', [
            'label'            => __('Credit Balance Change'),
            'required'         => true,
            'name'             => 'balance_delta',
            'value'            => $transaction->getBalanceDelta(),
            'class'            => 'validate-balance',
            'after_element_js' => '
                <script>
                require([
                        "jquery",
                        "Magento_Ui/js/lib/validation/utils",
                        "mage/backend/validation",
                    ], function($, utils){
                        $.validator.addMethod(
                            "validate-balance", function (value) {
                                return utils.isEmptyNoTrim(value) ||
                                    (!isNaN(utils.parseNumber(value)) && /^\s*[-+]?\d*(\.\d*)?\s*$/.test(value));
                            }, $.mage.__("Select type of option.")
                        );
                    }
                );
                </script>'
        ]);

        $fieldset->addField('message', 'text', [
            'label'    => __('Additional Message'),
            'required' => true,
            'name'     => 'message',
            'value'    => $transaction->getMessage(),
        ]);

        $fieldset->addField('currency_code', 'select', [
            'label'    => __('Currency'),
            'required' => true,
            'name'     => 'currency_code',
            'value'    => $this->currency->getDefaultCurrency(),
            'options'  => $this->getAllowedCurrenciesOptions(),
        ]);

        if ($this->registry->registry('referrer_url')) {
            $fieldset->addField('referrer_url', 'hidden', [
                'name'  => 'referrer_url',
                'value' => $this->registry->registry('referrer_url')
            ]);
        }


        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return array
     */
    private function getAllowedCurrenciesOptions()
    {
        $options = [];
        $currencies = $this->currencyModel->getConfigAllowCurrencies();
        foreach ($currencies as $currency) {
            $options[$currency] = $this->currency->getCurrency($currency)->getName();
        }

        return $options;
    }
}
