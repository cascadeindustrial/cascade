<?php
namespace Dcw\Override\Controller\MoveToQuote;

/**
 * Class Index
 *
 * @package Cart2Quote\Quotation\Controller\MoveToQuote
 */
class Index extends \Cart2Quote\Quotation\Controller\MoveToQuote\Index
{
    /**
     * Shopping cart display action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $copiedQuote = $this->cloneQuote();
            $this->messageManager->addSuccessMessage(__('Items in your cart have been successfully moved to the quote.'));
            $this->checkoutSession->clearQuote();
            $this->checkoutSession->clearStorage();
            $this->quotationSession->setQuoteId($copiedQuote->getId());
            $resultRedirect->setPath('quotation/quote/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        }

        return $resultRedirect;
    }
}
