<?php
namespace MageMaclean\MyShippingCustomer\Plugin\MyShipping;

use MageMaclean\MyShippingCustomer\Model\Repository\CustomerRepository as MyshippingCustomerRepository;

class Carrier
{
    protected $_myshippingCustomerRepository;
    
    public function __construct(
        MyshippingCustomerRepository $myshippingCustomerRepository
    )
    {
        $this->_myshippingCustomerRepository = $myshippingCustomerRepository;
    }

    public function afterIsEnabled(\MageMaclean\MyShipping\Model\Carrier $subject, $result) {
        if($result) {
            if($customerId = $subject->getCustomerId()) {
                $customer = $this->_myshippingCustomerRepository->getByCustomerId($customerId);
                return $customer->getId() && $customer->getMyshippingEnabled() ? true : false;
            } else {
                return false;
            }
        }
        return $result;
    }

    public function afterIsNewEnabled(\MageMaclean\MyShipping\Model\Carrier $subject, $result) {
        if($result) {
            if($customerId = $subject->getCustomerId()) {
                $customer = $this->_myshippingCustomerRepository->getByCustomerId($customerId);
                return $customer->getId() && $customer->getMyshippingNewEnabled();
            } else {
                return false;
            }
        }
        return $result;
    }
}
