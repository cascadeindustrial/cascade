<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Adminhtml\Quote\Ajax;

/**
 * Class CouponRemove
 *
 * @package Cart2Quote\Quotation\Controller\Adminhtml\Quote\Ajax
 */
class CouponRemove extends AbstractCoupon
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->_forward('noroute');
        }

        try {
            $this->_initQuote();
            $quote = $this->getCurrentQuote();

            $this->quoteRule->cleanQuoteRules($quote);

            $quote->applyCoupon(null);
            $quote->saveQuote();

            $this->getMessageManager()->addSuccessMessage(__('Coupon code is removed'));

            return $this->getResponse()->representJson(
                $this->json->serialize(['couponCode' => $quote->getCouponCode()])
            );
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->getMessageManager()->addExceptionMessage($e);

            return $this->getResponse()->representJson(
                $this->json->serialize(['error' => true])
            );
        }
    }
}