<?php
namespace Magento\Eav\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Eav\Api\Data\AttributeInterface
 */
interface AttributeExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Amasty\ShopbyBase\Api\Data\FilterSettingInterface|null
     */
    public function getFilterSetting();

    /**
     * @param \Amasty\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting
     * @return $this
     */
    public function setFilterSetting(\Amasty\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting);
}
