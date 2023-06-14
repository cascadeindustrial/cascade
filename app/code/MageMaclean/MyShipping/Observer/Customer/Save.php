<?php

namespace MageMaclean\MyShipping\Observer\Customer;

use MageMaclean\MyShipping\Model\AccountFactory as ModelFactory;
use MageMaclean\MyShipping\Model\ResourceModel\Account\CollectionFactory as CollectionFactory;
use MageMaclean\MyShipping\Model\Repository\AccountRepository as ModelRepository;

class Save implements \Magento\Framework\Event\ObserverInterface
{
    protected $_request;
    protected $_repository;
    protected $_modelFactory;
    protected $_collectionFactory;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        ModelRepository $repository,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->_request = $request;
        $this->_repository = $repository;
        $this->_modelFactory = $modelFactory;
        $this->_collectionFactory = $collectionFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getCustomer();
        $data = $this->_request->getParams();

        $collection = $this->_collectionFactory->create();
        $collection->addFieldToFilter('customer_id', array('eq' => $customer->getId()));
        $currentAccounts = array();
        if($collection->count()) {
            foreach($collection as $model) {
                $currentAccounts[$model->getId()] = $model;
            }
        }

        if($data && isset($data['myshipping'])) {
            $myshippingData = $data['myshipping'];
            if(array_key_exists('myshipping_accounts', $myshippingData)){
                $myshippingAccounts = $myshippingData['myshipping_accounts']['myshipping_accounts'];
                foreach($myshippingAccounts as $account) {
                    if(isset($account['id'])) {
                        $model = $currentAccounts[$account['id']];
                        unset($currentAccounts[$account['id']]);
                    } else {
                        $model = $this->_modelFactory->create();
                        $model->setCustomerId($customer->getId());
                    }
                    $model->setMyshippingCourierId($account['myshipping_courier_id']);
                    $model->setMyshippingAccount($account['myshipping_account']);
                    $model->setPosition($account['position']);
                    $this->_repository->save($model);
                }
            }
        }

        if($currentAccounts && sizeof($currentAccounts)) {
            foreach($currentAccounts as $model) {
                $this->_repository->delete($model);
            }
        }
    }
}