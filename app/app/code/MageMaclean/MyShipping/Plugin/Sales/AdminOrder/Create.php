<?php
namespace MageMaclean\MyShipping\Plugin\Sales\AdminOrder;

use MageMaclean\MyShipping\Helper\Data as Helper;

use MageMaclean\MyShipping\Model\Repository\QuoteRepository as MyshippingQuoteRepository;
use Magento\Quote\Api\Data\CartExtensionFactory;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use MageMaclean\MyShipping\Model\Carrier;


class Create
{
    protected $_cartExtensionFactory;
    protected $_helper;
    protected $_accountRepository;
    protected $_myshippingQuoteRepository;

    public function __construct(
        CartExtensionFactory $cartExtensionFactory,
        Helper $helper,
        AccountRepository $accountRepository,
        MyshippingQuoteRepository $myshippingQuoteRepository
    ) {
        $this->_cartExtensionFactory = $cartExtensionFactory;
        $this->_helper = $helper;
        $this->_accountRepository = $accountRepository;
        $this->_myshippingQuoteRepository = $myshippingQuoteRepository;
    }

    private function getMethodDataFromJson($json)
    {
        $data = (array)json_decode($json, true);
        if (!is_array($data) || count($data) <= 1) return false;
        if(!isset($data['myshipping_account_id']) && !isset($data['myshipping_courier_id'])) return false;

        return $data;
    }

    public function beforeSetShippingMethod(\Magento\Sales\Model\AdminOrder\Create $subject, $method) {
        if(!$this->_helper->isEnabled()) return $method;

        $quote = $subject->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        $extAttributes = $shippingAddress->getExtensionAttributes();
        if(!$extAttributes) {
            $extAttributes = $this->addressExtension->create();
        }
        if($methodData = $this->getMethodDataFromJson($method)) {
            // Load myshipping data from POST submission
            if(isset($methodData['myshipping_account_id']) && $methodData['myshipping_account_id']) {
                $extAttributes->setMyshippingAccountId((int)$methodData['myshipping_account_id']);
                $method = Carrier::CODE . "_account_" . (int)$methodData['myshipping_account_id'];
            } else {
                $extAttributes->setMyshippingCourierId((int)$methodData['myshipping_courier_id']);
                $extAttributes->setMyshippingAccount((string)$methodData['myshipping_account']);
                $extAttributes->setMyshippingSave((bool)$methodData['myshipping_save']);
                $method = Carrier::CODE_NEW;
            }
            $extAttributes->setMyshippingCourierMethod($methodData['myshipping_courier_method']);
        } else if($this->_helper->isMyshippingMethod($method)) {
            // Load myshipping data from myshipping quote model
            $myshippingQuote = $this->_myshippingQuoteRepository->getByAddressId($shippingAddress->getId());
            $extAttributes->setMyshippingAccountId($myshippingQuote->getMyshippingAccountId());
            $extAttributes->setMyshippingCourierId($myshippingQuote->getMyshippingCourierId());
            $extAttributes->setMyshippingAccount($myshippingQuote->getMyshippingAccount());
            $extAttributes->setMyshippingCourierMethod($myshippingQuote->getMyshippingCourierMethod());
            $extAttributes->setMyshippingSave($myshippingQuote->getMyshippingSave());
        } else {
            // Unset myshipping data
            $extAttributes->setMyshippingAccountId(0);
            $extAttributes->setMyshippingCourierId(0);
            $extAttributes->setMyshippingCourierMethod("");
            $extAttributes->setMyshippingAccount("");
            $extAttributes->setMyshippingSave(false);
        }
        $shippingAddress->setExtensionAttributes($extAttributes);

        $quote->setShippingAddress($shippingAddress);
        #$this->_myshippingQuoteRepository->updateMyshippingQuote($method, $shippingAddress);

        return $method;
    }

    public function afterSetShippingMethod(\Magento\Sales\Model\AdminOrder\Create $subject, $result, $method) {
        $address = $subject->getQuote()->getShippingAddress();
        $this->_myshippingQuoteRepository->updateMyshippingQuote($method, $address);

        return $result;
    }
}
