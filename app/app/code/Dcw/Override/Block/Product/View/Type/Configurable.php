<?php

namespace Dcw\Override\Block\Product\View\Type;

class Configurable extends \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable
{
    public function getJsonConfig()
    {
    //      $logFile='/var/log/configurable.log';
    // $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    // $logger = new \Zend\Log\Logger();
    // $logger->addWriter($writer);
    // $logger->info("in the overrided file");
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $localeFormat = $objectManager->create('Magento\Framework\Locale\Format');
        $variationPrices = $objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable\Variations\Prices');
        $store = $this->getCurrentStore();
        $currentProduct = $this->getProduct();

        $options = $this->helper->getOptions($currentProduct, $this->getAllowProducts());
        $attributesData = $this->configurableAttributeData->getAttributesData($currentProduct, $options);

        $config = [
            'attributes' => $attributesData['attributes'],
            'template' => str_replace('%s', '<%- data.price %>', $store->getCurrentCurrency()->getOutputFormat()),
            'currencyFormat' => $store->getCurrentCurrency()->getOutputFormat(),
            'optionPrices' => $this->getOptionPrices(),
            'priceFormat' => $localeFormat->getPriceFormat(),
            'prices' => $variationPrices->getFormattedPrices($this->getProduct()->getPriceInfo()),
            'productId' => $currentProduct->getId(),
            'chooseText' => __('Select'),
            'images' => $this->getOptionImages(),
            'index' => isset($options['index']) ? $options['index'] : [],
        ];

        if ($currentProduct->hasPreconfiguredValues() && !empty($attributesData['defaultValues'])) {
            $config['defaultValues'] = $attributesData['defaultValues'];
        }

        $config = array_merge($config, $this->_getAdditionalConfig());

        return $this->jsonEncoder->encode($config);
    }
}
