<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Model\Quote\Pdf\Total;

/**
 * Class Tax
 *
 * @package Cart2Quote\Quotation\Model\Quote\Pdf\Total
 */
class Tax extends DefaultTotal
{
    use \Cart2Quote\Features\Traits\Model\Quote\Pdf\Total\Tax {
        getTotalsForDisplay as private traitGetTotalsForDisplay;
    }

    /**
     * @var \Cart2Quote\Quotation\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Tax\Model\Config
     */
    protected $_taxConfig;

    /**
     * @var \Magento\Tax\Helper\Data
     */
    protected $_taxHelper;

    /**
     * @param \Cart2Quote\Quotation\Helper\Data $helper
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param \Magento\Tax\Model\Calculation $taxCalculation
     * @param \Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory $ordersFactory
     * @param \Magento\Tax\Model\Config $taxConfig
     * @param array $data
     */
    public function __construct(
        \Cart2Quote\Quotation\Helper\Data $helper,
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Tax\Model\Calculation $taxCalculation,
        \Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory $ordersFactory,
        \Magento\Tax\Model\Config $taxConfig,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->_taxConfig = $taxConfig;
        $this->_taxHelper = $taxHelper;
        parent::__construct($taxHelper, $taxCalculation, $ordersFactory, $data);
    }

    /**
     * Get tax amount for display on PDF
     *
     * @return array
     */
    public function getTotalsForDisplay()
    {
        return $this->traitGetTotalsForDisplay();
    }
}
