<?php

namespace Dcw\Override\Model\Config\Source;

class CheckoutStep extends \Amasty\Orderattr\Model\Config\Source\CheckoutStep
{
    const SHIPPING_METHODS_AFTER = 7;

    public function toArray()
    {
    //      $logFile='/var/log/CheckoutStep.log';
    // $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
    // $logger = new \Zend\Log\Logger();
    // $logger->addWriter($writer);
    // $logger->info("inside overrided file");
        return [
            self::SHIPPING_STEP => __('Shipping Address'),
            self::SHIPPING_METHODS => __('Shipping Methods Before'),
            self::SHIPPING_METHODS_AFTER => __('Shipping Methods After'),
            self::PAYMENT_STEP => __('Above Payment Method'),
            self::PAYMENT_PLACE_ORDER => __('Below Payment Method'),
            self::ORDER_SUMMARY => __('Order Summary'),
            self::NONE => __('None'),
        ];
    }
}
