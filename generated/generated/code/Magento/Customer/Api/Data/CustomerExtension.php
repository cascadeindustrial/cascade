<?php
namespace Magento\Customer\Api\Data;

/**
 * Extension class for @see \Magento\Customer\Api\Data\CustomerInterface
 */
class CustomerExtension extends \Magento\Framework\Api\AbstractSimpleObject implements CustomerExtensionInterface
{
    /**
     * @return boolean|null
     */
    public function getIsSubscribed()
    {
        return $this->_get('is_subscribed');
    }

    /**
     * @param boolean $isSubscribed
     * @return $this
     */
    public function setIsSubscribed($isSubscribed)
    {
        $this->setData('is_subscribed', $isSubscribed);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAmazonId()
    {
        return $this->_get('amazon_id');
    }

    /**
     * @param string $amazonId
     * @return $this
     */
    public function setAmazonId($amazonId)
    {
        $this->setData('amazon_id', $amazonId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTjExemptionType()
    {
        return $this->_get('tj_exemption_type');
    }

    /**
     * @param string $tjExemptionType
     * @return $this
     */
    public function setTjExemptionType($tjExemptionType)
    {
        $this->setData('tj_exemption_type', $tjExemptionType);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTjRegions()
    {
        return $this->_get('tj_regions');
    }

    /**
     * @param string $tjRegions
     * @return $this
     */
    public function setTjRegions($tjRegions)
    {
        $this->setData('tj_regions', $tjRegions);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTjLastSync()
    {
        return $this->_get('tj_last_sync');
    }

    /**
     * @param string $tjLastSync
     * @return $this
     */
    public function setTjLastSync($tjLastSync)
    {
        $this->setData('tj_last_sync', $tjLastSync);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVertexCustomerCode()
    {
        return $this->_get('vertex_customer_code');
    }

    /**
     * @param string $vertexCustomerCode
     * @return $this
     */
    public function setVertexCustomerCode($vertexCustomerCode)
    {
        $this->setData('vertex_customer_code', $vertexCustomerCode);
        return $this;
    }
}
