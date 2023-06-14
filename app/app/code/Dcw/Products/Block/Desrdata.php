<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product description block
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Dcw\Products\Block;

use Magento\Catalog\Model\Product;

/**
 * @api
 * @since 100.0.2
 */
class Desrdata extends \Magento\Framework\View\Element\Template
{
    
    protected $_product = null;

    
    protected $_coreRegistry = null;

    protected $quotationCartHelper;

    protected $request;

    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Cart2Quote\Quotation\Helper\QuotationCart $quotationCartHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->quotationCartHelper = $quotationCartHelper;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    //This method added for adding product to quote from homepage,plp,recently and related products.
     public function getAddToQuoteUrl($product, $additional = [])
    {
        return $this->quotationCartHelper->getAddUrl($product, $additional);
    }

    public function getPost()
    {
        return $this->request->getParam("product_list_mode");
    }
}
