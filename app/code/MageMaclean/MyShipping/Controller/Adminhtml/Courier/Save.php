<?php
declare(strict_types=1);

namespace MageMaclean\MyShipping\Controller\Adminhtml\Courier;

use MageMaclean\MyShipping\Ui\EntityUiConfig;
use MageMaclean\MyShipping\Ui\EntityUiManagerInterface;
use MageMaclean\MyShipping\Ui\SaveDataProcessorInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var SaveDataProcessorInterface
     */
    private $dataProcessor;
    /**
     * @var EntityUiManagerInterface
     */
    private $entityUiManager;
    /**
     * @var EntityUiConfig
     */
    private $entityUiConfig;

    public const ADMIN_RESOURCE = 'MageMaclean_MyShipping::myshipping_courier';

    /**
     * Save constructor.
     * @param Context $context
     * @param DataObjectHelper $dataObjectHelper
     * @param DataPersistorInterface $dataPersistor
     * @param SaveDataProcessorInterface $dataProcessor
     * @param EntityUiManagerInterface $entityUiManager
     * @param EntityUiConfig $uiConfig
     */
    public function __construct(
        Context $context,
        DataObjectHelper $dataObjectHelper,
        DataPersistorInterface $dataPersistor,
        SaveDataProcessorInterface $dataProcessor,
        EntityUiManagerInterface $entityUiManager,
        EntityUiConfig $uiConfig
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPersistor = $dataPersistor;
        $this->dataProcessor = $dataProcessor;
        $this->entityUiManager = $entityUiManager;
        $this->entityUiConfig = $uiConfig;
        parent::__construct($context);
    }

    /**
     * run the action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $entity = null;
        $postData = $this->getRequest()->getPostValue();
        $data = $this->dataProcessor->modifyData($postData);
        $requestParam = $this->entityUiConfig->getRequestParamName();
        $persistorKey = $this->entityUiConfig->getPersistoryKey();
        $id = !empty($data[$requestParam]) ? (int)$data[$requestParam] : null;
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $entity = $this->entityUiManager->get($id);
            if (!$id) {
                unset($data[$requestParam]);
            }
            $this->dataObjectHelper->populateWithArray(
                $entity,
                $data,
                $this->entityUiConfig->getInterface()
            );

            if(!$entity->getMethods(true) || !sizeof($entity->getMethods(true))) {
                throw new LocalizedException(__("Must have at least 1 method."));
            }

            $this->entityUiManager->save($entity);
            $this->messageManager->addSuccessMessage($this->entityUiConfig->getSaveSuccessMessage());
            $this->dataPersistor->clear($this->entityUiConfig->getPersistoryKey());
            $back = $this->getRequest()->getParam('back', 'continue');
            if ($back === 'close' && $this->entityUiConfig->getAllowSaveAndClose()) {
                $resultRedirect->setPath('*/*/index');
            } elseif ($back === 'duplicate' && $this->entityUiConfig->getAllowSaveAndDuplicate()) {
                $newEntity = $this->entityUiManager->get(null);
                $this->dataObjectHelper->populateWithArray(
                    $newEntity,
                    $data,
                    $this->entityUiConfig->getInterface()
                );
                $newEntity->setId(null);
                $this->entityUiManager->save($newEntity);
                $mewId = $newEntity->getId();
                $this->messageManager->addSuccessMessage($this->entityUiConfig->getDuplicateSuccessMessage());
                $this->dataPersistor->set($persistorKey, $data);
                $resultRedirect->setPath('*/*/edit', [$requestParam => $mewId]);
            } else {
                $resultRedirect->setPath('*/*/edit', [$requestParam => $entity->getId()]);
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set($persistorKey, $postData);
            $resultRedirect->setPath('*/*/edit', [$requestParam => $id]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($this->entityUiConfig->getSaveErrorMessage());
            $this->dataPersistor->set($persistorKey, $postData);
            $resultRedirect->setPath('*/*/edit', [$requestParam => $id]);
        }
        return $resultRedirect;
    }
}
