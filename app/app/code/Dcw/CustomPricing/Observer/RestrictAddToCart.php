<?php
namespace Dcw\CustomPricing\Observer;

use Magento\Framework\Event\ObserverInterface;

class RestrictAddToCart implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_messageManager = $messageManager;
    }

    /**
     * add to cart event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $cartObject = $objectManager->get('Magento\Checkout\Model\Cart');
      $checkoutSession = $objectManager->get('Magento\Checkout\Model\Session');
      $quote = $checkoutSession->getQuote();
      $quoteItems = $quote->getItemsCollection();
      $restrictAddToCart = 0;
      foreach($quoteItems as $item)
      {
           if($item->getProduct()->getSku()=='creditline')
           {
             $restrictAddToCart=1;
             break;
           }
      }
      if ($restrictAddToCart) {
                $this->_messageManager->addError(__('Credit Line Payment has been added to your cart. You will be unable to add items until the Credit Line Payment is completed or removed from the cart.'));
                //set false if you not want to add product to cart
                $observer->getRequest()->setParam('product', false);
                return $this;
      }
      return $this;
    }
}
