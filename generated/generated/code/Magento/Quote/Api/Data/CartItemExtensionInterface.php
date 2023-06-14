<?php
namespace Magento\Quote\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Quote\Api\Data\CartItemInterface
 */
interface CartItemExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\SalesRule\Api\Data\RuleDiscountInterface[]|null
     */
    public function getDiscounts();

    /**
     * @param \Magento\SalesRule\Api\Data\RuleDiscountInterface[] $discounts
     * @return $this
     */
    public function setDiscounts($discounts);

    /**
     * @return \Cart2Quote\Quotation\Api\Data\Quote\Item\SectionInterface|null
     */
    public function getSection();

    /**
     * @param \Cart2Quote\Quotation\Api\Data\Quote\Item\SectionInterface $section
     * @return $this
     */
    public function setSection(\Cart2Quote\Quotation\Api\Data\Quote\Item\SectionInterface $section);
}
