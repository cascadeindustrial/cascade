<?php


namespace Cminds\Creditline\Controller\Account;

use Magento\Framework\Controller\ResultFactory;
use Cminds\Creditline\Model\Transaction;
use Cminds\Creditline\Controller\Account;
use Magento\Checkout\Controller\Cart\Add;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Refill extends Add
{
    /**
     *  {@inheritdoc}
     */
    public function execute()
    {
        parent::execute();

        $this->_redirect('checkout/cart/');
    }
}