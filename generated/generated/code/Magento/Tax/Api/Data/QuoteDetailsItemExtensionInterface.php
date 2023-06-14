<?php
namespace Magento\Tax\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Tax\Api\Data\QuoteDetailsItemInterface
 */
interface QuoteDetailsItemExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return float|null
     */
    public function getPriceForTaxCalculation();

    /**
     * @param float $priceForTaxCalculation
     * @return $this
     */
    public function setPriceForTaxCalculation($priceForTaxCalculation);

    /**
     * @return float|null
     */
    public function getTaxCollectable();

    /**
     * @param float $taxCollectable
     * @return $this
     */
    public function setTaxCollectable($taxCollectable);

    /**
     * @return float|null
     */
    public function getCombinedTaxRate();

    /**
     * @param float $combinedTaxRate
     * @return $this
     */
    public function setCombinedTaxRate($combinedTaxRate);

    /**
     * @return array|null
     */
    public function getJurisdictionTaxRates();

    /**
     * @param array $jurisdictionTaxRates
     * @return $this
     */
    public function setJurisdictionTaxRates(array $jurisdictionTaxRates);

    /**
     * @return string|null
     */
    public function getProductType();

    /**
     * @param string $productType
     * @return $this
     */
    public function setProductType($productType);

    /**
     * @return string|null
     */
    public function getPriceType();

    /**
     * @param string $priceType
     * @return $this
     */
    public function setPriceType($priceType);

    /**
     * @return string|null
     */
    public function getTjPtc();

    /**
     * @param string $tjPtc
     * @return $this
     */
    public function setTjPtc($tjPtc);

    /**
     * @return string|null
     */
    public function getVertexProductCode();

    /**
     * @param string $vertexProductCode
     * @return $this
     */
    public function setVertexProductCode($vertexProductCode);

    /**
     * @return bool|null
     */
    public function getVertexIsConfigurable();

    /**
     * @param bool $vertexIsConfigurable
     * @return $this
     */
    public function setVertexIsConfigurable($vertexIsConfigurable);

    /**
     * @return string|null
     */
    public function getStoreId();

    /**
     * @param string $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * @return string|null
     */
    public function getQuoteId();

    /**
     * @param string $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId);

    /**
     * @return string|null
     */
    public function getProductId();

    /**
     * @param string $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * @return string|null
     */
    public function getQuoteItemId();

    /**
     * @param string $quoteItemId
     * @return $this
     */
    public function setQuoteItemId($quoteItemId);

    /**
     * @return string|null
     */
    public function getCustomerId();

    /**
     * @param string $customerId
     * @return $this
     */
    public function setCustomerId($customerId);
}
