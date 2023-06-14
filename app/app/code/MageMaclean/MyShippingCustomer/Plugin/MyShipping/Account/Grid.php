<?php
namespace MageMaclean\MyShippingCustomer\Plugin\Myshipping\Account;

use MageMaclean\MyShippingCustomer\Model\Repository\CustomerRepository as MyshippingCustomerRepository;

class Grid
{
    protected $_myshippingCustomerRepository;

    public function __construct(
        MyshippingCustomerRepository $myshippingCustomerRepository
    )
    {
        $this->_myshippingCustomerRepository = $myshippingCustomerRepository;
    }

    public function afterGetAddAccountEnabled(\MageMaclean\MyShipping\Block\Account\Grid $subject, $result)
    {
        if(!$result) return $result;

        $customer = $subject->getCustomer();
        $myshippingCustomer = $this->_myshippingCustomerRepository->getByCustomerId($customer->getId());
        
        return $myshippingCustomer->getMyshippingNewEnabled();
    }

    public function afterGetEditAccountEnabled(\MageMaclean\MyShipping\Block\Account\Grid $subject, $result)
    {
        if(!$result) return $result;

        $customer = $subject->getCustomer();
        $myshippingCustomer = $this->_myshippingCustomerRepository->getByCustomerId($customer->getId());
        
        return $myshippingCustomer->getMyshippingEnabled();
    }
}