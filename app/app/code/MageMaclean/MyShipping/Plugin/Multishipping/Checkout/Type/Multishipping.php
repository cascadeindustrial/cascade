<?php

namespace MageMaclean\MyShipping\Plugin\Multishipping\Checkout\Type;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Carrier;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use MageMaclean\MyShipping\Model\Repository\QuoteRepository as MyshippingQuoteRepository;
use Magento\Quote\Api\Data\AddressExtensionFactory;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Model\QuoteRepository;

class Multishipping
{
    protected $_request;
    protected $_quoteRepository;
    protected $_cartExtensionFactory;
    protected $_addressExtensionFactory;
    protected $_helper;
    protected $_accountRepository;
    protected $_myshippingQuoteRepository;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        QuoteRepository $quoteRepository,
        CartExtensionFactory $cartExtensionFactory,
        AddressExtensionFactory $addressExtensionFactory,
        AccountRepository $accountRepository,
        MyshippingQuoteRepository $myshippingQuoteRepository,
        Helper $helper
    ) {
        $this->_request = $request;
        $this->_quoteRepository = $quoteRepository;
        $this->_cartExtensionFactory = $cartExtensionFactory;
        $this->_addressExtensionFactory = $addressExtensionFactory;
        $this->_helper = $helper;
        $this->_accountRepository = $accountRepository;
        $this->_myshippingQuoteRepository = $myshippingQuoteRepository;
    }

    /**
     * @param \Magento\Multishipping\Model\Checkout\Type\Multishipping $subject
     * @param $methods
     * @return mixed|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSetShippingMethods(
        \Magento\Multishipping\Model\Checkout\Type\Multishipping $subject,
        $methods
    ) {
        if(!$this->_helper->isEnabled()) return [$methods];

        $quote = $subject->getQuote();
        $addresses = $quote->getAllShippingAddresses();

        $myshippingCourierIds = $this->_request->getPost('myshipping_courier_id');
        $myshippingCourierMethods = $this->_request->getPost('myshipping_courier_method');
        $myshippingAccounts = $this->_request->getPost('myshipping_account');
        /** @var  \Magento\Quote\Model\Quote\Address $address */
        foreach ($addresses as $address) {
            $addressId = $address->getId();
            if (isset($methods[$addressId])) {
                $method = $methods[$addressId];
                $carrierCode = explode("_", $method)[0];
                $extAttributes = $address->getExtensionAttributes();
                if(!$extAttributes) {
                    $extAttributes = $this->addressExtensionFactory->create();
                }

                if($carrierCode == Carrier::CODE) {
                    if($method == Carrier::CODE_NEW) {
                        $extAttributes->setMyshippingAccountId(0);
                        $extAttributes->setMyshippingCourierId(isset($myshippingCourierIds[$addressId]) ? $myshippingCourierIds[$addressId] : 0);
                        $extAttributes->setMyshippingAccount(isset($myshippingAccounts[$addressId]) ? $myshippingAccounts[$addressId] : "");
                    } else {
                        $accountId = str_replace(Carrier::CODE . "_account_", "", $method);
                        $account = $this->_accountRepository->getCustomerAccountById($accountId);
                        $extAttributes->setMyshippingAccountId($account->getId());
                        $extAttributes->setMyshippingCourierId($account->getMyshippingCourierId());
                        $extAttributes->setMyshippingAccount($account->getMyshippingAccount());
                    }
                    $extAttributes->setMyshippingCourierMethod(isset($myshippingCourierMethods[$addressId]) ? $myshippingCourierMethods[$addressId] : "");
                } else {
                    $extAttributes->setMyshippingAccountId(0);
                    $extAttributes->setMyshippingCourierId(0);
                    $extAttributes->setMyshippingAccount("");
                    $extAttributes->setMyshippingCourierMethod("");
                }

                $address->setExtensionAttributes($extAttributes);
                $this->_myshippingQuoteRepository->updateMyshippingQuote($method, $address);
            }
        }
    }
}
