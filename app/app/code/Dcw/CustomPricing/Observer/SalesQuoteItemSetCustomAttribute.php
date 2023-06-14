<?php
namespace Dcw\CustomPricing\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Cart;



class SalesQuoteItemSetCustomAttribute implements ObserverInterface
{
   protected $_objectManager;
   protected $cart;
   protected $product;
   protected $interface;
   protected $quote;
   protected $productCollectionFactory;
   protected $_coreSession;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\ObjectManagerInterface $interface,
        \Magento\Quote\Model\Quote\Item $quote,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Session\SessionManagerInterface $coreSession

    ) {
        $this->_objectManager = $objectManager;
        $this->cart = $cart;
        $this->product = $product;
        $this->objectManager = $interface;
        $this->quote = $quote;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_request = $request;
        $this->_coreSession = $coreSession;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
            $postData = $this->_request->getPost();
            if(!isset($postData['shipping_preference']))
                    return;

            $quoteItem = $observer->getQuoteItem();
            $product = $observer->getProduct();
            $shippingOption = $postData['shipping_preference'];
            $quoteItem->setShippingOption($shippingOption);
            //  if ($shippingOption == 1) {
            // $quoteItem->setShippingOption(1);
            // }
            // if ($shippingOption == 2) {
            // $quoteItem->setShippingOption(0);
            // }

    }
}
