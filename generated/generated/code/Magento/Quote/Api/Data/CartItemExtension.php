<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\CartItemInterface
 */
class CartItemExtension extends \Magento\Framework\Api\AbstractSimpleObject implements CartItemExtensionInterface
{
    /**
     * @return \Magento\SalesRule\Api\Data\RuleDiscountInterface[]|null
     */
    public function getDiscounts()
    {
        return $this->_get('discounts');
    }

    /**
     * @param \Magento\SalesRule\Api\Data\RuleDiscountInterface[] $discounts
     * @return $this
     */
    public function setDiscounts($discounts)
    {
        $this->setData('discounts', $discounts);
        return $this;
    }

    /**
     * @return \Cart2Quote\Quotation\Api\Data\Quote\Item\SectionInterface|null
     */
    public function getSection()
    {
        return $this->_get('section');
    }

    /**
     * @param \Cart2Quote\Quotation\Api\Data\Quote\Item\SectionInterface $section
     * @return $this
     */
    public function setSection(\Cart2Quote\Quotation\Api\Data\Quote\Item\SectionInterface $section)
    {
        $this->setData('section', $section);
        return $this;
    }
}
