<?php
namespace MageMaclean\MyShipping\Model\Myshipping;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Carrier;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;

class ResultProcessor
{
    protected $_helper;
    protected $_resultFactory;
    protected $_accountRepository;

    public function __construct(
        Helper $helper,
        ResultFactory $resultFactory,
        AccountRepository $accountRepository
    ) {
        $this->_helper = $helper;
        $this->_resultFactory = $resultFactory;
        $this->_accountRepository = $accountRepository;
    }

    /**
     * Create shipping assignment
     *
     * @param string $shippingMethod
     * @param AddressInterface $shippingAddress
     * @param array $items
     * @return \MageMaclean\MyShipping\Api\Data\MyshippingResultInterface
     */
    public function create($shippingMethod, $shippingAddress, $items)
    {
        $myshippingResult = $this->_resultFactory->create();
        $myshippingResult->setItems($items);

        if(!$shippingMethod) return $myshippingResult;
        if(!$this->_helper->isMyshippingMethod($shippingMethod)) return $myshippingResult;
        if(!$extAttributes = $shippingAddress->getExtensionAttributes()) return $myshippingResult;

        if($shippingMethod == Carrier::CODE_NEW) {
            $myshippingResult->setMyshippingCourierId((int)$extAttributes->getMyshippingCourierId());
            $myshippingResult->setMyshippingAccount((string)$extAttributes->getMyshippingAccount());
            $myshippingResult->setMyshippingSave((bool)$extAttributes->getMyshippingSave());
        } else {
            $account = $this->_accountRepository->getById($extAttributes->getMyshippingAccountId());
            $myshippingResult->setMyshippingAccountId((int)$account->getId());
            $myshippingResult->setMyshippingCourierId((int)$account->getMyshippingCourierId());
            $myshippingResult->setMyshippingAccount((string)$account->getMyshippingAccount());
        }
        $myshippingResult->setMyshippingCourierMethod((string)$extAttributes->getMyshippingCourierMethod());

        return $myshippingResult;
    }
}
