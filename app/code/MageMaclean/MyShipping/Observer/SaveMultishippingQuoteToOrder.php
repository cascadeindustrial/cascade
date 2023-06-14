<?php
namespace MageMaclean\MyShipping\Observer;

use \MageMaclean\MyShipping\Helper\Data as Helper;
use \MageMaclean\MyShipping\Model\AccountFactory as AccountFactory;
use \MageMaclean\MyShipping\Model\Repository\AccountRepository as AccountRepository;
use MageMaclean\MyShipping\Model\Myshipping\ResultProcessor;
use Magento\Sales\Api\Data\OrderExtensionFactory;

class SaveMultishippingQuoteToOrder implements \Magento\Framework\Event\ObserverInterface
{
    protected $_helper;
    protected $_accountRepository;
    protected $_accountFactory;
    protected $_resultProcessor;
    protected $orderExtensionFactory;

    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        Helper $helper,
        AccountRepository $accountRepository,
        AccountFactory $accountFactory,
        ResultProcessor $resultProcessor
    ) {
        $this->_helper = $helper;
        $this->_accountRepository = $accountRepository;
        $this->_accountFactory = $accountFactory;
        $this->_resultProcessor = $resultProcessor;
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $shippingAddress = $observer->getEvent()->getAddress();
        $quote = $observer->getEvent()->getQuote();

        $shippingMethod = $shippingAddress->getShippingMethod();
        if($this->_helper->isMyshippingMethod($shippingMethod)) {
            $extAttributes = $shippingAddress->getExtensionAttributes();
            if ($extAttributes->getMyshippingSave() && !$extAttributes->getMyshippingAccountId()) {
                $account = $this->_accountFactory->create();
                $account->setCustomerId($quote->getCustomerId());
                $account->setMyshippingCourierId($extAttributes->getMyshippingCourierId());
                $account->setMyshippingAccount($extAttributes->getMyshippingAccount());
                $this->_accountRepository->save($account);

                $extAttributes->setMyshippingAccountId($account->getId());
                $extAttributes->setMyshippingSave(false);
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
            $order->setExtensionAttributes($orderExtension);


            $shippingPrice = $myshippingResult->getShippingPrice();
            $amountPrice = $quote->getStore()->getBaseCurrency()
                ->convert($shippingPrice, $quote->getStore()->getCurrentCurrencyCode());

            $order->setBaseShippingAmount($shippingPrice);
            $order->setShippingAmount($amountPrice);
            $order->setShippingMethod($myshippingResult->getCode());
            $order->setShippingDescription($myshippingResult->getShippingDescription());
        }

        return $this;
    }
}
