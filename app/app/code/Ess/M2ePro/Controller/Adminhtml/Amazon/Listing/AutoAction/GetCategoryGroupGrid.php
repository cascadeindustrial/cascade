<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Controller\Adminhtml\Amazon\Listing\AutoAction;

/**
 * Class \Ess\M2ePro\Controller\Adminhtml\Amazon\Listing\AutoAction\GetCategoryGroupGrid
 */
class GetCategoryGroupGrid extends \Ess\M2ePro\Controller\Adminhtml\Amazon\Listing\AutoAction
{
    //########################################

    public function execute()
    {
        $grid = $this->createBlock('Amazon_Listing_AutoAction_Mode_Category_Group_Grid');
        $this->setAjaxContent($grid);
        return $this->getResult();
    }

    //########################################
}
