<?php

namespace MageMaclean\MyShipping\Plugin\Multishipping\Checkout;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote\Address\Total\Collector;
use Magento\Store\Model\ScopeInterface;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Myshipping\ResultProcessor;

class Overview
{
    protected $_multishipping;
    protected $priceCurrency;
    protected $_helper;
    protected $_resultProcessor;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping,
        PriceCurrencyInterface $priceCurrency,
        ScopeConfigInterface $scopeConfig,
        Helper $helper,
        ResultProcessor $resultProcessor
    ) {
        $this->_multishipping = $multishipping;
        $this->priceCurrency = $priceCurrency;
        $this->_scopeConfig = $scopeConfig;
        $this->_helper = $helper;
        $this->_resultProcessor = $resultProcessor;
    }

    public function getCheckout()
    {
        return $this->_multishipping;
    }

    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

    private function getMultishippingTotals($totals)
    {

        $total = $totals['shipping'];
        $shippingMethod = $total->getAddress()->getShippingMethod();

        $myshippingResult = $this->_resultProcessor->create($shippingMethod, $total->getAddress(), $total->getAddress()->getAllVisibleItems());
        $shippingPrice = $myshippingResult->getShippingPrice();
        $store = $this->getQuote()->getStore();
        $amountPrice = $store->getBaseCurrency()
            ->convert($shippingPrice, $store->getCurrentCurrencyCode());
        $total->setBaseShippingAmount($shippingPrice);
        $total->setShippingAmount($amountPrice);
        $total->setValue($amountPrice);

        return $totals;
    }

    public function aroundRenderTotals(
        \Magento\Multishipping\Block\Checkout\Overview $subject,
        callable $proceed,
        $totals,
        $colspan = null
    ) {
        $total = $totals['shipping'];
        $shippingMethod = $total->getAddress()->getShippingMethod();
        if(!$this->_helper->isMyshippingMethod($shippingMethod)) return $proceed($totals, $colspan);

        // check if the shipment is multi shipment
        $totals = $this->getMultishippingTotals($totals);

        // sort totals by configuration settings
        $totals = $this->sortTotals($totals);

        if ($colspan === null) {
            $colspan = 3;
        }
        $totals = $subject->getChildBlock(
                'totals'
            )->setTotals(
                $totals
            )->renderTotals(
                '',
                $colspan
            ) . $subject->getChildBlock(
                'totals'
            )->setTotals(
                $totals
            )->renderTotals(
                'footer',
                $colspan
            );
        return $totals;
    }

    public function afterGetShippingAddressTotals(
        \Magento\Multishipping\Block\Checkout\Overview $subject,
        $result
    ) {
        $total = $result['shipping'];
        if($total) {
            $shippingAddress = $total->getAddress();
            $shippingMethod = $shippingAddress->getShippingMethod();
            if($this->_helper->isMyshippingMethod($shippingMethod)) {
                $myshippingResult = $this->_resultProcessor->create($shippingMethod, $shippingAddress, $shippingAddress->getAllVisibleItems());
                $shippingPrice = $myshippingResult->getShippingPrice();
                $store = $subject->getQuote()->getStore();
                $amountPrice = $store->getBaseCurrency()
                    ->convert($shippingPrice, $store->getCurrentCurrencyCode());
                $total->setBaseShippingAmount($shippingPrice);
                $total->setShippingAmount($amountPrice);
                $total->setValue($amountPrice);
                #$result['shipping'] = $total;
            }
        }

        return $result;
    }

    public function afterGetShippingAddressRate(
        \Magento\Multishipping\Block\Checkout\Overview $subject,
        $result,
        $shippingAddress
    ) {
        if($result && $this->_helper->isMyshippingMethod($result->getCode())) {
            $shippingMethod = $shippingAddress->getShippingMethod();
            $myshippingResult = $this->_resultProcessor->create($shippingMethod, $shippingAddress, $shippingAddress->getAllVisibleItems());

            $courierTitle = $myshippingResult->getCourier()->getTitle();
            $methodTitle = $myshippingResult->getCourier()->getMethodTitle($myshippingResult->getMyshippingCourierMethod());
            $account = $myshippingResult->getMyshippingAccount();
            
            $result->setCarrierTitle($courierTitle);
            $result->setMethodTitle($account . " - " . $methodTitle);
            $result->setPrice($myshippingResult->getShippingPrice());
        }
        return $result;
    }

    public function formatPrice($price)
    {
        return $this->priceCurrency->format(
            $price,
            true,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $this->getQuote()->getStore()
        );
    }

    private function sortTotals($totals): array
    {
        $sortedTotals = [];
        $sorts = $this->_scopeConfig->getValue(
            Collector::XML_PATH_SALES_TOTALS_SORT,
            ScopeInterface::SCOPE_STORES
        );

        $sorted = [];
        foreach ($sorts as $code => $sortOrder) {
            $sorted[$sortOrder] = $code;
        }
        ksort($sorted);

        foreach ($sorted as $code) {
            if (isset($totals[$code])) {
                $sortedTotals[$code] = $totals[$code];
            }
        }

        $notSorted = array_diff(array_keys($totals), array_keys($sortedTotals));
        foreach ($notSorted as $code) {
            $sortedTotals[$code] = $totals[$code];
        }

        return $sortedTotals;
    }
}
