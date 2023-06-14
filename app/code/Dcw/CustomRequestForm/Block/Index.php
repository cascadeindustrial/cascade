<?php
namespace Dcw\CustomRequestForm\Block;

use Magento\Store\Model\StoreManagerInterface;

class Index extends \Magento\Framework\View\Element\Template

{
	protected $storeManager;
 protected $productRepository;
 protected $_productloader;
 protected $_customOptions;
  protected $_scopeConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        StoreManagerInterface $storeManager,
        \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
        //ProductRepositoryInterface $productRepository,
        //\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\ProductFactory $_productloader,
         \Magento\Framework\Registry $registry,
         \Magento\Catalog\Model\Product\Option $customOptions,
         \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        //\Magento\Catalog\Model\Product\OptionFactory $_customOptions,
        array $data = []
    )
    {
    	$this->listProductBlock = $listProductBlock;
    	$this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->_customOptions = $customOptions;
        $this->_productloader = $_productloader;
        $this->_registry = $registry;
        $this->_scopeConfig = $scopeConfig;
        //$this->_customOptions = $_customOptions;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
  {
    $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
      $breadcrumbs->addCrumb('home', array('label'=>'Home', 'title'=>'Go to Home Page','link'=>$baseUrl));
      $breadcrumbs->addCrumb('title', array('label'=>'Custom Request Form', 'title'=>'custom_request_form'));
    }
  }
    // public function getLoadProduct($id)
    // {
    //     return $this->_productloader->create()->load($id);
    // }

// public function getProductBySku($productSku)
// {
// 	return $this->productRepository->get($productSku);
// }


    public function getCustomOptions()
    {
    	$productId = $this->getProductId();
			$product = $this->_productloader->create()->load($productId);
			return $this->_customOptions->getProductOptionCollection($product);
    }

		public function getProductId(){

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$resource=$objectManager->create('\Magento\Framework\App\ResourceConnection');
			$connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
			$skuQuery = "SELECT entity_id  FROM `catalog_product_entity` WHERE `sku` LIKE 'custom-request-form'";
			$result1 = $connection->fetchRow($skuQuery);
			return $result1['entity_id'];
		}

    public function getConfig($config_path)
    {
        return $this->_scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getAddToCartPostParams($product)
{
    return $this->listProductBlock->getAddToCartPostParams($product);
}

}
?>
