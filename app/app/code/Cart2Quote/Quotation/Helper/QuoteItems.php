<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Helper;

/**
 * Class LockQuote
 * @package Cart2Quote\Quotation\Helper
 */
class QuoteItems extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @const quote form settings config path
     */
    const QUOTE_ITEMS = 'cart2quote_advanced/quote_items/item_configuration';

    /**
     * @return array
     */
    public function getQuoteItemsConfigArray()
    {
        return json_decode(
            $this->scopeConfig->getValue(
                self::QUOTE_ITEMS,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            ),
            true
        );
    }
}
