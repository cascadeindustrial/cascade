<?php

namespace Dcw\CustomPricing\Controller\Adminhtml\CustomPricing;

class Newform extends \Magento\Backend\App\Action
{
	protected $resultPageFactory = false;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Dcw_CustomPricing::newform');
		$resultPage->getConfig()->getTitle()->prepend((__('Add New Pricing Rule')));
		return $resultPage;
	}
}