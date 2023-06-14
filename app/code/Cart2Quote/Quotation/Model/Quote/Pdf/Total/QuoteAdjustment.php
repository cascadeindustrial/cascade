<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Model\Quote\Pdf\Total;

/**
 * Class QuoteReduction
 *
 * @package Cart2Quote\Quotation\Model\Quote\Pdf\Total
 */
class QuoteAdjustment extends \Cart2Quote\Quotation\Model\Quote\Pdf\Total\DefaultTotal
{
    use \Cart2Quote\Features\Traits\Model\Quote\Pdf\Total\QuoteAdjustment {
        getTotalsForDisplay as private traitGetTotalsForDisplay;
        getAmount as private traitGetAmount;
    }

    /**
     * @var \Magento\Tax\Model\Config
     */
    protected $taxConfig;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param \Magento\Tax\Model\Calculation $taxCalculation
     * @param \Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory $ordersFactory
     * @param \Magento\Tax\Model\Config $taxConfig
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Tax\Model\Calculation $taxCalculation,
        \Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory $ordersFactory,
        \Magento\Tax\Model\Config $taxConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->taxConfig = $taxConfig;
        parent::__construct($taxHelper, $taxCalculation, $ordersFactory, $data);
    }

    /**
     * Get Quote Reduction for display on PDF
     *
     * @return array
     */
    public function getTotalsForDisplay()
    {
        return $this->traitGetTotalsForDisplay();
    }

    /**
     * Function to return the amount that should be included in QuoteReduction block
     *
     * @return mixed
     */
    public function getAmount()
    {
        return $this->traitGetAmount();
    }
}
