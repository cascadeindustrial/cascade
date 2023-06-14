<?php
namespace Cminds\Creditline\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field; 
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class SendRepay extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Cminds_Creditline::system/config/sendrepay.phtml';

    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     *
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return url for repay button
     *
     * @return string
     */
    public function getRepayUrl()
    {
        return $this->getUrl('creditline/system_config/sendrepay');
    }

    /**
     * Generate repay button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $repaymailurl = $this->getRepayUrl();
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'sendrepaymail_button',
                'label' => __('Send Repayment Mail'),
                'onclick' => "window.location='".$repaymailurl."'"
            ]
        );

        return $button->toHtml();
    }
}