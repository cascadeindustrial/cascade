<?php
namespace MageMaclean\MyShipping\Model\Repository;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

use MageMaclean\MyShipping\Model\Account as Model;
use MageMaclean\MyShipping\Model\ResourceModel\Account as ResourceModel;
use MageMaclean\MyShipping\Model\AccountFactory as ModelFactory;
use MageMaclean\MyShipping\Model\ResourceModel\Account\CollectionFactory as CollectionFactory;

class AccountRepository
{
    private $resourceModel;
    private $modelFactory;
    private $collectionFactory;
    private $_customerSession;

    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory,
        CustomerSession $customerSession
    ) {
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->_customerSession = $customerSession;
    }

    public function save(\MageMaclean\MyShipping\Model\Account $model)
    {
        try {
            $this->resourceModel->save($model);
        } catch (AlreadyExistsException $e) {
            throw $e;
        } catch (\Exception $originalException) {
            throw new CouldNotSaveException(__('Unable to save My Shipping Account'), $originalException);
        }
        return $this;
    }

    public function delete(\MageMaclean\MyShipping\Model\Account $model)
    {
        if($model->getId()) {
            try {
                $this->resourceModel->delete($model);
            } catch (\Exception $originalException) {
                throw new CouldNotDeleteException(__('Unable to delete My Shipping Account'), $originalException);
            }
        }
        return $this;
    }

    public function getById($accountId) {
        return $this->modelFactory->create()->load($accountId);
    }

    public function getListByCustomerId($customerId)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('customer_id', $customerId);
        $collection->setOrder('position', 'desc');

        return $collection;
    }

    public function getListByCourierId($courierId)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('myshipping_courier_id', $courierId);
        $collection->setOrder('position', 'desc');

        return $collection;
    }

    public function getCustomerAccountById($accountId) {
        if(!$this->_customerSession->isLoggedIn()) {
            throw new NoSuchEntityException(__('Must be logged in to access your shipping account'));
        }

        $model = $this->modelFactory->create()->load($accountId);
        if($model->getCustomerId() != $this->_customerSession->getCustomerId()) {
            throw new NoSuchEntityException(__('This shipping account does not belong to you'));
        }

        return $model;
    }
}
