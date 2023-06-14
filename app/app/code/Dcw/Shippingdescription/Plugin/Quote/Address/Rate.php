<?php
namespace Dcw\Shippingdescription\Plugin\Quote\Address;

class Rate
{
    /**
     * @param \Magento\Quote\Model\Quote\Address\AbstractResult $rate
     * @return \Magento\Quote\Model\Quote\Address\Rate
     */
    public function afterImportShippingRate($subject, $result, $rate)
    {
        // printLog("in afterImportShippingRate method");
        // printLog($rate->getDescription());
        // printLog($rate->getShortdescription());
        // printLog("-------------end--------------");
        if ($rate instanceof \Magento\Quote\Model\Quote\Address\RateResult\Method) {
            $result->setDescription($rate->getDescription());
            $result->setShortdescription($rate->getShortdescription());
        }

        return $result;
    }
}
