<?php
namespace MageMaclean\MyShipping\Plugin\Customer;

class DataProviderWithDefaultAddresses
{
    protected $collection;
    protected $collectionFactory;
    protected $_logger;

    public function __construct(
        \MageMaclean\MyShipping\Model\ResourceModel\Account\Collection $collection,
        \MageMaclean\MyShipping\Model\ResourceModel\Account\CollectionFactory $collectionFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->collection = $collection;
        $this->collectionFactory = $collectionFactory;
        $this->_logger = $logger;
    }

    public function afterGetData(\Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses $subject, $result)
    {
        if($result){
            $customer_id = key($result);
        
            $collection = $this->collectionFactory->create(); 
            $collection->addFieldToFilter('customer_id', array('eq' => $customer_id));
            $collection->setOrder('position', 'ASC');

            if($collection->count()) {
                $position = 1;
                $items = $collection->getItems();
                foreach ($items as $item) {
                    $itemData = $item->getData();
                    $itemData['position'] = $position;
                    $result[$customer_id]['myshipping']['myshipping_accounts']['myshipping_accounts'][] = $itemData;
                    $position++;
                }
            }
        }
        return $result;
    }

}