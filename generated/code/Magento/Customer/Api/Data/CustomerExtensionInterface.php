<?php
namespace Magento\Customer\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Customer\Api\Data\CustomerInterface
 */
interface CustomerExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return boolean|null
     */
    public function getIsSubscribed();

    /**
     * @param boolean $isSubscribed
     * @return $this
     */
    public function setIsSubscribed($isSubscribed);

    /**
     * @return string|null
     */
    public function getAmazonId();

    /**
     * @param string $amazonId
     * @return $this
     */
    public function setAmazonId($amazonId);

    /**
     * @return string|null
     */
    public function getTjExemptionType();

    /**
     * @param string $tjExemptionType
     * @return $this
     */
    public function setTjExemptionType($tjExemptionType);

    /**
     * @return string|null
     */
    public function getTjRegions();

    /**
     * @param string $tjRegions
     * @return $this
     */
    public function setTjRegions($tjRegions);

    /**
     * @return string|null
     */
    public function getTjLastSync();

    /**
     * @param string $tjLastSync
     * @return $this
     */
    public function setTjLastSync($tjLastSync);

    /**
     * @return string|null
     */
    public function getVertexCustomerCode();

    /**
     * @param string $vertexCustomerCode
     * @return $this
     */
    public function setVertexCustomerCode($vertexCustomerCode);
}
