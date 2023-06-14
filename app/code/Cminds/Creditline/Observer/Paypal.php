<?php


namespace Cminds\Creditline\Observer;

use Magento\Framework\Event\Observer;
use Cminds\Creditline\Model\Transaction;
use Magento\Payment\Model\Cart\SalesModel\Quote;
use Magento\Payment\Model\Cart\SalesModel\Order;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Paypal extends AbstractObserver
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Cart $cart */
        $cart = $observer->getEvent()->getData('cart');

        if ($cart) {
            $salesEntity = $cart->getSalesModel();

            if ($salesEntity instanceof Quote) {
                $balanceField = 'base_creditline_amount_used';
            } elseif ($salesEntity instanceof Order) {
                $balanceField = 'base_creditline_amount';
            } else {
                return;
            }

            $value = abs($salesEntity->getDataUsingMethod($balanceField));

            if ($value > 0.0001) {
                $cart->addDiscount(floatval($value));

            }
        }
    }
}
