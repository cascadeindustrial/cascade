<?php
namespace MageWorx\SeoMarkup\Helper\DataProvider\Product;

/**
 * Interceptor class for @see \MageWorx\SeoMarkup\Helper\DataProvider\Product
 */
class Interceptor extends \MageWorx\SeoMarkup\Helper\DataProvider\Product implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\MageWorx\SeoMarkup\Helper\Product $helperData, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder, \Magento\Framework\Registry $registry, \Magento\Catalog\Model\ResourceModel\Category $resourceCategory, \Magento\Review\Model\ReviewFactory $reviewFactory, \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory, \Magento\Review\Model\ResourceModel\Rating\Option\Vote\CollectionFactory $ratingVoteCollectionFactory, \Magento\Framework\App\Helper\Context $context, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone, \Magento\Framework\Stdlib\DateTime $dateTime, \MageWorx\SeoAll\Helper\MagentoVersion $helperVersion)
    {
        $this->___init();
        parent::__construct($helperData, $storeManager, $imageBuilder, $registry, $resourceCategory, $reviewFactory, $reviewCollectionFactory, $ratingVoteCollectionFactory, $context, $timezone, $dateTime, $helperVersion);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentCurrencyCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCurrentCurrencyCode');
        if (!$pluginInfo) {
            return parent::getCurrentCurrencyCode();
        } else {
            return $this->___callPlugins('getCurrentCurrencyCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDescriptionValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDescriptionValue');
        if (!$pluginInfo) {
            return parent::getDescriptionValue($product);
        } else {
            return $this->___callPlugins('getDescriptionValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeValueByCode($product, $attributeCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributeValueByCode');
        if (!$pluginInfo) {
            return parent::getAttributeValueByCode($product, $attributeCode);
        } else {
            return $this->___callPlugins('getAttributeValueByCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductCanonicalUrl($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductCanonicalUrl');
        if (!$pluginInfo) {
            return parent::getProductCanonicalUrl($product);
        } else {
            return $this->___callPlugins('getProductCanonicalUrl', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConditionValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConditionValue');
        if (!$pluginInfo) {
            return parent::getConditionValue($product);
        } else {
            return $this->___callPlugins('getConditionValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregateRatingData($product, $useMagentoBestRating = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAggregateRatingData');
        if (!$pluginInfo) {
            return parent::getAggregateRatingData($product, $useMagentoBestRating);
        } else {
            return $this->___callPlugins('getAggregateRatingData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getReviewData($product, $useMagentoBestRating = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getReviewData');
        if (!$pluginInfo) {
            return parent::getReviewData($product, $useMagentoBestRating);
        } else {
            return $this->___callPlugins('getReviewData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getColorValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getColorValue');
        if (!$pluginInfo) {
            return parent::getColorValue($product);
        } else {
            return $this->___callPlugins('getColorValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBrandValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBrandValue');
        if (!$pluginInfo) {
            return parent::getBrandValue($product);
        } else {
            return $this->___callPlugins('getBrandValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getManufacturerValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getManufacturerValue');
        if (!$pluginInfo) {
            return parent::getManufacturerValue($product);
        } else {
            return $this->___callPlugins('getManufacturerValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getModelValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getModelValue');
        if (!$pluginInfo) {
            return parent::getModelValue($product);
        } else {
            return $this->___callPlugins('getModelValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGtinData($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGtinData');
        if (!$pluginInfo) {
            return parent::getGtinData($product);
        } else {
            return $this->___callPlugins('getGtinData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSkuValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSkuValue');
        if (!$pluginInfo) {
            return parent::getSkuValue($product);
        } else {
            return $this->___callPlugins('getSkuValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWeightValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWeightValue');
        if (!$pluginInfo) {
            return parent::getWeightValue($product);
        } else {
            return $this->___callPlugins('getWeightValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPriceValidUntilValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPriceValidUntilValue');
        if (!$pluginInfo) {
            return parent::getPriceValidUntilValue($product);
        } else {
            return $this->___callPlugins('getPriceValidUntilValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductIdValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductIdValue');
        if (!$pluginInfo) {
            return parent::getProductIdValue($product);
        } else {
            return $this->___callPlugins('getProductIdValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomPropertyValue($product, $propertyName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomPropertyValue');
        if (!$pluginInfo) {
            return parent::getCustomPropertyValue($product, $propertyName);
        } else {
            return $this->___callPlugins('getCustomPropertyValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryValue($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCategoryValue');
        if (!$pluginInfo) {
            return parent::getCategoryValue($product);
        } else {
            return $this->___callPlugins('getCategoryValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductImage($product, $imageId = 'product_base_image')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductImage');
        if (!$pluginInfo) {
            return parent::getProductImage($product, $imageId);
        } else {
            return $this->___callPlugins('getProductImage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailability($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAvailability');
        if (!$pluginInfo) {
            return parent::getAvailability($product);
        } else {
            return $this->___callPlugins('getAvailability', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'reset');
        if (!$pluginInfo) {
            return parent::reset();
        } else {
            return $this->___callPlugins('reset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isModuleOutputEnabled');
        if (!$pluginInfo) {
            return parent::isModuleOutputEnabled($moduleName);
        } else {
            return $this->___callPlugins('isModuleOutputEnabled', func_get_args(), $pluginInfo);
        }
    }
}
