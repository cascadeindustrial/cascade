<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dcw\FeaturedProducts\Model;

class Product extends \Magento\Catalog\Model\Product
{

public function getSpecialPrice()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $httpContext = $objectManager->get('Magento\Framework\App\Http\Context');
        $isLoggedIn = $httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        if($isLoggedIn){
        return $this->_getData('special_price');
    }
    }

}