<?php
namespace Dcw\TopCategories\Block;

class TopCategories extends \Magento\Framework\View\Element\Template
{
    protected $_categoryCollectionFactory;

    protected $_categoryHelper;

    protected $_connection;

    protected $_helperImage;

    protected $_storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\ResourceConnection $connection,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Helper\Image $helperImage,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_connection = $connection;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
        $this->_helperImage = $helperImage;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

   public function getDefaultPlaceholderImageUrl(){
       return $this->_helperImage->getDefaultPlaceholderUrl('small_image');
     }

    public function getTopCategoryCollection() {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name','image','thumbnail')
                    ->addAttributeToFilter('is_popular', array('eq' => 1))
                   ->addAttributeToFilter('is_active',array('eq' => 1))
                   ->addAttributeToSort('category_sort_no', 'ASC')
                   ->setPageSize(12)
                   ->load();
        //echo $collection->getSelect()->__toString();exit;
                   //echo "inside top categories";
        return $collection;
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
