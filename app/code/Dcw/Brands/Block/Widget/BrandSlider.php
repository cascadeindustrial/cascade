<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBrand
 */


namespace Dcw\Brands\Block\Widget;

use Amasty\ShopbyBrand\Block\Widget\BrandSlider as AmastyWidget;

use Amasty\ShopbyBase\Api\Data\OptionSettingInterface;
use \Magento\Eav\Model\Entity\Attribute\Option;

class BrandSlider extends AmastyWidget
{
    /**
     * @param \Magento\Eav\Model\Entity\Attribute\Option $option
     * @param OptionSettingInterface $setting
     *
     * @return array
     */
    protected function getItemData(Option $option, OptionSettingInterface $setting)
    {
        $result = [];
        if ($setting->getIsFeatured()) {
            $result = [
                'id'       => $option->getValue(),
                'label'    => $setting->getLabel() ?: $option->getLabel(),
                'url'      => $this->helper->getBrandUrl($option),
                'img'      => $setting->getSliderImageUrl(),
                'position' => $setting->getSliderPosition(),
                'alt'      => $setting->getSmallImageAlt() ? : $setting->getLabel()
            ];
        }

        return $result;
    }
}
