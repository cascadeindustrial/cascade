<?php

namespace Dcw\Shippingdescription\Plugin\Carrier;

use Magento\Quote\Api\Data\ShippingMethodInterfaceFactory;

/**
 * Class Description
 *
 */
class Shortdescription
{
    /**
     * @var ShippingMethodInterfaceFactory
     */
    protected $extensionFactory;

    /**
     * Description constructor.
     * @param ShippingMethodInterfaceFactory $extensionFactory
     */
    public function __construct(
        ShippingMethodInterfaceFactory $extensionFactory
    )
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * @param $subject
     * @param $result
     * @param $rateModel
     * @return mixed
     */
    public function afterModelToDataObject($subject, $result, $rateModel)
    {
        // printLog("in short description method");
        // printLog($rateModel->getShortdescription());
        // printLog("-------------end--------------");
        $extensionAttribute = $result->getExtensionAttributes() ?
            $result->getExtensionAttributes()
            :
            $this->extensionFactory->create()
        ;
        $extensionAttribute->setShortdescription($rateModel->getShortdescription());
        $result->setExtensionAttributes($extensionAttribute);
        return $result;
    }
}
