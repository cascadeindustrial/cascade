<?php
namespace MageMaclean\MyShipping\Observer;

use \MageMaclean\MyShipping\Helper\Data as Helper;
use \MageMaclean\MyShipping\Model\Carrier as Carrier;
use MageMaclean\MyShipping\Model\Myshipping\ResultProcessor;
use MageMaclean\MyShipping\Model\Repository\QuoteRepository as MyshippingQuoteRepository;

class ConvertOrderToQuote implements \Magento\Framework\Event\ObserverInterface
{
  protected $_helper;
  protected $_resultProcessor;
  protected $_myshippingQuoteRepository;

  public function __construct(
      Helper $helper,
      ResultProcessor $resultProcessor,
      MyshippingQuoteRepository $myshippingQuoteRepository
  ) {
    $this->_helper = $helper;
    $this->_resultProcessor = $resultProcessor;
    $this->_myshippingQuoteRepository = $myshippingQuoteRepository;
  }

  public function execute(\Magento\Framework\Event\Observer $observer)
  {
    $order = $observer->getEvent()->getOrder();
    $quote = $observer->getEvent()->getQuote();

    $shippingAddress = $quote->getShippingAddress();
    $shippingMethod = $shippingAddress->getShippingMethod();

    $orderExtension = $order->getExtensionAttributes();
    if($this->_helper->isMyshippingMethod($shippingMethod) && $orderExtension) {
        $extAttributes = $shippingAddress->getExtensionAttributes();
        if(!$extAttributes) {
            $extAttributes = $this->_addressExtensionFactory->create();
        }

        $extAttributes->setMyshippingAccountId((int) $orderExtension->getMyshippingAccountId());
        $extAttributes->setMyshippingCourierId((int) $orderExtension->getMyshippingCourierId());
        $extAttributes->setMyshippingAccount((string) $orderExtension->getMyshippingAccount());
        $extAttributes->setMyshippingCourierMethod((string) $orderExtension->getMyshippingCourierMethod());
        $extAttributes->setMyshippingSave(false);
        $shippingAddress->setExtensionAttributes($extAttributes);
        $this->_myshippingQuoteRepository->updateMyshippingQuote($shippingMethod, $shippingAddress);

        $myshippingResult = $this->_resultProcessor->create($shippingMethod, $shippingAddress, $quote->getAllVisibleItems());

        $shippingAddress->setShippingMethod($myshippingResult->getCode());
        $shippingAddress->setShippingDescription($myshippingResult->getShippingDescription());
    }

    return $this;
  }
}
