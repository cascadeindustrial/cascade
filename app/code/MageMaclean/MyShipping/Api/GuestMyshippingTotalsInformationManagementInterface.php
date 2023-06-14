<?php
namespace MageMaclean\MyShipping\Api;

/**
 * Interface GuestMyshippingTotalsInformationManagementInterface
 * @api
 */
interface GuestMyshippingTotalsInformationManagementInterface
{
    /**
     * Calculate quote totals based on address and shipping method.
     *
     * @param string $cartId 
     * @param \MageMaclean\MyShipping\Api\Data\MyshippingInformationInterface $myshippingInformation
     * @return \Magento\Quote\Api\Data\TotalsInterface
     */
    public function calculate(
        $cartId, 
        \MageMaclean\MyShipping\Api\Data\MyshippingInformationInterface $myshippingInformation
    );
}
