<?php

namespace Dcw\ShippingMethod\Block\Search;

use Magento\Catalog\Model\Product as ProductModel;
use Magento\Catalog\Block\Product\ReviewRendererInterface;

class Product extends \Amasty\Xsearch\Block\Search\Product
{
    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function initializeProductCollectionNew()
    {
        //Parent part without blocks and sorting initializing.
        $layer = $this->getLayer();
        $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
        $origCategory = null;
        if ($this->getCategoryId()) {
            try {
                $category = $this->categoryRepository->get($this->getCategoryId());
            } catch (NoSuchEntityException $e) {
                $category = null;
            }

            if ($category) {
                $origCategory = $layer->getCurrentCategory();
                $layer->setCurrentCategory($category);
            }
        }

        $collection = $layer->getProductCollection();
        if ($origCategory) {
            $layer->setCurrentCategory($origCategory);
        }

        //Custom part.
        $collection->clear();
        $collection->setPageSize($this->getLimit());
        $collection->setOrder('relevance');
        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $collection]
        );
        return $collection;
    }
    /**
     * @return array
     */
    public function getResults()
    {
    //     $logFile='/var/log/productsphp.log';
    // $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    // $logger = new \Zend\Log\Logger();
    // $logger->addWriter($writer);
    // $logger->info("overrided file");
        $results = [];
        $imageId = $this->getImageIdNew();
        foreach ($this->getLoadedProductCollection() as $product) {
            $data['img'] = $this->encodeMediaUrlNew(
                $this->getImage($product, $imageId)->toHtml()
            );
            $data['url'] = $this->getRelativeLinkNew($product->getProductUrl());
            $data['name'] = $this->getName($product);
            $data['sku'] = $this->getSkuNew($product);
            $data['model_no'] = $this->getModelNew($product);
            $data['brand'] =  $this->getBrandNew($product);
            $data['description'] = $this->getDescription($product);
            $data['price'] = $this->getProductPrice($product);
            $data['is_salable'] = $product->isSaleable();
            $data['product_data'] = [
                'entity_id' => (string)$product->getId(),
                'request_path' => (string)$product->getRequestPath()
                ];
            $data['reviews'] = $this->getReviewsSummaryHtml($product, ReviewRendererInterface::SHORT_VIEW);
            $results[$product->getId()] = $data;
        }
        // $logger->info("name");
        // $logger->info($this->getName($product));

        $this->setNumResults($this->getLoadedProductCollection()->getSize());
        return $results;
    }

    /**
     * @return string
     */
    public function getImageIdNew()
    {
        return $this->isPortoThemeNew() ? self::PORTO_IMAGE_ID : self::IMAGE_ID;
    }

    /**
     * @return bool
     */
    public function isPortoThemeNew()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $design = $objectManager->create('Magento\Framework\View\DesignInterface');
        return $design->getDesignTheme()->getCode() == self::SMARTWAVE_PORTO_CODE;
    }

    /**
     * Encode media url on elasticsearch reindex process in order to correctly handle pub/index.php execution.
     *
     * @param string $html
     * @return string
     */
    public function encodeMediaUrlNew($html)
    {
        if ($this->getLimit() === 0) {
            $html = str_replace($this->getMediaUrlNew(), self::MEDIA_URL_PLACEHOLDER, $html);
        }

        return $html;
    }

    /**
     * @return string
     */
    public function getMediaUrlNew()
    {
        if ($this->mediaUrl === null) {
            $this->mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        }

        return $this->mediaUrl;
    }

    private function getName(ProductModel $product)
    {
        $nameLength = $this->getNameLength();
        $productNameStripped = $this->stripTags($product->getName(), null, true);
        $text =
            $nameLength && $this->string->strlen($productNameStripped) > $nameLength ?
            $this->string->substr($productNameStripped, 0, $this->getNameLength()) . '...'
            : $productNameStripped;
        return $this->highlight($text);
    }

    /**
     * @param ProductModel $product
     * @return string
     */
    public function getNameNew(ProductModel $product)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $string = $objectManager->create('Magento\Framework\Stdlib\StringUtils');        
        $nameLength = $this->getNameLengthNew();
        $productNameStripped = $this->stripTags($product->getNameNew(), null, true);
        $text = $nameLength && $string->strlen($productNameStripped) > $nameLength ?
            $this->string->substr($productNameStripped, 0, $this->getNameLengthNew()) . '...'
            : $productNameStripped;
        return $this->highlight($text);
    }

    public function getSkuNew(ProductModel $product)
    {
        $text = $product->getData('sku');
        return $this->highlight($text);
    }

    public function getModelNew(ProductModel $product)
    {
        $text = $product->getData('model_no');
        return $this->highlight($text);
    }

    public function getBrandNew(ProductModel $product)
    {
        $text = $product->getResource()->getAttribute('brand')->getFrontend()->getValue($product);
        return $this->highlight($text);
    }

    /**
     * @return int
     */
    public function getNameLengthNew()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $xSearchHelper = $objectManager->create('Amasty\Xsearch\Helper\Data');
        return (int)$xSearchHelper->getModuleConfig(self::XML_PATH_TEMPLATE_NAME_LENGTH);
    }

    /**
     * @return int
     */
    public function getDescLengthNew()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $xSearchHelper = $objectManager->create('Amasty\Xsearch\Helper\Data');
        return (int)$xSearchHelper->getModuleConfig(self::XML_PATH_TEMPLATE_DESC_LENGTH);
    }

    /**
     * @param string $url
     * @return string
     */
    public function getRelativeLinkNew($url)
    {
        $baseUrl = $this->getBaseUrl();
        $baseUrlPosition = strpos($url, $baseUrl);
        if ($baseUrlPosition !== false) {
            return substr($url, strlen($baseUrl) - 1);
        }
        return preg_replace('#^[^/:]+://[^/]+#', '', $url);
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function addFormKeyNew(string $html): string
    {
        return str_replace(self::FORM_KEY_PLACEHOLDER, $this->getFormKey(), $html);
    }
}
