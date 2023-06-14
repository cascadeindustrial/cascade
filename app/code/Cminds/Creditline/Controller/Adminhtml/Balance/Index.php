<?php
namespace Cminds\Creditline\Controller\Adminhtml\Balance;

use Magento\Framework\Controller\ResultFactory;
use Cminds\Creditline\Controller\Adminhtml\Balance;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Index extends Balance
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage);

        $this->_addContent($resultPage->getLayout()
            ->createBlock('Cminds\Creditline\Block\Adminhtml\Balance'));

        return $resultPage;
    }
}
