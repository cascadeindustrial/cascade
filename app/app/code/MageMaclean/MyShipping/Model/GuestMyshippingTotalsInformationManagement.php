<?php

namespace MageMaclean\MyShipping\Model;

/**
 * Shipping method read service
 */
class GuestMyshippingTotalsInformationManagement implements
    \MageMaclean\MyShipping\Api\GuestMyshippingTotalsInformationManagementInterface
{
    /**
     * @var \Magento\Quote\Model\QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

    /**
     * @var \MageMaclean\MyShipping\Api\MyshippingTotalsInformationManagementInterface
     */
    protected $totalsInformationManagement;

    /**
     * @param \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory
     * @param \MageMaclean\MyShipping\Api\MyshippingTotalsInformationManagementInterface $totalsInformationManagement
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory,
        \MageMaclean\MyShipping\Api\MyshippingTotalsInformationManagementInterface $totalsInformationManagement
    ) {
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->totalsInformationManagement = $totalsInformationManagement;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(
        $cartId,
        \MageMaclean\MyShipping\Api\Data\MyshippingInformationInterface $myshippingInformation
    ) {
        /** @var $quoteIdMask \Magento\Quote\Model\QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');

        return $this->totalsInformationManagement->calculate(
            $quoteIdMask->getQuoteId(),
            $myshippingInformation
        );
    }
}
