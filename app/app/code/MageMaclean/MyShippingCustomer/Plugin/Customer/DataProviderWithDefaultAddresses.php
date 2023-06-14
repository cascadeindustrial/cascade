<?php
namespace MageMaclean\MyShippingCustomer\Plugin\Customer;

use MageMaclean\MyShippingCustomer\Model\Repository\CustomerRepository as MyshippingCustomerRepository;

class DataProviderWithDefaultAddresses
{
    protected $_myshippingCustomerRepository;

    public function __construct(
        MyshippingCustomerRepository $myshippingCustomerRepository
    )
    {
        $this->_myshippingCustomerRepository = $myshippingCustomerRepository;
    }

    public function afterGetData(\Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses $subject, $result)
    {
        if($result){
            $customerId = key($result);
            
            $myshippingCustomer = $this->_myshippingCustomerRepository->getByCustomerId($customerId);
            $result[$customerId]['myshipping']['myshipping_enabled'] = $myshippingCustomer->getMyshippingEnabled();
            $result[$customerId]['myshipping']['myshipping_new_enabled'] = $myshippingCustomer->getMyshippingNewEnabled();
        }
        return $result;
    }
}