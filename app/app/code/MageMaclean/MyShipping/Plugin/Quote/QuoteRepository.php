<?php
namespace MageMaclean\MyShipping\Plugin\Quote;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\CartRepositoryInterface;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Carrier;
use MageMaclean\MyShipping\Model\Repository\QuoteRepository as MyshippingQuoteRepository;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;

class QuoteRepository
{
    protected $_helper;
    protected $_myshippingQuoteRepository;
    protected $_accountRepository;

    public function __construct(
        Helper $helper,
        MyshippingQuoteRepository $myshippingQuoteRepository,
        AccountRepository $accountRepository
    ) {
        $this->_helper = $helper;
        $this->_myshippingQuoteRepository = $myshippingQuoteRepository;
        $this->_accountRepository = $accountRepository;
    }

    public function afterGet(
        CartRepositoryInterface $subject,
        CartInterface $quote
    ) {
        return $this->processQuote($quote);
    }

    public function afterGetList(
        CartRepositoryInterface $subject,
        SearchResultsInterface $result
    ) {
        $items = [];
        foreach ($result->getItems() as $item) {
            $items[] = $this->processQuote($item);
        }
        $result->setItems($items);

        return $result;
    }

    /**
     * @param CartInterface $quote
     * @return CartInterface
     */
    private function processQuote(CartInterface $quote): CartInterface
    {
        if($quote->getShippingAddress()) {
            foreach($quote->getAllShippingAddresses() as $shippingAddress) {
                $this->loadMyshippingQuote($shippingAddress->getShippingMethod(), $shippingAddress);
            }
        }

        if ($quote->getExtensionAttributes() && $quote->getExtensionAttributes()->getShippingAssignments()) {
            $shippingAssignments = $quote->getExtensionAttributes()->getShippingAssignments();

            $shippingAssignmentsResult = [];
            foreach ($shippingAssignments as $shippingAssignment) {
                $shipping = $shippingAssignment->getShipping();
                $shippingAddress = $shipping->getAddress();
                $this->loadMyshippingQuote($shippingAssignment->getCarrierCode() . "_" . $shippingAssignment->getMethodCode(), $shippingAddress);
                $shipping->setAddress($shippingAddress);
                $shippingAssignment->setShipping($shipping);
                $shippingAssignmentsResult[] = $shippingAssignment;
            }

            if (!empty($shippingAssignmentsResult)) {
                $quote->getExtensionAttributes()->setShippingAssignments($shippingAssignmentsResult);
            }
        }

        return $quote;
    }

    private function loadMyshippingQuote($shippingMethod, $shippingAddress) {

        $extAttributes = $shippingAddress->getExtensionAttributes();
        if(
            !$extAttributes
            || (!$extAttributes->getMyshippingCourierId() && !$extAttributes->getMyshippingAccountId())
        ) {
            if(
                $this->_helper->isMyshippingMethod($shippingMethod) &&
                $extAttributes
            ) {
                $myshippingQuote = $this->_myshippingQuoteRepository->getByAddressId($shippingAddress->getId());
                if ($shippingMethod == Carrier::CODE_NEW) {
                    $extAttributes->setMyshippingCourierId((int)$myshippingQuote->getData('myshipping_courier_id'));
                    $extAttributes->setMyshippingAccount((string)$myshippingQuote->getData('myshipping_account'));
                    $extAttributes->setMyshippingCourierMethod((string)$myshippingQuote->getData('myshipping_courier_method'));
                    $extAttributes->setMyshippingSave((bool)$myshippingQuote->getData('myshipping_save'));
                } else {
                    $accountId = (int) $myshippingQuote->getData('myshipping_account_id');
                    if($account = $this->_accountRepository->getById($accountId)) {
                        $extAttributes->setMyshippingAccountId($account->getId());
                        $extAttributes->setMyshippingCourierId($account->getMyshippingCourierId());
                        $extAttributes->setMyshippingAccount($account->getMyshippingAccount());
                        $extAttributes->setMyshippingCourierMethod((string)$myshippingQuote->getData('myshipping_courier_method'));
                    }
                }
            }
            $shippingAddress->setExtensionAttributes($extAttributes);
        }
    }
}
