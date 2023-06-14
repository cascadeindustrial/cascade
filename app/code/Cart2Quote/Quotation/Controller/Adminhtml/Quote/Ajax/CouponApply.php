<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Adminhtml\Quote\Ajax;

/**
 * Class CouponApply
 *
 * @package Cart2Quote\Quotation\Controller\Adminhtml\Quote\Ajax
 */
class CouponApply extends AbstractCoupon
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page|null
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->_forward('noroute');
        }
        $amount = (float)$this->getRequest()->getParam('amount');
        $isPercentage = $this->getRequest()->getParam('isPercentage') === 'true';
        $code = $this->getRequest()->getParam('code');
        $code = empty($code) ? null : trim($code);
        try {
            $this->_initQuote();
            $quote = $this->getCurrentQuote();

            if ($this->quoteRule->ruleExistsForQuote($quote)) {
                $this->quoteRule->cleanQuoteRules($quote);
            }
            $rule = $this->quoteRule->createQuoteRule($quote, $amount, $isPercentage, $code);
            $code = $rule->getCouponCode();
            $isApplyDiscount = false;

            foreach ($quote->getAllItems() as $item) {
                if (!$item->getNoDiscount()) {
                    $isApplyDiscount = true;
                    break;
                }
            }
            if (!$isApplyDiscount) {
                $this->getMessageManager()->addErrorMessage(
                    __(
                        '%1 coupon code was not applied. Do not apply discount is selected for item(s)',
                        $this->escaper->escapeHtml($code)
                    )
                );
            }

            $quote->applyCoupon($code);
            $quote->saveQuote();

            $this->getMessageManager()->addSuccessMessage(__('Coupon code is applied %1', $code));

            return $this->getResponse()->representJson(
                $this->json->serialize(['couponCode' => $code])
            );
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->getMessageManager()->addExceptionMessage($e);

            return $this->getResponse()->representJson(
                $this->json->serialize(['error' => true, 'couponCode' => $code])
            );
        }
    }
}
