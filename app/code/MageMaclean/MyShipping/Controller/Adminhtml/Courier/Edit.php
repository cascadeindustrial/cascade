<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Controller\Adminhtml\Courier;

use MageMaclean\MyShipping\Ui\EntityUiConfig;
use MageMaclean\MyShipping\Ui\EntityUiManagerInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @var EntityUiManagerInterface
     */
    private $entityUiManager;
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;
    public const ADMIN_RESOURCE = 'MageMaclean_MyShipping::myshipping_courier';

    /**
     * Edit constructor.
     * @param Context $context
     * @param EntityUiManagerInterface $entityUiManager
     * @param EntityUiConfig $uiConfig
     */
    public function __construct(Context $context, EntityUiManagerInterface $entityUiManager, EntityUiConfig $uiConfig)
    {
        $this->entityUiManager = $entityUiManager;
        $this->uiConfig = $uiConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam($this->uiConfig->getRequestParamName());
        $entity = $this->entityUiManager->get($id);
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $activeMenu = $this->uiConfig->getMenuItem();
        if ($activeMenu) {
            $resultPage->setActiveMenu($activeMenu);
        }
        $resultPage->getConfig()->getTitle()->prepend($this->uiConfig->getListPageTitle());
        if (!$entity->getId()) {
            $resultPage->getConfig()->getTitle()->prepend($this->uiConfig->getNewLabel());
        } else {
            $resultPage->getConfig()->getTitle()->prepend($entity->getData($this->uiConfig->getNameAttribute()));
        }
        return $resultPage;
    }
}
