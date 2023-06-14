<?php
namespace Dcw\Brands\Controller\Index;

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
		//echo "im'm inside the controller file geethika and calling layout file, the layout file is called and we know the layout file name is based on controller name";

		return $this->_pageFactory->create();
	}
}

?>
