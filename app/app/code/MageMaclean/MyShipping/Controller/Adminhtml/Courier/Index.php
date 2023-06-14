<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Controller\Adminhtml\Courier;

use MageMaclean\MyShipping\Ui\EntityUiConfig;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;

    /**
     * Index constructor.
     * @param Context $context
     * @param EntityUiConfig $uiConfig
     */
    public const ADMIN_RESOURCE = 'MageMaclean_MyShipping::myshipping_courier';
    
    public function __construct(Context $context, EntityUiConfig $uiConfig)
    {
        parent::__construct($context);
        $this->uiConfig = $uiConfig;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $listMenuItem = $this->uiConfig->getMenuItem();
        if ($listMenuItem) {
            $resultPage->setActiveMenu($listMenuItem);
        }
        $pageTitle = $this->uiConfig->getListPageTitle();
        if ($pageTitle) {
            $resultPage->getConfig()->getTitle()->prepend($pageTitle);
        }
        return $resultPage;
    }
}
