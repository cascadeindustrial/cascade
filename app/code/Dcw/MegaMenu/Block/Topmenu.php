<?php

/* author:venkateshvenki */
namespace Dcw\MegaMenu\Block;

class Topmenu extends \Magento\Framework\View\Element\Template
{

    protected $_categoryHelper;
    protected $_categoryFlatConfig;
    protected $_topMenu;
    protected $_headerDesignHelper;
    protected $_categoryFactory;
    protected $_helper;
    protected $_filterProvider;
    protected $_blockFactory;
    protected $_megamenuConfig;
    protected $_storeManager;
    protected $_filesystem ;
    protected $_imageFactory;
    protected $scopeConfig;

    const XML_PATH_BLOG_TITLE = 'aw_blog/general/blog_title';
    const XML_PATH_ROUTE_VALUE = 'aw_blog/general/route_to_blog';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Dcw\MegaMenu\Helper\Data $helper,
        \Dcw\HeaderDesign\Helper\Data $headerDesignHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Theme\Block\Html\Topmenu $topMenu,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {

        $this->_categoryHelper = $categoryHelper;
        $this->_categoryFlatConfig = $categoryFlatState;
        $this->_categoryFactory = $categoryFactory;
        $this->_topMenu = $topMenu;
        $this->_headerDesignHelper = $headerDesignHelper;
        $this->_helper = $helper;
        $this->_filterProvider = $filterProvider;
        $this->_blockFactory = $blockFactory;
        $this->_storeManager = $context->getStoreManager();
        $this->_filesystem = $filesystem;
        $this->_imageFactory = $imageFactory;
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context);
    }

    public function getCategoryHelper()
    {
        return $this->_categoryHelper;
    }

    public function getCategoryModel($id)
    {
        $_category = $this->_categoryFactory->create();
        $_category->load($id);

        return $_category;
    }

    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
    {
        return $this->_topMenu->getHtml($outermostClass, $childrenWrapClass, $limit);
    }

    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }

    public function getActiveChildCategories($category)
    {
        $children = [];
        if ($this->_categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }
        foreach($subcategories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }
            $children[] = $category;
        }
        return $children;
    }

    public function getParentImage($catId)
    {
        $category = $this->getCategoryModel($catId);
        $sw_menu_cat_img = $category->getData('image');

        return $sw_menu_cat_img;
    }

    public function getMegamenuHtml()
    {
        $html = '';

        $navigationAnchorColor = $this->_headerDesignHelper->getNavigationAnchorTagColor();

        $categories = $this->getStoreCategories(true,false,true);
        foreach($categories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }

            $children = $this->getActiveChildCategories($category);

            $html .='<li class="level0 ui-menu-item">';
            $html .= '<a href="javascript:void(0)" class="level-top" style="color:'.$navigationAnchorColor.'" title="'.$category->getName().'">';
            $html .= '<span>'.$category->getName().'</span>';
            $html .= '</a>';
            if(count($children) > 0)
            $html .= '<i class="fa fa-angle-down" aria-hidden="true"></i>';
            if(count($children) > 0) {
            $html .= '<ul class="mega_menu_section">';
            $html .= '<div class="magamenu-left">';
                foreach ($children as $child) {
                    $sub_children = $this->getActiveChildCategories($child);
                    $html .= '<li class="level1 ui-menu-item">';
                    $html .= '<a href="'.$this->_categoryHelper->getCategoryUrl($child).'" title="'.$child->getName().'">';
                    $html .= '<span class="border-tns">'.$child->getName().'</span>';
                    $html .= '</a>';
                    if(count($sub_children) > 0)
                    $html .= '<i class="fa fa-angle-down" aria-hidden="true"></i>';
                    $html .='<div class="megamenu-right">';
                    $html .='<div class="subchildmenu">';
                    if(count($sub_children) > 0) {
                        foreach($sub_children as $subchild) {
                            $html .= '<div class="cat_grouped_items">';
                            $html .='<a class="cat-title" href="'.$this->_categoryHelper->getCategoryUrl($subchild).'" title="'.$subchild->getName().'">'.$subchild->getName().'</a>';
                            $html .= '<ul class="subchildmenu-items">';
                            $sub_sub_children = $this->getActiveChildCategories($subchild);
                            if(count($sub_sub_children)>0) {
                                foreach ($sub_sub_children as $subsubchild) {
                                    $html .= '<li><a href="' . $this->_categoryHelper->getCategoryUrl($subsubchild) . '" title="' . $subsubchild->getName() . '">' . $subsubchild->getName() . '</a></li>';
                                }
                            }

                            $html .= '</ul>';
                            $html .= '</div>';                        }
                    }
                    $html .='<a class="shop-all" href="'.$this->_categoryHelper->getCategoryUrl($child).'" title="'.$child->getName().'"><span>View All</span></a>';
                    $html .='</div>';
                $html .= '</div>';
                    $html .= '</li>';
                }
            $html .= '</div>';
            $html .= '</ul>';
        }
            $html .='</li>';
        }

        return $html;
    }

     public function getBlogTitle() 
     {
     $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

     return $this->scopeConfig->getValue(self::XML_PATH_BLOG_TITLE, $storeScope);
     }

     public function getRouteToBlog() 
     {
     $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

     return $this->scopeConfig->getValue(self::XML_PATH_ROUTE_VALUE, $storeScope);
     }
}
