<?php

namespace MageMaclean\MyShipping\Observer;

use MageMaclean\MyShipping\Model\Carrier;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use MageMaclean\MyShipping\Model\Repository\QuoteRepository as MyshippingQuoteRepository;
use MageMaclean\MyShipping\Model\AccountFactory;
use MageMaclean\MyShipping\Model\Myshipping\ResultProcessor;

class SaveQuoteToOrder implements \Magento\Framework\Event\ObserverInterface
{
    protected $orderExtensionFactory;
    protected $_helper;
    protected $_accountFactory;
    protected $_accountRepository;
    protected $_myshippingQuoteRepository;
    protected $_resultProcessor;

    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        Helper $helper,
        AccountRepository $accountRepository,
        MyshippingQuoteRepository $myshippingQuoteRepository,
        AccountFactory $accountFactory,
        ResultProcessor $resultProcessor
    )
    {
        $this->_helper = $helper;
        $this->_accountRepository = $accountRepository;
        $this->_myshippingQuoteRepository = $myshippingQuoteRepository;
        $this->_accountFactory = $accountFactory;
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->_resultProcessor = $resultProcessor;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();

        $shippingAddress = $quote->getShippingAddress();
        $shippingMethod = $shippingAddress->getShippingMethod();

        if ($this->_helper->isMyshippingMethod($shippingMethod)) {
            $extAttributes = $shippingAddress->getExtensionAttributes();
            if ($extAttributes->getMyshippingSave() && !$extAttributes->getMyshippingAccountId()) {
                $account = $this->_accountFactory->create();
                $account->setCustomerId($quote->getCustomerId());
                $account->setMyshippingCourierId($extAttributes->getMyshippingCourierId());
                $account->setMyshippingAccount($extAttributes->getMyshippingAccount());
                $this->_accountRepository->save($account);

                $extAttributes->setMyshippingAccountId($account->getId());
                $extAttributes->setMyshippingSave(false);

                $shippingMethod = $account->getCode();
            }
            $shippingAddress->setExtensionAttributes($extAttributes);

            $myshippingResult = $this->_resultProcessor->create($shippingMethod, $shippingAddress, $shippingAddress->getAllVisibleItems());

            $orderExtension = $order->getExtensionAttributes();
            if(!$orderExtension) {
                $orderExtension = $this->orderExtensionFactory->create();
            }

            $orderExtension->setMyshippingAccountId($myshippingResult->getMyshippingAccountId());
            $orderExtension->setMyshippingCourierId($myshippingResult->getMyshippingCourierId());
            $orderExtension->setMyshippingAccount($myshippingResult->getMyshippingAccount());
            $orderExtension->setMyshippingCourierMethod($myshippingResult->getMyshippingCourierMethod());
            $orderExtension->setMyshippingSave($myshippingResult->getMyshippingSave());

            $order->setExtensionAttributes($orderExtension);
            $order->setShippingMethod($shippingMethod);

            #$order->setShippingMethod($myshippingRateResult->getCode());
            #$order->setShippingDescription($myshippingRateResult->getShippingDescription());
            #$order->setShippingAmount($shippingPrice);
            #$order->setBaseShippingAmount($shippingPrice);
        }

        return $this;
    }
}
