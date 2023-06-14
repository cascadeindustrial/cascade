<?php

namespace Cminds\Creditline\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Cminds\Creditline\Helper\Calculation;
use Cminds\Creditline\Helper\Data as CreditHelper;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var CreditHelper
     */
    protected $creditHelper;

    /**
     * @param Calculation  $calculationHelper
     * @param CreditHelper $creditHelper
     */
    public function __construct(
        Calculation $calculationHelper,
        CreditHelper $creditHelper
    ) {
        $this->calculationHelper = $calculationHelper;
        $this->creditHelper = $creditHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        /** @var Quote $quote */
        $quote = $this->creditHelper->getQuote();
        $balance = $this->creditHelper->getBalance($quote->getCustomerId(), $quote->getQuoteCurrencyCode());

        $amount = $balance->getAmount();
        if ($quote->getQuoteCurrencyCode() !== $balance->getCurrencyCode()) {
            $amount = $this->calculationHelper->convertToCurrency(
                $amount,
                $balance->getCurrencyCode(),
                $quote->getQuoteCurrencyCode(),
                $quote->getStore()
            );
        }
        $isAllowed = $this->creditHelper->isAllowedForQuote();
        if ($amount == 0) {
            $isAllowed = 0;
        }
        return [
            'creditConfig' => [
                'isAllowed'  => $isAllowed,
                'amount'     => (float)$amount,
                'amountUsed' => $quote->getUseCredit() == Config::USE_CREDIT_YES,
            ],
        ];
    }
}
