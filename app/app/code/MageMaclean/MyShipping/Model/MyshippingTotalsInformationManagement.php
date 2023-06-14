<?php

namespace MageMaclean\MyShipping\Model;


use Magento\Framework\Api\DataObjectHelper;
use Magento\Quote\Api\CouponManagementInterface;
use Magento\Quote\Api\Data\AddressExtensionFactory;
use Magento\Quote\Api\Data\TotalsExtensionFactory;
use Magento\Quote\Model\Cart\Totals\ItemConverter;
use Magento\Quote\Model\Cart\TotalsConverter;

use MageMaclean\MyShipping\Model\Myshipping\ResultProcessor;
use MageMaclean\MyShipping\Model\Repository\QuoteRepository as MyshippingQuoteRepository;

/**
 * Shipping method read service
 */
class MyshippingTotalsInformationManagement implements
    \MageMaclean\MyShipping\Api\MyshippingTotalsInformationManagementInterface
{
    protected $cartTotalRepository;
    protected $cartRepository;
    protected $totalsFactory;
    protected $dataObjectHelper;
    protected $couponService;
    protected $totalsConverter;
    protected $itemConverter;
    protected $addressExtensionFactory;
    protected $totalsExtensionFactory;
    protected $_myshippingQuoteRepository;
    protected $_resultProcessor;
    protected $quoteIdMaskFactory;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalRepository,
        \Magento\Quote\Api\Data\TotalsInterfaceFactory $totalsFactory,
        DataObjectHelper $dataObjectHelper,
        CouponManagementInterface $couponService,
        TotalsConverter $totalsConverter,
        ItemConverter $itemConverter,
        AddressExtensionFactory $addressExtensionFactory,
        TotalsExtensionFactory $totalsExtensionFactory,
        MyshippingQuoteRepository $myshippingQuoteRepository,
        ResultProcessor $resultProcessor
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->totalsFactory = $totalsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->couponService = $couponService;
        $this->totalsConverter = $totalsConverter;
        $this->itemConverter = $itemConverter;
        $this->addressExtensionFactory = $addressExtensionFactory;
        $this->totalsExtensionFactory = $totalsExtensionFactory;
        $this->_myshippingQuoteRepository = $myshippingQuoteRepository;
        $this->_resultProcessor = $resultProcessor;
    }

    /**
     * @inheritDoc
     */
    public function calculate(
        int $cartId,
        \MageMaclean\MyShipping\Api\Data\MyshippingInformationInterface $myshippingInformation
    ) {
        $quote = $this->cartRepository->get($cartId);
        $this->validateQuote($quote);

        $this->processMyshippingInformation($myshippingInformation, $quote);

        if ($quote->getIsVirtual()) {
            $quote->setBillingAddress($myshippingInformation->getShippingAddress());
        } else {
            $quote->setShippingAddress($myshippingInformation->getShippingAddress());
            if ($myshippingInformation->getShippingCarrierCode() && $myshippingInformation->getShippingMethodCode()) {
                $method = $myshippingInformation->getShippingCarrierCode().'_'.$myshippingInformation->getShippingMethodCode();
                $quote->getShippingAddress()->setCollectShippingRates(true)->setShippingMethod($method);
            }
        }

        $quote->collectTotals();
        return $this->cartTotalRepository->get($cartId);
    }

    /**
     * Process Myshipping Information
     *
     * @param \MageMaclean\MyShipping\Api\Data\MyshippingInformationInterface $myshippingInformation
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     */
    private function processMyshippingInformation(\MageMaclean\MyShipping\Api\Data\MyshippingInformationInterface $myshippingInformation, \Magento\Quote\Api\Data\CartInterface $quote) {
        $shippingAddress = $quote->getShippingAddress();
        if(!$shippingAddress) return;
        
        $extAttributes = $shippingAddress->getExtensionAttributes();
        if(!$extAttributes) {
            $extAttributes = $this->addressExtensionFactory->create();
        }

        if($myshippingInformation->getShippingMethodCode() == Carrier::CODE_METHOD_NEW) {
            $extAttributes->setMyshippingCourierId((int)$myshippingInformation->getMyshippingCourierId());
            $extAttributes->setMyshippingCourierMethod((string)$myshippingInformation->getMyshippingCourierMethod());
            $extAttributes->setMyshippingAccount((string)$myshippingInformation->getMyshippingAccount());
        } else {
            if(!$myshippingInformation->getMyshippingAccountId()) {
                throw new \Magento\Framework\Exception\InputException(__("Invalid data for myshipping account."));
                return;
            }

            $extAttributes->setMyshippingAccountId((int)$myshippingInformation->getMyshippingAccountId());
            $extAttributes->setMyshippingCourierId((int)$myshippingInformation->getMyshippingCourierId());
            $extAttributes->setMyshippingAccount((int)$myshippingInformation->getMyshippingAccount());
            $extAttributes->setMyshippingCourierMethod((string)$myshippingInformation->getMyshippingCourierMethod());
        }

        $shippingAddress->setExtensionAttributes($extAttributes);
    }


    /**
     * Check if quote have items.
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function validateQuote(\Magento\Quote\Model\Quote $quote)
    {
        if ($quote->getItemsCount() === 0) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Totals calculation is not applicable to empty cart')
            );
        }
    }
}
