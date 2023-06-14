<?php
namespace Dcw\TopCategories\Block;

class SubCategoryGrid extends \Magento\Framework\View\Element\Template
{
	protected $_registry;
	protected $_categoryFactory;
	protected $_helperImage;
    protected $connection;

	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\ResourceConnection $connection,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
	\Magento\Catalog\Helper\Image $helperImage,
        array $data = []
    ) {
         $this->_registry = $registry;
         $this->_connection = $connection;
         $this->_categoryFactory = $categoryFactory;
         $this->_storeManager = $storeManager;
	$this->_helperImage = $helperImage;
        parent::__construct($context, $data);
    }
    public function getDefaultPlaceholderImageUrl(){
       return $this->_helperImage->getDefaultPlaceholderUrl('small_image');
     }
    public function getCurrentCategoryId() {
    $catId = $this->_registry->registry('current_category')->getId();
    return $catId;
    	// return $category = $this->getData('current_category');
    }
    public function getSubcategory(){
			return $this->_registry->registry('current_category');
    // $catId = $this->_registry->registry('current_category')->getId();
    // return $subCategory = $this->_categoryFactory->create()->load($catId);
     }

     public function getChildcategories()
     {
    		// $catId = $this->_registry->registry('current_category')->getId();
    		// $subCategory = $this->_categoryFactory->create()->load($catId);
				return $this->_registry->registry('current_category')->getChildrenCategories();
    	//return $childcategories = $subCategory->getChildrenCategories();
     }

    //  public function getSecondChildcategories()
    //  {
    // $catId = $this->_registry->registry('current_category')->getId();
    // $subCategory = $this->_categoryFactory->create()->load($catId)->getId();
    // $secondLevel = $this->_categoryFactory->create()->load($subCategory,'eq');
    // return $childcategories = $secondLevel->getChildrenCategories();
    //  }

    //  public function getChildCatName()
    //  {
    // $catId = $this->_registry->registry('current_category')->getId();
    // $subCategory = $this->_categoryFactory->create()->load($catId);
    // $secondLevel = $this->_categoryFactory->create()->load($subCategory->getId() ,'eq');
    // $lastLevel = $this->_categoryFactory->create()->load($secondLevel->getId() ,'eq');
    // return $categoryNames = $lastLevel->getChildrenCategories();
    //  }


    // public function getParentCategoryId(){
		//
    //  return $parent_category = $this->getParentCategory();
		//
    // }

    public function getBaseUrl() {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

     public function getStore()
    {
        return $this->_storeManager->getStore();
    }

    public function getCategoryImageUrl($categoryId){
      $imageUrl = '';
      $baseMediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
      $connection = $this->_connection->getConnection();
      $sql = "SELECT value from catalog_category_entity_varchar WHERE  attribute_id=48 and entity_id=$categoryId";
      $categoryData = $connection->fetchRow($sql);
      if($image = $categoryData['value'])
        $imageUrl = $baseMediaUrl.'catalog/category/'.$image;
      return $imageUrl;
    }

}
