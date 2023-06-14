<?php

namespace Dcw\Customer\Controller\Minilogin;

use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action {

    protected $resultJsonFactory;
    protected $resultPageFactory;
    /**
     *
     * @var type
     */
    protected $customerSession;

    public function __construct(Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Customer\Model\Session $customerSession
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
    }

    public function execute()
    {
        $jsonData = array();
        $jsonData['res'] = $this->resultPageFactory->create()->getLayout()
                ->createBlock('Dcw\Customer\Block\Form\Loginmini')
                ->setTemplate('Magento_Theme::html/header_login.phtml')
                ->toHtml();
        $result = $this->resultJsonFactory->create();
        return $result->setData($jsonData);

    }

}
