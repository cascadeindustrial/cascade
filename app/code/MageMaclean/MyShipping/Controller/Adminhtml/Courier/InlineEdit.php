<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Controller\Adminhtml\Courier;

use MageMaclean\MyShipping\Ui\EntityUiManagerInterface;
use MageMaclean\MyShipping\Ui\SaveDataProcessorInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

class InlineEdit extends Action implements HttpPostActionInterface
{
    /**
     * @var SaveDataProcessorInterface
     */
    private $dataProcessor;
    /**
     * @var EntityUiManagerInterface
     */
    private $entityUiManager;

    public const ADMIN_RESOURCE = 'MageMaclean_MyShipping::myshipping_courier';

    /**
     * InlineEdit constructor.
     * @param SaveDataProcessorInterface $dataProcessor
     * @param EntityUiManagerInterface $entityUiManager
     */
    public function __construct(
        Context $context,
        SaveDataProcessorInterface $dataProcessor,
        EntityUiManagerInterface $entityUiManager
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->entityUiManager = $entityUiManager;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json
     * |\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $id) {
                    try {
                        $entity = $this->entityUiManager->get($id);
                        $newData = $this->dataProcessor->modifyData($postItems[$id]);
                        // phpcs:disable Magento2.Performance.ForeachArrayMerge
                        $entity->setData(array_merge($entity->getData(), $newData));
                        // phpcs:enable
                        $this->entityUiManager->save($entity);
                    } catch (\Exception $e) {
                        $messages[] = '[' . __('Error') .  ': ' . $id . '] ' . $e->getMessage();
                        $error = true;
                    }
                }
            }
        }
        $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
        return $resultJson;
    }
}
