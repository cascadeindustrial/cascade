<?php
namespace Dcw\CreditForm\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $customerSession = $objectManager->get('\Magento\Customer\Model\Session');
    $urlInterface = $objectManager->get('\Magento\Framework\UrlInterface');
    if(!$customerSession->isLoggedIn()) {
       $customerSession->setAfterAuthUrl($urlInterface->getCurrentUrl());
       $customerSession->authenticate();
    }
		return $this->_pageFactory->create();
	}
}
