<?php
namespace MageMaclean\Core\Plugin;

use MageMaclean\Core\Helper\Data as Helper;
use Magento\Backend\Model\Menu\Builder\AbstractCommand;

class RemoveMenu
{
    const MAGEMACLEAN_CORE = 'MageMaclean_Core::magemaclean';

    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function afterExecute(AbstractCommand $subject, $itemParams)
    {
        if (!$this->helper->getGeneralConfig('menu')) {
            if ((isset($itemParams['id']) && $itemParams['id'] === self::MAGEMACLEAN_CORE)
                || (isset($itemParams['parent']) && $itemParams['parent'] === self::MAGEMACLEAN_CORE)) {
                $itemParams['removed'] = true;
            }
        }

        return $itemParams;
    }
}