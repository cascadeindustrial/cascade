<?php

namespace Dcw\PaymentMethod\Plugin\Model\Method;

class Available
{
 
    public function afterGetAvailableMethods($subject, $result)
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 
		$itemsCollection = $cart->getQuote()->getItemsCollection();
		$itemsVisible = $cart->getQuote()->getAllVisibleItems();
		$items = $cart->getQuote()->getAllItems();
		$restrict = 0;
		foreach($itemsVisible as $item) {
			if (strpos($item->getName(), 'Creditline') !== false || strpos($item->getName(), 'Credit Line') !== false) {
				$restrict = 1;
			}else{
				$restrict = 0;
			}
		}
        foreach ($result as $key=>$_result) {
            if ($_result->getCode() == "authnetcim") {
                if ($restrict == 1) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }
}