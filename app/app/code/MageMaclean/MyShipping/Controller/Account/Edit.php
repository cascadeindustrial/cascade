<?php
namespace MageMaclean\MyShipping\Controller\Account;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;

class Edit extends AbstractAccount implements HttpGetActionInterface
{
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('form');
    }
}
