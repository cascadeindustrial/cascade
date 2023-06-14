<?php

namespace Cminds\Creditline\Model;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use Magento\Paypal\Model\Cart;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Observer
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Context           $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->storeManager = $storeManager;
        $this->context = $context;
    }

    /**
     * @return $this
     */
    public function customerSaveAfter()
    {
        return $this;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function addPaypalCreditItem($observer)
    {
        $payPalCart = $observer->getEvent()->getPaypalCart();

        if ($payPalCart) {
            $salesEntity = $payPalCart->getSalesEntity();
            if ($salesEntity instanceof Quote) {
                $balanceField = 'base_creditline_amount_used';
            } elseif ($salesEntity instanceof Order) {
                $balanceField = 'base_creditline_amount';
            } else {
                return;
            }

            $value = abs($salesEntity->getDataUsingMethod($balanceField));

            if ($value > 0.0001) {
                $payPalCart->updateTotal(
                    Cart::TOTAL_DISCOUNT,
                    floatval($value),
                    __(
                        'Credit Line (%1)',
                        $this->storeManager->getStore()->convertPrice($value, true, false)
                    )
                );
            }
        }
    }
}
