<?php

namespace Cminds\Creditline\Api;

interface CreditManagementInterface
{
    /**
     * @param int $cartId
     * @param float $creditAmount
     * @return bool
     */
    public function apply($cartId, $creditAmount);

    /**
     * @param int $cartId
     * @param float $creditAmount
     * @return bool
     */
    public function cancel($cartId, $creditAmount);
}
