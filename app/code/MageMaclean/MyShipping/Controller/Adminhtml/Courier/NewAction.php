<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Controller\Adminhtml\Courier;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Forward;

class NewAction extends Action
{
    public const ADMIN_RESOURCE = 'MageMaclean_MyShipping::myshipping_courier';
    
    /**
     * @return Forward
     */
    public function execute()
    {
        /** @var Forward $forward */
        $forward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $forward->forward('edit');
        return $forward;
    }
}
