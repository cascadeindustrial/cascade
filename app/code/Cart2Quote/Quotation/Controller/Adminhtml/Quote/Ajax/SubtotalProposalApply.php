<?php
/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Controller\Adminhtml\Quote\Ajax;

use Cart2Quote\Quotation\Controller\Adminhtml\Ajax\Quote\AjaxAction;

/**
 * Class SubtotalProposalApply
 *
 * @package Cart2Quote\Quotation\Controller\Adminhtml\Quote\Ajax
 */
class SubtotalProposalApply extends AbstractAction
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->_forward('noroute');
        }

        $amount = (float)$this->getRequest()->getParam('amount');
        $isPercentage = $this->getRequest()->getParam('isPercentage') === 'true';

        try {
            $this->_initQuote();
            $quote = $this->getCurrentQuote()->setSubtotalProposal($amount, $isPercentage);
            $quote->saveQuote();

            $this->getMessageManager()->addSuccessMessage(__('Subtotal proposal is applied'));

            return $this->getResponse()->representJson(
                $this->json->serialize(['success' => true])
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
