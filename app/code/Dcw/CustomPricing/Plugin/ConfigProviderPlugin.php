<?php
namespace Dcw\CustomPricing\Plugin;
 
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Model\ProductRepository as ProductRepository;
 
class ConfigProviderPlugin extends \Magento\Framework\Model\AbstractModel
{
    protected $checkoutSession;
 
    protected $_productRepository;
 
    public function __construct(
        CheckoutSession $checkoutSession,
        ProductRepository $productRepository
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->_productRepository = $productRepository;
    }
 
    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject, 
        array $result
    ) {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/deli.log');
           $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);
        $items = $result['totalsData']['items'];
        foreach ($items as $index => $item) {
            $quoteItem = $this->checkoutSession->getQuote()->getItemById($item['item_id']);
            $product = $this->_productRepository->getById($quoteItem->getProduct()->getId());
            $attributes = $product->getAttributes();
            
              $result['quoteItemData'][$index]['shipping_option'] = $quoteItem->getShippingOption(); 
             $result['quoteItemData'][$index]['standard_delivery_time'] = $attributes['standard_delivery_time']->getFrontend()->getValue($product);
             $result['quoteItemData'][$index]['expedited_delivery_time'] = $attributes['expedited_delivery_time']->getFrontend()->getValue($product);
            //$result['quoteItemData'][$index]['model_no'] = $product->getResource()->getAttribute('model_no')->getFrontend()->getValue($product);
           
           // $logger->info(json_encode((array)$result['quoteItemData'][$index]['model_no']));
        }
       // $logger->info(json_encode((array)$result));
        return $result;
    }
}