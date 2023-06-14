<?php
namespace Magento\Eav\Api\Data;

/**
 * Extension class for @see \Magento\Eav\Api\Data\AttributeInterface
 */
class AttributeExtension extends \Magento\Framework\Api\AbstractSimpleObject implements AttributeExtensionInterface
{
    /**
     * @return \Amasty\ShopbyBase\Api\Data\FilterSettingInterface|null
     */
    public function getFilterSetting()
    {
        return $this->_get('filter_setting');
    }

    /**
     * @param \Amasty\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting
     * @return $this
     */
    public function setFilterSetting(\Amasty\ShopbyBase\Api\Data\FilterSettingInterface $filterSetting)
    {
        $this->setData('filter_setting', $filterSetting);
        return $this;
    }
}
