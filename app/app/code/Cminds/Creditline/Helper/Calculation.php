<?php

namespace Cminds\Creditline\Helper;

use Magento\Directory\Model\Currency;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Pricing\PriceCurrencyInterface as CurrencyHelper;
use Cminds\Creditline\Api\Config\CalculationConfigInterface;
use Cminds\Creditline\Model\Config;
use Cminds\Creditline\Model\Balance;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Calculation extends AbstractHelper
{
    public function __construct(
        CalculationConfigInterface $calculationConfig,
        Config $config,
        CurrencyFactory $dirCurrencyFactory,
        CurrencyHelper $currencyHelper,
        Context $context
    ) {
        parent::__construct($context);

        $this->calculationConfig = $calculationConfig;
        $this->config = $config;
        $this->currencyHelper  = $currencyHelper;
        $this->dirCurrencyFactory = $dirCurrencyFactory;
    }

    /**
     * @param float $credits
     * @param float $tax
     * @param float $shipping
     * @return float
     */
    public function calc($credits, $tax = 0.00, $shipping = 0.00)
    {
        if (!$this->calculationConfig->IsShippingIncluded()) {
            $credits -= $shipping;
        }
        if (!$this->calculationConfig->isTaxIncluded()) {
            $credits -= $tax;
        }

        return $credits;
    }

    /**
     * @param float $amount
     * @param AbstractModel|string|null $fromCurrency
     * @param AbstractModel|string|null $toCurrency
     * @param null|string|bool|int|ScopeInterface $store
     *
     * @return float
     */
    public function convertToCurrency($amount, $fromCurrency, $toCurrency, $store)
    {
        if (!$fromCurrency instanceof Currency) {
            $fromCurrency = $this->currencyHelper->getCurrency($store, $fromCurrency);
        }
        if (!$toCurrency instanceof Currency) {
            $toCurrency = $this->currencyHelper->getCurrency($store, $toCurrency);
        }

        try {
            $converted = $fromCurrency->convert($amount, $toCurrency);
        } catch (\Exception $e) {
            $converted = $this->calcCurrencyRate($amount, $fromCurrency, $toCurrency);
        }

        return $converted;
    }

    /**
     * @param float $amount
     * @param AbstractModel $fromCurrency
     * @param AbstractModel $toCurrency
     * @return float
     */
    public function calcCurrencyRate($amount, $fromCurrency, $toCurrency)
    {
        if (
            $fromCurrency->getCurrencyCode() == $toCurrency->getCurrencyCode() ||
            $this->config->getShareBalance() == Balance::SHARE_BALANCE_CURRENCY
        ) {
            return $amount;
        }

        $currencyModel = $this->dirCurrencyFactory->create();
        $rates = $currencyModel->getCurrencyRates(
            $fromCurrency->getCurrencyCode(), $toCurrency->getCurrencyCode()
        );
        if (!count($rates) || !isset($rates[$toCurrency->getCurrencyCode()])) {
            $rates = $currencyModel->getCurrencyRates(
                $toCurrency->getCurrencyCode(), $fromCurrency->getCurrencyCode()
            );
            $currencyRate = 1 / $rates[$fromCurrency->getCurrencyCode()];
            $rates[$toCurrency->getCurrencyCode()] = $currencyRate;
        }
        $rate = $rates[$toCurrency->getCurrencyCode()];

        return $this->currencyHelper->round($amount * $rate);
    }
}
