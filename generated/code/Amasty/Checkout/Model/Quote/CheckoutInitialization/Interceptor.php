<?php
namespace Amasty\Checkout\Model\Quote\CheckoutInitialization;

/**
 * Interceptor class for @see \Amasty\Checkout\Model\Quote\CheckoutInitialization
 */
class Interceptor extends \Amasty\Checkout\Model\Quote\CheckoutInitialization implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Amasty\Checkout\Model\Config $checkoutConfig, \Amasty\Checkout\Model\FieldsDefaultProvider $defaultProvider, \Magento\Framework\Webapi\ServiceOutputProcessor $outputProcessor, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Amasty\Checkout\Model\Quote\Shipping\AddressMethods $addressMethods, \Magento\Payment\Model\MethodList $paymentMethodList, \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalsRepository)
    {
        $this->___init();
        parent::__construct($checkoutConfig, $defaultProvider, $outputProcessor, $quoteRepository, $addressMethods, $paymentMethodList, $cartTotalsRepository);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethods($quote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingMethods');
        if (!$pluginInfo) {
            return parent::getShippingMethods($quote);
        } else {
            return $this->___callPlugins('getShippingMethods', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddress($quote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingAddress');
        if (!$pluginInfo) {
            return parent::getShippingAddress($quote);
        } else {
            return $this->___callPlugins('getShippingAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initializeShipping(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'initializeShipping');
        if (!$pluginInfo) {
            return parent::initializeShipping($quote);
        } else {
            return $this->___callPlugins('initializeShipping', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentArray(\Magento\Quote\Api\Data\CartInterface $quote) : array
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaymentArray');
        if (!$pluginInfo) {
            return parent::getPaymentArray($quote);
        } else {
            return $this->___callPlugins('getPaymentArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveInitial(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveInitial');
        if (!$pluginInfo) {
            return parent::saveInitial($quote);
        } else {
            return $this->___callPlugins('saveInitial', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentMethods(\Magento\Quote\Api\Data\CartInterface $quote) : array
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaymentMethods');
        if (!$pluginInfo) {
            return parent::getPaymentMethods($quote);
        } else {
            return $this->___callPlugins('getPaymentMethods', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalsArray(int $quoteId) : array
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalsArray');
        if (!$pluginInfo) {
            return parent::getTotalsArray($quoteId);
        } else {
            return $this->___callPlugins('getTotalsArray', func_get_args(), $pluginInfo);
        }
    }
}
