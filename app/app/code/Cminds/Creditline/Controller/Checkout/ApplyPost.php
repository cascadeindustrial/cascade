<?php


namespace Cminds\Creditline\Controller\Checkout;

use Magento\Framework\Controller\ResultFactory;
use Cminds\Creditline\Controller\Checkout;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class ApplyPost extends Checkout
{
    /**
     * @return Redirect
     */
    public function execute()
    {
        $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->_processPost();

        $url = $this->_url->getUrl('checkout/cart', ['_secure' => true]);
        if ($this->getRequest()->getParam('is_paypal')) {
            $url = $this->_url->getUrl('paypal/express/review', ['_secure' => true]);
        }

        return parent::_goBack($url);
    }
}
