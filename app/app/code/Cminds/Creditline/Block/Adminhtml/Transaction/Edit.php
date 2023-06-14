<?php


namespace Cminds\Creditline\Block\Adminhtml\Transaction;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Edit extends Container
{
    /**
     * @var Config
     */
    protected $wysiwygConfig;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Config   $wysiwygConfig
     * @param Registry $registry
     * @param Context  $context
     * @param array    $data
     */
    public function __construct(
        Config $wysiwygConfig,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->wysiwygConfig = $wysiwygConfig;
        $this->registry = $registry;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'transaction_id';
        $this->_controller = 'adminhtml_transaction';
        $this->_blockGroup = 'Cminds_Creditline';

        $this->buttonList->update('save', 'label', __('Save'));
        $this->buttonList->update('delete', 'label', __('Delete'));

        return $this;
    }

    /**
     * @return void
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($this->wysiwygConfig->isEnabled()) {
        }
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();

        if (!$this->registry->registry('current_transaction')->getCustomerId()) {
            $html .= $this->getLayout()
                ->createBlock('\Cminds\Creditline\Block\Adminhtml\Transaction\Edit\Customer')->toHtml();
        }

        return $html;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        if (
            $this->registry->registry('current_transaction') &&
            $this->registry->registry('current_transaction')->getId()
        ) {
            return $this->registry->registry('current_transaction');
        }
    }

    /**
     * @return Phrase
     */
    public function getHeaderText()
    {
        if ($transaction = $this->getTransaction()) {
            return __("Edit Transaction '%1'", $this->escapeHtml($transaction->getName()));
        } else {
            return __('Create New Transaction');
        }
    }
}
