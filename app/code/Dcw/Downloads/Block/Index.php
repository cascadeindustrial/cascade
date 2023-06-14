<?php
namespace Dcw\Downloads\Block;

use Magento\Store\Model\StoreManagerInterface;
use Amasty\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Amasty\ProductAttachment\Model\File\FileScope\FileScopeDataProvider;


class Index extends \Magento\Framework\View\Element\Template
{
  protected $storeManager;
  protected $_currency;
  protected $_productloader;
  private $fileScopeDataProvider;
  protected $categoryRepository;
  protected $_categoryCollectionFactory;




  public function __construct(
     \Magento\Backend\Block\Template\Context $context,
     \Magento\Framework\App\Request\Http $request,
     FileScopeDataProvider $fileScopeDataProvider,
     \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
     \Magento\Catalog\Model\CategoryRepository $categoryRepository,
     \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
     \Magento\Catalog\Model\ProductFactory $_productloader,
    StoreManagerInterface $storeManager,
    \Magento\Directory\Model\Currency $currency
                                  ) {
     $this->storeManager = $storeManager;
     $this->_productloader = $_productloader;
     $this->fileScopeDataProvider = $fileScopeDataProvider;
     $this->_categoryCollectionFactory = $categoryCollectionFactory;
    $this->categoryRepository = $categoryRepository;
     $this->listProductBlock = $listProductBlock;
     $this->request = $request;
     $this->_currency = $currency;
 		 parent::__construct($context);
  }
 public function _prepareLayout()
 {
    $attachmentName = $this->getProductAttachments();
    $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))
      $breadcrumbs->addCrumb('home', array('label'=>'Home', 'title'=>'Go to Home Page','link'=>$baseUrl));
    $categoryId = $this->getRequest()->getParam('cat_ids');
    if($categoryId)
    {
      $breadcrumbs = explode(", ",$categoryId);
      $categoryCollection = $this->getBreadcrumbCategoryCollection($breadcrumbs);
      foreach ($categoryCollection as $cat) {
          $catName = $cat->getName();
          $catUrl = $cat->getUrl();
          if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))
             $breadcrumbs->addCrumb('download_page'.$catName, array('label'=>$catName, 'title'=>$catName,'link'=>$catUrl));
      }
    }
    if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))
       $breadcrumbs->addCrumb('pdf_name', array('label'=>$attachmentName, 'title'=>$attachmentName));
  }

  public function getBreadcrumbCategoryCollection($breadcrumbs)
  {
    $collection = $this->_categoryCollectionFactory->create();
    $collection->addAttributeToSelect('name')
                     ->addAttributeToFilter('entity_id', array('in' => $breadcrumbs))
                    ->load();
    return $collection;
  }

    public function getAddToCartPostParams($product)
{
    return $this->listProductBlock->getAddToCartPostParams($product);
}
  public function getProductDetails()
  {
    $id = $this->getRequest()->getParam('productId');
    return $id;
  }

  public function getProductAttachments()
  {
   $attachmentName = $this->getRequest()->getParam('attachment_name');
   return $attachmentName;
  }

  public function getDownloadsAttachments($id)
    {
  $product = $this->_productloader->create()->load($id);
  if($product)
    return $this->fileScopeDataProvider->execute(
                [
                    RegistryConstants::PRODUCT => $product->getId(),
                    RegistryConstants::STORE => $this->_storeManager->getStore()->getId(),
                    RegistryConstants::EXTRA_URL_PARAMS => [
                        'product' => (int)$product->getId()
                    ]
                ],
                'frontendProduct'
            );

        return false;
}
public function getCurrentCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }
}
