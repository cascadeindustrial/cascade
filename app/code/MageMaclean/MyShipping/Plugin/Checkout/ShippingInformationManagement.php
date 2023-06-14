<?php

namespace MageMaclean\MyShipping\Plugin\Checkout;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Repository\QuoteRepository as MyshippingQuoteRepository;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Quote\Api\Data\AddressExtensionFactory;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use MageMaclean\MyShipping\Model\Carrier;

class ShippingInformationManagement
{
    protected $_quoteRepository;
    protected $_addressExtensionFactory;
    protected $_helper;
    protected $_accountRepository;
    protected $_myshippingQuoteRepository;

    /**
     * ShippingInformationManagement constructor.
     * @param QuoteRepository $quoteRepository
     * @param AddressExtensionFactory $addressExtensionFactory
     * @param AccountRepository $accountRepository
     * @param MyshippingQuoteRepository $myshippingQuoteRepository
     * @param Helper $helper
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        AddressExtensionFactory $addressExtensionFactory,
        Helper $helper,
        AccountRepository $accountRepository,
        MyshippingQuoteRepository $myshippingQuoteRepository
    ) {
        $this->_quoteRepository = $quoteRepository;
        $this->_addressExtensionFactory = $addressExtensionFactory;
        $this->_helper = $helper;
        $this->_accountRepository = $accountRepository;
        $this->_myshippingQuoteRepository = $myshippingQuoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     * @param $method
     * @return mixed
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        if(!$this->_helper->isEnabled()) return;

        $shippingAddress = $addressInformation->getShippingAddress();
        $this->_processAddressInformation($shippingAddress, $addressInformation);
        $addressInformation->setShippingAddress($shippingAddress);
    }

    private function _processAddressInformation($shippingAddress, $addressInformation) {
        $carrierCode = $addressInformation->getShippingCarrierCode();
        $methodCode = $addressInformation->getShippingMethodCode();
        $shippingMethod = $carrierCode . "_" . $methodCode;

        $extAttributes = $shippingAddress->getExtensionAttributes();
        if(!$extAttributes) {
            $extAttributes = $this->_addressExtensionFactory->create();
        }

        if(!$this->_helper->isMyshippingMethod($shippingMethod)) {
            $shippingAddress->setExtensionAttributes($extAttributes);
            return;
        }

        $addressInformationExtensionAttributes = $addressInformation->getExtensionAttributes();
        if($methodCode == Carrier::CODE_METHOD_NEW && $addressInformationExtensionAttributes) {
            $extAttributes->setMyshippingCourierId($addressInformationExtensionAttributes->getMyshippingCourierId());
            $extAttributes->setMyshippingCourierMethod($addressInformationExtensionAttributes->getMyshippingCourierMethod());
            $extAttributes->setMyshippingAccount($addressInformationExtensionAttributes->getMyshippingAccount());
            $extAttributes->setMyshippingSave($addressInformationExtensionAttributes->getMyshippingSave() ? true : false);
        } else if($addressInformationExtensionAttributes) {
            $account = $this->_accountRepository->getCustomerAccountById($addressInformationExtensionAttributes->getMyshippingAccountId());
            if($account) {
                $extAttributes->setMyshippingAccountId($account->getId());
                $extAttributes->setMyshippingCourierMethod($addressInformationExtensionAttributes->getMyshippingCourierMethod());
            } else {
                throw new InputException("Invalid shipping account ID.");
            }
        } else {
            throw new InputException("Invalid shipping data provided.");
        }
        $shippingAddress->setExtensionAttributes($extAttributes);
    }

    public function afterSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $result,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        if(!$this->_helper->isEnabled()) return $result;

        /** @var Quote $quote */
        $quote = $this->_quoteRepository->getActive($cartId);
        if($shippingAddress = $quote->getShippingAddress()) {
            $this->_processAddressInformation($shippingAddress, $addressInformation);
            $this->_myshippingQuoteRepository->updateMyshippingQuote($shippingAddress->getShippingMethod(), $shippingAddress);
        }

        return $result;
    }
}
