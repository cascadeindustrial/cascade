<?php
namespace Magento\Tax\Api\Data;

/**
 * Extension class for @see \Magento\Tax\Api\Data\QuoteDetailsItemInterface
 */
class QuoteDetailsItemExtension extends \Magento\Framework\Api\AbstractSimpleObject implements QuoteDetailsItemExtensionInterface
{
    /**
     * @return float|null
     */
    public function getPriceForTaxCalculation()
    {
        return $this->_get('price_for_tax_calculation');
    }

    /**
     * @param float $priceForTaxCalculation
     * @return $this
     */
    public function setPriceForTaxCalculation($priceForTaxCalculation)
    {
        $this->setData('price_for_tax_calculation', $priceForTaxCalculation);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTaxCollectable()
    {
        return $this->_get('tax_collectable');
    }

    /**
     * @param float $taxCollectable
     * @return $this
     */
    public function setTaxCollectable($taxCollectable)
    {
        $this->setData('tax_collectable', $taxCollectable);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getCombinedTaxRate()
    {
        return $this->_get('combined_tax_rate');
    }

    /**
     * @param float $combinedTaxRate
     * @return $this
     */
    public function setCombinedTaxRate($combinedTaxRate)
    {
        $this->setData('combined_tax_rate', $combinedTaxRate);
        return $this;
    }

    /**
     * @return array|null
     */
    public function getJurisdictionTaxRates()
    {
        return $this->_get('jurisdiction_tax_rates');
    }

    /**
     * @param array $jurisdictionTaxRates
     * @return $this
     */
    public function setJurisdictionTaxRates(array $jurisdictionTaxRates)
    {
        $this->setData('jurisdiction_tax_rates', $jurisdictionTaxRates);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductType()
    {
        return $this->_get('product_type');
    }

    /**
     * @param string $productType
     * @return $this
     */
    public function setProductType($productType)
    {
        $this->setData('product_type', $productType);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPriceType()
    {
        return $this->_get('price_type');
    }

    /**
     * @param string $priceType
     * @return $this
     */
    public function setPriceType($priceType)
    {
        $this->setData('price_type', $priceType);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTjPtc()
    {
        return $this->_get('tj_ptc');
    }

    /**
     * @param string $tjPtc
     * @return $this
     */
    public function setTjPtc($tjPtc)
    {
        $this->setData('tj_ptc', $tjPtc);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVertexProductCode()
    {
        return $this->_get('vertex_product_code');
    }

    /**
     * @param string $vertexProductCode
     * @return $this
     */
    public function setVertexProductCode($vertexProductCode)
    {
        $this->setData('vertex_product_code', $vertexProductCode);
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getVertexIsConfigurable()
    {
        return $this->_get('vertex_is_configurable');
    }

    /**
     * @param bool $vertexIsConfigurable
     * @return $this
     */
    public function setVertexIsConfigurable($vertexIsConfigurable)
    {
        $this->setData('vertex_is_configurable', $vertexIsConfigurable);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStoreId()
    {
        return $this->_get('store_id');
    }

    /**
     * @param string $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->setData('store_id', $storeId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuoteId()
    {
        return $this->_get('quote_id');
    }

    /**
     * @param string $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId)
    {
        $this->setData('quote_id', $quoteId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductId()
    {
        return $this->_get('product_id');
    }

    /**
     * @param string $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->setData('product_id', $productId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuoteItemId()
    {
        return $this->_get('quote_item_id');
    }

    /**
     * @param string $quoteItemId
     * @return $this
     */
    public function setQuoteItemId($quoteItemId)
    {
        $this->setData('quote_item_id', $quoteItemId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_get('customer_id');
    }

    /**
     * @param string $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->setData('customer_id', $customerId);
        return $this;
    }
}
