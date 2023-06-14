<?php 
namespace Dcw\SimpleSku\Plugin\Magento\ConfigurableProduct\Block\Product\View\Type;

class Configurable
{
    protected $objectmanager;
    public function __construct (
       
        \Magento\Framework\ObjectManagerInterface $objectmanager
        
    ) {
        $this->_objectManager = $objectmanager;
    }
    public function afterGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        $result
    ) {
        $store  = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currencyCode = $store->getStore()->getCurrentCurrencyCode();
        $priceCurrencyObject = $this->_objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface');
        $currency = $this->_objectManager->create('Magento\Directory\Model\CurrencyFactory')->create()->load($currencyCode); 
        $currencySymbol = $currency->getCurrencySymbol();
        $jsonResult = json_decode($result, true);

        $jsonResult['skus'] = [];
        $jsonResult['modelno'] = [];
        $jsonResult['expedited'] = [];
        $jsonResult['standard'] = [];
        $jsonResult['price'] = [];
        foreach ($subject->getAllowProducts() as $simpleProduct) {
            $jsonResult['skus'][$simpleProduct->getId()] = $simpleProduct->getSku();
            $jsonResult['modelno'][$simpleProduct->getId()] = $simpleProduct->getModelNo();
            $finalPrice = $simpleProduct->getPrice();
            $rate = $priceCurrencyObject->convert($finalPrice, 1, $currencyCode);
            $price = round($rate,2);
            $originlPrice = number_format((float)$price, 2, '.', ''); 
            $customprice = number_format($originlPrice,2); 
            $jsonResult['price'][$simpleProduct->getId()] = $currencySymbol.$customprice;
            $jsonResult['expedited'][$simpleProduct->getId()] = $simpleProduct->getAttributeText('expedited_delivery_time');
            $jsonResult['standard'][$simpleProduct->getId()] = $simpleProduct->getAttributeText('standard_delivery_time');
        }


        $result = json_encode($jsonResult);

        return $result;
    }
}