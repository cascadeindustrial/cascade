<?php
namespace Cminds\Creditline\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

abstract class Balance extends Action
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
        $this->resultFactory = $context->getResultFactory();
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     * @param Interceptor $resultPage
     * @return Interceptor
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Cminds_Creditline::creditline_transaction');
        $resultPage->getConfig()->getTitle()->prepend(__('Credit Line'));
        $resultPage->getConfig()->getTitle()->prepend(__('Customers'));

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Cminds_Creditline::creditline_balance');
    }
}
