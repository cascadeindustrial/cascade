<?php
namespace MageMaclean\MyShippingCustomer\Model\Repository;

use Magento\Framework\Exception\AbstractAggregateException;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

use MageMaclean\MyShippingCustomer\Model\ResourceModel\Customer as ResourceModel;
use MageMaclean\MyShippingCustomer\Model\CustomerFactory as ModelFactory;
use MageMaclean\MyShippingCustomer\Model\ResourceModel\Customer\CollectionFactory as CollectionFactory;

class CustomerRepository
{
    private $resourceModel;
    private $modelFactory;
    private $collectionFactory;
    
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory
    ) {
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function save(\MageMaclean\MyShippingCustomer\Model\Customer $model)
    {
        try {
            $this->resourceModel->save($model);
        } catch (AlreadyExistsException $e) {
            throw $e;
        } catch (\Exception $originalException) {
            throw new CouldNotSaveException(__('Unable to save My Shipping quote'), $originalException);
        }
        return $this;
    }

    public function delete(\MageMaclean\MyShippingCustomer\Model\Customer $model)
    {
        if($model->getId()) {
            try {
                $this->resourceModel->delete($model);
            } catch (\Exception $originalException) {
                throw new CouldNotDeleteException(__('Unable to delete My Shipping quote'), $originalException);
            }
        }
        return $this;
    }

    public function getById($id) {
        return $this->modelFactory->create()->load($id);
    }

    public function getByCustomerId($customerId)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('customer_id', $customerId);
        if($collection->count()) {
            return $collection->getFirstItem();
        } else {
            $model = $this->modelFactory->create();
            $model->setCustomerId($customerId);
            return $model;
        }
    }
}
