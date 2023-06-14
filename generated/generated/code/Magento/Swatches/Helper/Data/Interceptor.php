<?php
namespace Magento\Swatches\Helper\Data;

/**
 * Interceptor class for @see \Magento\Swatches\Helper\Data
 */
class Interceptor extends \Magento\Swatches\Helper\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Swatches\Model\ResourceModel\Swatch\CollectionFactory $swatchCollectionFactory, \Magento\Catalog\Model\Product\Image\UrlBuilder $urlBuilder, ?\Magento\Framework\Serialize\Serializer\Json $serializer = null, ?\Magento\Swatches\Model\SwatchAttributesProvider $swatchAttributesProvider = null, ?\Magento\Swatches\Model\SwatchAttributeType $swatchTypeChecker = null)
    {
        $this->___init();
        parent::__construct($productCollectionFactory, $productRepository, $storeManager, $swatchCollectionFactory, $urlBuilder, $serializer, $swatchAttributesProvider, $swatchTypeChecker);
    }

    /**
     * {@inheritdoc}
     */
    public function assembleAdditionalDataEavAttribute(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'assembleAdditionalDataEavAttribute');
        if (!$pluginInfo) {
            return parent::assembleAdditionalDataEavAttribute($attribute);
        } else {
            return $this->___callPlugins('assembleAdditionalDataEavAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadFirstVariationWithSwatchImage(\Magento\Catalog\Api\Data\ProductInterface $configurableProduct, array $requiredAttributes)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadFirstVariationWithSwatchImage');
        if (!$pluginInfo) {
            return parent::loadFirstVariationWithSwatchImage($configurableProduct, $requiredAttributes);
        } else {
            return $this->___callPlugins('loadFirstVariationWithSwatchImage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadFirstVariationWithImage(\Magento\Catalog\Api\Data\ProductInterface $configurableProduct, array $requiredAttributes)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadFirstVariationWithImage');
        if (!$pluginInfo) {
            return parent::loadFirstVariationWithImage($configurableProduct, $requiredAttributes);
        } else {
            return $this->___callPlugins('loadFirstVariationWithImage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadVariationByFallback(\Magento\Catalog\Api\Data\ProductInterface $parentProduct, array $attributes)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadVariationByFallback');
        if (!$pluginInfo) {
            return parent::loadVariationByFallback($parentProduct, $attributes);
        } else {
            return $this->___callPlugins('loadVariationByFallback', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductMediaGallery(\Magento\Catalog\Model\Product $product) : array
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductMediaGallery');
        if (!$pluginInfo) {
            return parent::getProductMediaGallery($product);
        } else {
            return $this->___callPlugins('getProductMediaGallery', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributesFromConfigurable(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributesFromConfigurable');
        if (!$pluginInfo) {
            return parent::getAttributesFromConfigurable($product);
        } else {
            return $this->___callPlugins('getAttributesFromConfigurable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSwatchAttributesAsArray(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSwatchAttributesAsArray');
        if (!$pluginInfo) {
            return parent::getSwatchAttributesAsArray($product);
        } else {
            return $this->___callPlugins('getSwatchAttributesAsArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSwatchesByOptionsId(array $optionIds)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSwatchesByOptionsId');
        if (!$pluginInfo) {
            return parent::getSwatchesByOptionsId($optionIds);
        } else {
            return $this->___callPlugins('getSwatchesByOptionsId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isProductHasSwatch(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isProductHasSwatch');
        if (!$pluginInfo) {
            return parent::isProductHasSwatch($product);
        } else {
            return $this->___callPlugins('isProductHasSwatch', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSwatchAttribute(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isSwatchAttribute');
        if (!$pluginInfo) {
            return parent::isSwatchAttribute($attribute);
        } else {
            return $this->___callPlugins('isSwatchAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isVisualSwatch(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isVisualSwatch');
        if (!$pluginInfo) {
            return parent::isVisualSwatch($attribute);
        } else {
            return $this->___callPlugins('isVisualSwatch', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isTextSwatch(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isTextSwatch');
        if (!$pluginInfo) {
            return parent::isTextSwatch($attribute);
        } else {
            return $this->___callPlugins('isTextSwatch', func_get_args(), $pluginInfo);
        }
    }
}
