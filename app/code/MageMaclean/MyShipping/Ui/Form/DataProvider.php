<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui\Form;

use MageMaclean\MyShipping\Ui\CollectionProviderInterface;
use MageMaclean\MyShipping\Ui\EntityUiConfig;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    private $loadedData;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;
    /**
     * @var DataModifierInterface
     */
    private $dataModifier;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionProviderInterface $collectionProvider
     * @param DataPersistorInterface $dataPersistor
     * @param DataModifierInterface $dataModifier
     * @param EntityUiConfig $uiConfig
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        CollectionProviderInterface $collectionProvider,
        DataPersistorInterface $dataPersistor,
        DataModifierInterface $dataModifier,
        EntityUiConfig $uiConfig,
        array $meta = [],
        array $data = []
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->uiConfig = $uiConfig;
        $this->dataModifier = $dataModifier;
        parent::__construct($name, $uiConfig->getRequestParamName(), $uiConfig->getRequestParamName(), $meta, $data);
        $this->collection = $collectionProvider->getCollection();
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        /** @var AbstractModel $entity */
        foreach ($this->collection as $entity) {
            $this->loadedData[$entity->getId()] = $this->dataModifier->modifyData($entity, $entity->getData());
        }
        $persistorKey = $this->uiConfig->getPersistoryKey();
        $data = $this->dataPersistor->get($persistorKey);
        if (!empty($data)) {
            $entity = $this->collection->getNewEmptyItem();
            $entity->setData($data);
            $this->loadedData[$entity->getId()] = $entity->getData();
            $this->dataPersistor->clear($persistorKey);
        }
        return $this->loadedData;
    }
}
