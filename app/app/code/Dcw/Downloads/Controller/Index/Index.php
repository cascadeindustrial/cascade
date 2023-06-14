<?php
namespace Dcw\Downloads\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Variable\Model\VariableFactory;
use Psr\Log\LoggerInterface;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $logger;
    protected $resultPageFactory;
    protected $variableFactory;
    protected  $_productloader;
    protected $_pageFactory;
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        VariableFactory $variableFactory,
        ResultFactory $resultPageFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader
    )
    {
        $this->logger = $logger;
        $this->resultPageFactory = $resultPageFactory;
        $this->_productloader = $_productloader;
        $this->variableFactory = $variableFactory;
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
      
    return $this->_pageFactory->create();

        
    }
   
}