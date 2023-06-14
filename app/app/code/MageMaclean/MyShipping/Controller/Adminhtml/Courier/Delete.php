<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Controller\Adminhtml\Courier;

use MageMaclean\MyShipping\Ui\EntityUiConfig;
use MageMaclean\MyShipping\Ui\EntityUiManagerInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;
    /**
     * @var EntityUiManagerInterface
     */
    private $uiManager;

    public const ADMIN_RESOURCE = 'MageMaclean_MyShipping::myshipping_courier';

    /**
     * Delete constructor.
     * @param Context $context
     * @param EntityUiConfig $uiConfig
     * @param EntityUiManagerInterface $uiManager
     */
    public function __construct(Context $context, EntityUiConfig $uiConfig, EntityUiManagerInterface $uiManager)
    {
        $this->uiConfig = $uiConfig;
        $this->uiManager = $uiManager;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $paramName = $this->uiConfig->getRequestParamName();
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $id = (int)$this->getRequest()->getParam($paramName);
        if ($id) {
            try {
                $this->uiManager->delete($id);
                $this->messageManager->addSuccessMessage($this->uiConfig->getDeleteSuccessMessage());
                $resultRedirect->setPath('*/*/');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage($this->uiConfig->getDeleteMissingEntityMessage());
                $resultRedirect->setPath('*/*/');
                return $resultRedirect;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $resultRedirect->setPath('*/*/edit', [$paramName => $id]);
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($this->uiConfig->getGeneralDeleteErrorMessage());
                $resultRedirect->setPath('*/*/edit', [$paramName => $id]);
                return $resultRedirect;
            }
        }
        $this->messageManager->addErrorMessage($this->uiConfig->getDeleteMissingEntityMessage());
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}
