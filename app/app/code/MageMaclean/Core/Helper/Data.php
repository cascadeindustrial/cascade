<?php
namespace MageMaclean\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    const CONFIG_PATH = 'magemaclean';

    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    public function isEnabled()
    {
        return $this->getGeneralConfig('enabled');
    }

    public function getGeneralConfig($field) {
        return $this->scopeConfig->getValue(self::CONFIG_PATH . '/general/' . $field);
    }
}