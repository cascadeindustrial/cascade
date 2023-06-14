<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Items;

/**
 * Class FooterRenderer
 *
 * @package Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Items
 */
class FooterRenderer extends DefaultRenderer
{
    /**
     * Retrieve rendered column html content
     *
     * @param string $column the column key
     * @return string
     */
    public function getColumnFooterHtml($column)
    {
        $block = $this->getColumnRenderer($column, self::DEFAULT_TYPE);

        if ($block instanceof FooterInterface) {
            return $block->toFooterHtml();
        }
        return '&nbsp;';
    }

    /**
     * Checks if column is disabled in items grid config
     *
     * @param $columnName
     * @return bool
     */
    public function isColumnDisabled($columnName)
    {
        $itemsGridConfig = $this->getItemsGridConfig();

        foreach ($itemsGridConfig as $itemGridConfig) {
            if (strpos($columnName, $itemGridConfig['name']) !== false) {
                return !$itemGridConfig['visibility'];
            }
        }

        return false;
    }
}
