<?php

namespace Dcw\FeaturedProducts\Block;

class FeaturedProducts extends \Magento\Framework\View\Element\Template
{    
    protected $_productCollectionFactory;
    protected $productVisibility;
    protected $productStatus;
    protected $_varFactory;
    protected $_categoryFactory;
     protected $_helperImage;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, 
        \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,  
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Variable\Model\VariableFactory $varFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Helper\Image $helperImage,
        array $data = []
    )
    {    
        $this->_productCollectionFactory = $productCollectionFactory;    
        $this->listProductBlock = $listProductBlock; 
        $this->_storeManager = $storeManager;
        $this->productStatus = $productStatus;
        $this->_scopeConfig = $scopeConfig;
        $this->productVisibility = $productVisibility;
        $this->_varFactory = $varFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_helperImage = $helperImage;
        $this->productVisibility = $productVisibility;

        parent::__construct($context, $data);
    }

    public function getDefaultPlaceholderImageUrl(){
       return $this->_helperImage->getDefaultPlaceholderUrl('small_image');
     }

    public function getProductCollection()
    {
        $categoryId = $this->_scopeConfig->getValue('generalconfiguration/settings/category_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        // echo $categoryId;
        // exit;

        //$categoryId = 14;
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*')
                   ->addCategoriesFilter(['in' => $categoryId])
                   ->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
                   ->setVisibility($this->productVisibility->getVisibleInSiteIds())
                   ->setPageSize(12)
                   ->setFlag('has_stock_status_filter', true)   
           //->addAttributeToFilter('is_featured', array('eq' => 1))
            ->load(); 
            // print_r($collection->getData());
        return $collection;
    }

    public function getCategoryUrl()
    {
        $categoryId = $this->_scopeConfig->getValue('generalconfiguration/settings/category_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $category = $this->_categoryFactory->create()->load($categoryId);
        return $featuredCategoryUrl = $category->getUrl();

    }
    public function getAddToCartPostParams($product)
{
    return $this->listProductBlock->getAddToCartPostParams($product);
}
   public function getMediaUrl() {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

   public function getVariableValue() {
       $var = $this->_varFactory->create();
        $var->loadByCode('phone_no');
        return $var->getValue('plain');
    }
}
?>
