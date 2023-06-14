<?php

namespace Dcw\CustomPricing\Plugin;

use Magento\Checkout\Model\Cart;

class PreventAddToCart
{
	protected $cartModel;
	protected $cartHelper;
	protected $_registry;
	protected $_productRepository;
	protected $_urlInterface;


	 public function __construct(
        \Magento\Checkout\Model\Cart $cartModel,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Model\ProductRepository $productRepository,
				\Magento\Framework\UrlInterface $urlInterface,
				\Magento\Checkout\Helper\Cart $cartHelper
    ) {
        $this->cartModel = $cartModel;
        $this->_registry = $registry;
        $this->_request = $request;
        $this->cartHelper = $cartHelper;
				$this->_urlInterface = $urlInterface;
        $this->_productRepository = $productRepository;
    }

    public function beforeAddProduct(Cart $subject, $productInfo, $requestInfo = null)
    {

    	$postData = $this->_request->getPost();


			if(isset($postData['pdppage_delivery_options']))
			{

				if($postData['pdppage_delivery_options']==2)
						return [$productInfo,$requestInfo];

				if(!isset($postData['shipping_preference']))
						throw new \Magento\Framework\Exception\LocalizedException(__("Please select one of the Delivery options"));
			}
			else{
				return [$productInfo,$requestInfo];
			}



        return [$productInfo,$requestInfo];
    }

		public function beforeUpdateItem(Cart $subject,$itemId, $requestInfo = null, $updatingParams = null)
		{

		 $currentUrl = $this->_urlInterface->getCurrentUrl();

			if($this->cartHelper->getItemsCount()==1 || (strpos($currentUrl,"amasty_checkout") !== false) || (strpos($currentUrl,"quote_ajax") !== false))
						return [$itemId, $requestInfo, $updatingParams];


		}

}
