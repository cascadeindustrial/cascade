<?php
namespace MageMaclean\MyShipping\Model\Repository;

use MageMaclean\MyShipping\Model\Carrier;
use MageMaclean\MyShipping\Helper\Data as Helper;
use Magento\Framework\Exception\AbstractAggregateException;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

use MageMaclean\MyShipping\Model\ResourceModel\Myshipping\Quote as ResourceModel;
use MageMaclean\MyShipping\Model\Myshipping\QuoteFactory as ModelFactory;
use MageMaclean\MyShipping\Model\ResourceModel\Myshipping\Quote\CollectionFactory as CollectionFactory;
use Magento\Quote\Api\Data\AddressInterface;

class QuoteRepository
{
    private $resourceModel;
    private $modelFactory;
    private $collectionFactory;
    private $_helper;

    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory,
        Helper $helper
    ) {
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->_helper = $helper;
    }

    public function updateMyshippingQuote(string $method, AddressInterface $address) {
        $myshippingQuote = $this->getByAddressId($address->getId());
        $extAttributes = $address->getExtensionAttributes();

        $myshippingQuote->setMyshippingAccountId(0);
        $myshippingQuote->setMyshippingCourierId(0);
        $myshippingQuote->setMyshippingAccount("");
        $myshippingQuote->setMyshippingCourierMethod("");
        $myshippingQuote->setMyshippingSave(false);

        if ($this->_helper->isMyshippingMethod($method) && $extAttributes) {
            if ($method == Carrier::CODE_NEW) {
                $myshippingQuote->setMyshippingCourierId((int)$extAttributes->getMyshippingCourierId());
                $myshippingQuote->setMyshippingAccount((string)$extAttributes->getMyshippingAccount());
                $myshippingQuote->setMyshippingSave((bool)$extAttributes->getMyshippingSave());
            } else {
                if ($extAttributes->getMyshippingAccountId()) {
                    $myshippingQuote->setMyshippingAccountId((int)$extAttributes->getMyshippingAccountId());
                }
            }
            $myshippingQuote->setMyshippingCourierMethod((string)$extAttributes->getMyshippingCourierMethod());
            $this->save($myshippingQuote);
        } else if($myshippingQuote->getId()) {
            $this->delete($myshippingQuote);
        }
    }

    public function save(\MageMaclean\MyShipping\Model\Myshipping\Quote $model)
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

    public function delete(\MageMaclean\MyShipping\Model\Myshipping\Quote $model)
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

    public function getByAddressId($addressId)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('quote_address_id', $addressId);
        if($collection->count()) {
            return $collection->getFirstItem();
        } else {
            $model = $this->modelFactory->create();
            $model->setQuoteAddressId($addressId);
            return $model;
        }
    }
}
