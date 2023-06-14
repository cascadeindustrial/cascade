<?php

namespace Cminds\Creditline\Plugin\Checks;

use Cminds\Creditline\Model\Config;
use Magento\Payment\Model\Checks\ZeroTotal as ChecksZeroTotal;
use Magento\Quote\Model\Quote;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class ZeroTotal
{

    /**
     * @param ChecksZeroTotal      $subject
     * @param callable       $proceed
     * @param AbstractMethod $paymentMethod
     * @param Quote          $quote
     * @return string
     */
    public function aroundIsApplicable(
        ChecksZeroTotal $subject, $proceed, $paymentMethod, Quote $quote
    ) {
        $result = $proceed($paymentMethod, $quote);
        if ($quote->getBaseGrandTotal() < 0.0001 && $quote->getUseCredit() == Config::USE_CREDIT_YES) {
            return true;
        }

        return $result;
    }
}
