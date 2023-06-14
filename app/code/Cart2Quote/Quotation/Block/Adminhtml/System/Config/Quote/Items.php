<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Adminhtml\System\Config\Quote;

/**
 * Class Items
 * @package Cart2Quote\Quotation\Block\Adminhtml\System\Config\Quote
 */
class Items extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'Cart2Quote_Quotation::system/config/quote/items.phtml';

    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\Item\Collection
     */
    protected $itemCollection;

    /**
     * Statuses constructor.
     * @param \Magento\Quote\Model\ResourceModel\Quote\Item\Collection $itemCollection
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Quote\Model\ResourceModel\Quote\Item\Collection $itemCollection,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->itemCollection = $itemCollection;
    }

    /**
     * Get grid configuration
     *
     * @return string
     */
    public function getItemsGridConfig()
    {
        return json_encode([
            'field' => [
                'name' => $this->getElement()->getName(),
                'label' => $this->getElement()->getLabel(),
                'fieldValue' => $this->getElement()->getValue(),
                'htmlId' => $this->getElement()->getHtmlId(),
                'items' => $this->getAllItemsAsArray()
            ]
        ]);
    }

    /**
     * Get the button and scripts contents
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);

        return $this->toHtml();
    }

    /**
     * Get all item configurations as an array
     *
     * @return array
     */
    public function getAllItemsAsArray()
    {
        $itemCostPrice = [
            'value' => 'price-cost',
            'label' => 'Cost Price'
        ];

        $itemGpMargin = [
            'value' => 'quote-margin',
            'label' => 'GP Margin'
        ];

        $itemGpMarginValue = [
            'value' => 'quote-margin-value',
            'label' => 'GP Margin Value'
        ];

        return [$itemCostPrice, $itemGpMargin, $itemGpMarginValue];
    }
}
