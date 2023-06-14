<?php
namespace Dcw\CustomPricing\Model\Data;

use Dcw\CustomPricing\Model\ResourceModel\CustomPricing\CollectionFactory;
use Dcw\CustomPricing\Model\CustomPricing;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $postCollectionFactory,
        array $meta = [],
        array $data = []
    ){
        $this->collection = $postCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if(isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource=$objectManager->create('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);



        foreach($items as $post)
        {
     //        $logger->info("data");
     // $logger->info($post);
            $this->_loadedData[$post->getId()] = $post->getData();
            $postId = $post->getId();
            $sql = "SELECT * FROM `dcw_custom_price_rules` where id= ".$postId;
            $customPriceRules = $connection->fetchRow($sql);
            if(count($customPriceRules)>0)
            {
              $categories = $customPriceRules['category'];
              $finalFormatCategories = explode(',',$categories);
              //$customAttributes = ['4']; // here you should do your own retrieving of data from the database
              $this->_loadedData[$post->getId()]['category'] = $finalFormatCategories;
            }

        }

        return $this->_loadedData;
    }
//     foreach ($items as $test) {
//     ...
//     $this->loadedData[$test->getId()] = $test->getData();
//
//     ...
//     return $this->loadedData;
// }

}
