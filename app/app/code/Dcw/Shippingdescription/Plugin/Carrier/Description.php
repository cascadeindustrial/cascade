<?php

namespace Dcw\Shippingdescription\Plugin\Carrier;

use Magento\Quote\Api\Data\ShippingMethodInterfaceFactory;

/**
 * Class Description
 *
 */
class Description
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
        // printLog("in description method");
        // printLog($rateModel->getDescription());
        // printLog("-------------end--------------");
        $extensionAttribute = $result->getExtensionAttributes() ?
            $result->getExtensionAttributes()
            :
            $this->extensionFactory->create()
        ;
        $extensionAttribute->setDescription($rateModel->getDescription());
        $result->setExtensionAttributes($extensionAttribute);
        return $result;
    }
}
