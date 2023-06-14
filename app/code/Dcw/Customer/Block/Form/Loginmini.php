<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dcw\Customer\Block\Form;

/**
 * Customer login form block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Loginmini extends \Magento\Framework\View\Element\Template
{
    protected $_registry;
    /**
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array                                            $data
     */
    protected $customerSession;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    /**
 * @var \Magento\CatalogInventory\Api\StockRegistryInterface
 */
private $stockRegistry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        array $data = []
    ) {
        
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->customerSession = $customerSession;
        $this->stockRegistry = $stockRegistry;
        $this->stockItem = $stockItem;
    }
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    
    public function getCurrentCategory()
    {        
        return $this->_registry->registry('current_category');
    }
    
    public function getCurrentProduct()
    {        
        return $this->_registry->registry('current_product');
    }
    public function getCustomerSession()
    {
    return $this->customerSession->getCustomerSession(); //Get value from customer session
    }
    public function getStockRegistry()
    {
    return $this->stockRegistry;
    }
    public function getStockItem()
    {
    return $this->stockItem;
    }
}
