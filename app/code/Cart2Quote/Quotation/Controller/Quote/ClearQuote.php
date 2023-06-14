<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Quote;

/**
 * Class UpdatePost
 *
 * @package Cart2Quote\Quotation\Controller\Quote
 */
class ClearQuote extends \Cart2Quote\Quotation\Controller\Quote
{
    /**
     * Update quote data action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $this->cart->truncate()->save();
        } catch (\Magento\Framework\Exception\LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage($exception, __('We can\'t update the quote.'));
        }

        return $this->_goBack();
    }
}
