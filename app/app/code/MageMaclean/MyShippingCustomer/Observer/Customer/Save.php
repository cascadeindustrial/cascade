<?php

namespace MageMaclean\MyShippingCustomer\Observer\Customer;

use MageMaclean\MyShippingCustomer\Model\Repository\CustomerRepository as MyshippingCustomerRepository;

class Save implements \Magento\Framework\Event\ObserverInterface
{
    protected $_request;
    protected $_myshippingCustomerRepository;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        MyshippingCustomerRepository $myshippingCustomerRepository
    ) {
        $this->_request = $request;
        $this->_myshippingCustomerRepository = $myshippingCustomerRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getCustomer();
        $data = $this->_request->getParams();

        if($data && isset($data['myshipping'])) {
            $myshippingData = $data['myshipping'];
            
            $model = $this->_myshippingCustomerRepository->getByCustomerId($customer->getId());
            
            // ui_component toggle fields sending string instead of boolean 
            $model->setMyshippingEnabled($myshippingData['myshipping_enabled'] == "true");
            $model->setMyshippingNewEnabled($myshippingData['myshipping_new_enabled'] == "true");
            
            $this->_myshippingCustomerRepository->save($model);
        }
    }
}