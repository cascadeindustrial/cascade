<?php
/**
 * Copyright (c) 2020. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Dcw\Products\Controller\Quote;
/**
 * Class Add
 *
 * @package Cart2Quote\Quotation\Controller\Quote
 */
class Add extends \Cart2Quote\Quotation\Controller\Quote\Add
{

  /**
   * Add product to quote cart action
   *
   * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
   * @throws \Magento\Framework\Exception\LocalizedException
   * @throws \Magento\Framework\Exception\NoSuchEntityException
   */
  public function execute()
  {
      $params = $this->getRequest()->getParams();
      $productId = $this->getRequest()->getParam('product');
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance() ;
      $productData = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);

    $productSku = $productData->getData('sku');
     try {
          if (isset($params['qty'])) {
              $filter = new \Zend_Filter_LocalizedToNormalized(
                  [
                      'locale' => $this->resolverInterface->getLocale()
                  ]
              );
              $params['qty'] = $filter->filter($params['qty']);
          }

          $product = $this->_initProduct();
          $related = $this->getRequest()->getParam('related_product');

          /**
           * Check product availability
           */
          if (!$product) {
              return $this->goBack();
          }

          //check if product is quoteable
          $checkProduct = $product;

          //if product is configurable check if Dynamic AddButtons is Enabled
          if ($checkProduct->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
              //if that is enabled, extract the target simple product
              if ($this->quotationDataHelper->isDynamicAddButtonsEnabled()) {
                  $checkProduct = $this->_getProduct($checkProduct);
                  $request = $this->_getProductRequest($params);
                  $cartCandidates = $checkProduct->getTypeInstance()
                      ->prepareForCartAdvanced(
                          $request,
                          $checkProduct,
                          \Magento\Catalog\Model\Product\Type\AbstractType::PROCESS_MODE_FULL
                      );

                  foreach ($cartCandidates as $cartCandidate) {
                      if ($cartCandidate->getTypeId() == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
                          //we found a simple product
                          $checkProduct = $cartCandidate;
                          break;
                      }
                  }
              }
          }

          //check the product for quotability
          $quotable = $this->quotationDataHelper->isQuotable(
              $checkProduct,
              $this->customerSession->getCustomerGroupId()
          );

          if (!$quotable) {
              $this->messageManager->addErrorMessage(
                  __('This product is not quotable.')
              );

              return $this->goBack();
          }

          if (!$this->quotationDataHelper->isStockEnabledFrontend()) {
              $this->productHelper->setSkipSaleableCheck(true);
              $this->cart->getQuote()->setIsSuperMode(true);
          }

          $this->cart->addProduct($product, $params);
          if (!empty($related)) {
              $this->cart->addProductsByIds(explode(',', $related));
          }

          $this->cart->getQuote()->setIsQuotationQuote(true);
          $this->cart->save();

          $this->_eventManager->dispatch(
              'checkout_cart_add_product_complete',
              ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
          );

          $strategyProvider = $objectManager->create('\Cart2Quote\Quotation\Model\Quote\Request\Strategy\Provider');
          $strategy = $strategyProvider->getStrategy();
          $redirectToCart = $this->_quotationSession->getNoCartRedirect(true);
          if (!$redirectToCart || $strategy == 'quick_quote') {
              if (!$this->cart->getQuote()->getHasError()) {
                if ( $productSku != 'custom-request-form'){
                  $message = __(
                      'You added %1 to <a href="%2">your quote</a>.',
                      $this->escaper->escapeHtml($product->getName()),
                      $this->_url->getUrl('quotation/quote')
                  );
                }

                else
                {
                  $message = __(
                      'You have successfully submitted your quote to your Quote cart.Visit your quote cart to finish submitting your quote(s).',
                      $this->escaper->escapeHtml($product->getName()),
                      $this->_url->getUrl('quotation/quote')
                  );
                }

                  //still using the depricated addSuccess as addSuccessMessage removes the url
                  $this->messageManager->addSuccess($message);
              }

              $refererUrl = $this->_redirect->getRefererUrl();

              if (strpos($refererUrl, 'amasty_quickview') !== false) {

                  $resultRedirect = $this->resultRedirectFactory->create();
                  $resultRedirect->setPath('quotation/quote');
                  return $resultRedirect;
              }



              return $this->goBack(null, $product);
          }
      } catch (\Magento\Framework\Exception\LocalizedException $e) {
          if ($this->_quotationSession->getUseNotice(true)) {
              $this->messageManager->addNoticeMessage(
                  $this->escaper->escapeHtml($e->getMessage())
              );
          } else {
              $messages = array_unique(explode("\n", $e->getMessage()));
              foreach ($messages as $message) {
                  $this->messageManager->addErrorMessage(
                      $this->escaper->escapeHtml($message)
                  );
              }
          }
          $url = $this->_quotationSession->getRedirectUrl(true);
          if ($url) {
              return $this->goBack($url);
          }
      } catch (\Exception $e) {
          $this->messageManager->addExceptionMessage($e, __('We can\'t add this item to your quote right now.'));
          $this->logger->critical($e);
      }

      return $this->goBack();
  }

}
