<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Amazon\Template;

use Ess\M2ePro\Controller\Adminhtml\Amazon\Template;

/**
 * Class \Ess\M2ePro\Controller\Adminhtml\Amazon\Template\Delete
 */
class Delete extends Template
{
    //########################################

    public function execute()
    {
        $ids = $this->getRequestIds();
        $type = $this->getRequest()->getParam('type');

        if (count($ids) == 0) {
            $this->messageManager->addError($this->__('Please select Item(s) to remove.'));
            return $this->_redirect('*/*/index');
        }

        if (empty($type)) {
            $this->messageManager->addError($this->__('You should provide correct parameters.'));
            return $this->_redirect('*/*/index');
        }

        $deleted = $locked = 0;
        foreach ($ids as $id) {
            $template = $this->getTemplateObject($type, $id);

            if ($template->isLocked()) {
                $locked++;
            } else {
                $template->delete();
                $deleted++;
            }
        }

        $tempString = $this->__('%amount% record(s) were successfully deleted.', $deleted);
        $deleted && $this->messageManager->addSuccess($tempString);

        $tempString  = $this->__('%amount% record(s) are used in Listing(s).', $locked) . ' ';
        $tempString .= $this->__('Policy must not be in use to be deleted.');
        $locked && $this->messageManager->addError($tempString);

        $this->_redirect('*/*/index');
    }

    //########################################

    private function getTemplateObject($type, $id)
    {
        switch ($type) {
            case \Ess\M2ePro\Block\Adminhtml\Amazon\Template\Grid::TEMPLATE_SHIPPING_OVERRIDE:
                $model = $this->activeRecordFactory->getObject('Amazon_Template_ShippingOverride')->load($id);
                break;

            case \Ess\M2ePro\Block\Adminhtml\Amazon\Template\Grid::TEMPLATE_SHIPPING_TEMPLATE:
                $model = $this->activeRecordFactory->getObject('Amazon_Template_ShippingTemplate')->load($id);
                break;

            case \Ess\M2ePro\Block\Adminhtml\Amazon\Template\Grid::TEMPLATE_PRODUCT_TAX_CODE:
                $model = $this->activeRecordFactory->getObject('Amazon_Template_ProductTaxCode')->load($id);
                break;

            case \Ess\M2ePro\Block\Adminhtml\Amazon\Template\Grid::TEMPLATE_SELLING_FORMAT:
                $model = $this->amazonFactory->getObjectLoaded('Template\SellingFormat', $id);
                break;

            case \Ess\M2ePro\Block\Adminhtml\Amazon\Template\Grid::TEMPLATE_SYNCHRONIZATION:
            case \Ess\M2ePro\Block\Adminhtml\Amazon\Template\Grid::TEMPLATE_DESCRIPTION:
            default:
                $model = $this->amazonFactory->getObjectLoaded('Template\\' . ucfirst($type), $id);
                break;
        }

        return $model;
    }

    //########################################
}
