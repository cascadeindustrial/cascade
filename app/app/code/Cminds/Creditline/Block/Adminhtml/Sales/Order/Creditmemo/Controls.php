<?php


namespace Cminds\Creditline\Block\Adminhtml\Sales\Order\Creditmemo;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;
use Cminds\Creditline\Helper\Data;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Controls extends Template
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Data
     */
    protected $creditData;

    public function __construct(
        Registry $registry,
        Context $context,
        Data $creditData,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->context = $context;
        $this->creditData = $creditData;

        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function canRefundToCredit()
    {
        if ($this->registry->registry('current_creditmemo')->getOrder()->getCustomerIsGuest()) {
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getReturnValue()
    {
        $max = round($this->registry->registry('current_creditmemo')->getBaseCreditReturnMax(), 2);

        if ($max) {
            return $max;
        }

        return 0;
    }

    /**
     * @return string
     */
    public function getBaseCurrencyCode()
    {
        $code = $this->registry->registry('current_creditmemo')->getBaseCurrencyCode();

        if ($code) {
            return $code;
        }

        return '';
    }

    /**
     * @return Data
     */
    public function getCreditData()
    {
        return $this->creditData;
    }
}
