<?php
namespace Cminds\Creditline\Api\Config;

interface CalculationConfigInterface
{

    /**
     * @param null|int $store
     * @return bool
     */
    public function isTaxIncluded($store = null);

    /**
     * @param null|int $store
     * @return bool
     */
    public function IsShippingIncluded($store = null);
}