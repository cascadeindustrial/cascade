<?php

namespace Cminds\Creditline\Service\Config;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Cminds\Creditline\Api\Config\CalculationConfigInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CalculationConfig implements CalculationConfigInterface
{
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function isTaxIncluded($store = null)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function IsShippingIncluded($store = null)
    {
        return true;
    }
}