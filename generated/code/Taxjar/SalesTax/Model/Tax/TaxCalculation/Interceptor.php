<?php
namespace Taxjar\SalesTax\Model\Tax\TaxCalculation;

/**
 * Interceptor class for @see \Taxjar\SalesTax\Model\Tax\TaxCalculation
 */
class Interceptor extends \Taxjar\SalesTax\Model\Tax\TaxCalculation implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Tax\Model\Calculation $calculation, \Magento\Tax\Model\Calculation\CalculatorFactory $calculatorFactory, \Magento\Tax\Model\Config $config, \Magento\Tax\Api\Data\TaxDetailsInterfaceFactory $taxDetailsDataObjectFactory, \Magento\Tax\Api\Data\TaxDetailsItemInterfaceFactory $taxDetailsItemDataObjectFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Tax\Api\TaxClassManagementInterface $taxClassManagement, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Tax\Api\Data\AppliedTaxInterfaceFactory $appliedTaxDataObjectFactory, \Magento\Tax\Api\Data\AppliedTaxRateInterfaceFactory $appliedTaxRateDataObjectFactory)
    {
        $this->___init();
        parent::__construct($calculation, $calculatorFactory, $config, $taxDetailsDataObjectFactory, $taxDetailsItemDataObjectFactory, $storeManager, $taxClassManagement, $dataObjectHelper, $priceCurrency, $appliedTaxDataObjectFactory, $appliedTaxRateDataObjectFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function calculateTaxDetails(\Magento\Tax\Api\Data\QuoteDetailsInterface $quoteDetails, $useBaseCurrency, $scope)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'calculateTaxDetails');
        if (!$pluginInfo) {
            return parent::calculateTaxDetails($quoteDetails, $useBaseCurrency, $scope);
        } else {
            return $this->___callPlugins('calculateTaxDetails', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function calculateTax(\Magento\Tax\Api\Data\QuoteDetailsInterface $quoteDetails, $storeId = null, $round = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'calculateTax');
        if (!$pluginInfo) {
            return parent::calculateTax($quoteDetails, $storeId, $round);
        } else {
            return $this->___callPlugins('calculateTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultCalculatedRate($productTaxClassID, $customerId = null, $storeId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDefaultCalculatedRate');
        if (!$pluginInfo) {
            return parent::getDefaultCalculatedRate($productTaxClassID, $customerId, $storeId);
        } else {
            return $this->___callPlugins('getDefaultCalculatedRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCalculatedRate($productTaxClassID, $customerId = null, $storeId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCalculatedRate');
        if (!$pluginInfo) {
            return parent::getCalculatedRate($productTaxClassID, $customerId, $storeId);
        } else {
            return $this->___callPlugins('getCalculatedRate', func_get_args(), $pluginInfo);
        }
    }
}
