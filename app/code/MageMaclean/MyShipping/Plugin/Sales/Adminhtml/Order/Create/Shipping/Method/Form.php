<?php
namespace MageMaclean\MyShipping\Plugin\Sales\Adminhtml\Order\Create\Shipping\Method;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Carrier as Carrier;

class Form
{
    protected $_helper;

    public function __construct(
        Helper $helper
    ) {
        $this->_helper = $helper;
    }

    public function afterGetShippingRates(
        \Magento\Sales\Block\Adminhtml\Order\Create\Shipping\Method\Form $subject,
        $result
    )
    {
        if($result && sizeof($result)) {
            foreach($result as $carrierCode => $rates) {
                if($carrierCode == Carrier::CODE) {
                    foreach($rates as $i => $rate) {
                        if($rate->getCode() == Carrier::CODE_NEW) {
                            $methodTitle = $rate->getMethodTitle();
                        } else {
                            $appendStr = $rate->getCarrierTitle() . " - ";
                            $methodTitle = $appendStr . str_replace($appendStr, "", $rate->getMethodTitle());
                        }
                        $rate->setMethodTitle($methodTitle);
                    }
                }
            }
        }

        return $result;
    }
}
