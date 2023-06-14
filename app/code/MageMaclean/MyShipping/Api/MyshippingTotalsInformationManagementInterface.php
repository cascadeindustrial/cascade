<?php
namespace MageMaclean\MyShipping\Api;

/**
 * Interface MyshippingTotalsInformationManagementInterface
 * @api
 */
interface MyshippingTotalsInformationManagementInterface
{
    /**
     * Estimate shipping
     *
     * @param int $cartId The shopping cart ID.
     * @param \MageMaclean\MyShipping\Api\Data\MyshippingInformationInterface $myshippingInformation
     * @return \Magento\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\InputException
     */
    public function calculate(int $cartId, \MageMaclean\MyShipping\Api\Data\MyshippingInformationInterface $myshippingInformation);
}
