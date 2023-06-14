<?php


namespace Cminds\Creditline\Controller\Account;

use Magento\Framework\Controller\ResultFactory;
use Cminds\Creditline\Controller\Account;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Subscribe extends Account
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $balance = $this->balanceFactory->create()->loadByCustomer($this->_getSession()->getCustomer());
        $isSubscribed = (bool) $this->getRequest()->getParams('is_subscribed');

        $balance->setIsSubscribed($isSubscribed)->save();

        $this->messageManager->addSuccessMessage(__('Email subscription was successfully updated.'));

        $this->_redirect('*/*/');
    }
}
