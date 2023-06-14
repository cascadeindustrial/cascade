<?php
namespace Dcw\Brands\Block;
use Amasty\ShopbyBase\Model\ResourceModel\OptionSetting\CollectionFactory as OptionSettingCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product\Attribute\Repository;
class Index extends \Magento\Framework\View\Element\Template
{
  private $optionSettingCollectionFactory;
  protected $storeManager;
  protected $_productRepository;
  protected $_dataHelper;
  protected $_productCollectionFactory;
  protected $_connection;
  protected $_brandUrl;
  public function __construct( OptionSettingCollectionFactory $optionSettingCollectionFactory,
                               \Amasty\ShopbyBrand\Helper\Data  $dataHelper,
                               \Magento\Framework\App\ResourceConnection $connection,
                               StoreManagerInterface $storeManager,
                               \Magento\Backend\Block\Template\Context $context,
                               \Magento\Catalog\Model\ProductRepository $productRepository,
                               \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
                               \Amasty\ShopbyBase\Api\UrlBuilderInterface $brandUrl) {
    $this->_connection = $connection;
    $this->_dataHelper = $dataHelper;
    $this->_productCollectionFactory = $productCollectionFactory;
    $this->optionSettingCollectionFactory = $optionSettingCollectionFactory;
    $this->storeManager = $storeManager;
    $this->_productRepository = $productRepository;
    $this->_brandUrl = $brandUrl;
    parent::__construct($context);
  }

  public function _prepareLayout()
  {
    $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    $brandUrl = $baseUrl.'brands';
    $brandTitle = $this->getBrandTitle();
    if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
      $breadcrumbs->addCrumb('home', array('label'=>'Home', 'title'=>'Go to Home Page','link'=>$baseUrl));
      $breadcrumbs->addCrumb('brand_page', array('label'=>'Brands', 'title'=>'Brands','link'=>$brandUrl));
      $breadcrumbs->addCrumb('brand_title', array('label'=>$brandTitle, 'title'=>$brandTitle));
    }
  }

  public function getBrandTitle(){
    $id = $this->getRequest()->getParam('brandid');
    $connectioninfo = $this->_connection->getConnection();
    $sql = "SELECT eav.value from eav_attribute_option_value eav WHERE  eav.option_id=$id" ;
    $brandlable = $connectioninfo->fetchRow($sql);
    return $brandlable['value'];
  }

  public function getBrandUrl(){
    $id = $this->getRequest()->getParam('brandid');
    $url = $this->_brandUrl->getUrl('ambrand/index/index', ['id' => $id]);
    return $url;
  }
  public function getMediaUrl(){
    return $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
  }
  public function getBrandCollection()
  {
    $id = $this->getRequest()->getParam('brandid');
    $collection = $this->optionSettingCollectionFactory->create()->addFieldToFilter('value', $id)->getFirstItem();
    return $collection->getData();
  }

  public function getProductById($id)
  {
    return $this->_productRepository->getById($id);
  }
  public function getProductsCollection(){
    $attribute = $this->_dataHelper->getBrandAttributeCode();
    $id = $this->getRequest()->getParam('brandid');
    $collection = $this->_productCollectionFactory->create();
    $collection->addAttributeToFilter($attribute, $id);
    return $collection;
  }
  public function getCategoryInfo($catId){

    $connectioninfo = $this->_connection->getConnection();
    $sqll = "SELECT cce.path,cce.children_count from catalog_category_entity cce WHERE  cce.entity_id=$catId" ;
    $catPath = $connectioninfo->fetchRow($sqll);
    return $catPath;

  }
  public function getCategoryName($catId){
    $connectioninfo = $this->_connection->getConnection();

    $sql = "SELECT ccev.value from catalog_category_entity cce LEFT JOIN catalog_category_entity_varchar ccev on cce.entity_id=ccev.entity_id
                       WHERE ccev.attribute_id = 45 and cce.entity_id=$catId and ccev.store_id=0";
    $customerData = $connectioninfo->fetchRow($sql);
    return $customerData;

  }
}
