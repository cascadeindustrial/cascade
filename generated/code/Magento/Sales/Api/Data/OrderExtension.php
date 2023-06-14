<?php
namespace Magento\Sales\Api\Data;

/**
 * Extension class for @see \Magento\Sales\Api\Data\OrderInterface
 */
class OrderExtension extends \Magento\Framework\Api\AbstractSimpleObject implements OrderExtensionInterface
{
    /**
     * @return \Magento\Sales\Api\Data\ShippingAssignmentInterface[]|null
     */
    public function getShippingAssignments()
    {
        return $this->_get('shipping_assignments');
    }

    /**
     * @param \Magento\Sales\Api\Data\ShippingAssignmentInterface[] $shippingAssignments
     * @return $this
     */
    public function setShippingAssignments($shippingAssignments)
    {
        $this->setData('shipping_assignments', $shippingAssignments);
        return $this;
    }

    /**
     * @return \Magento\Payment\Api\Data\PaymentAdditionalInfoInterface[]|null
     */
    public function getPaymentAdditionalInfo()
    {
        return $this->_get('payment_additional_info');
    }

    /**
     * @param \Magento\Payment\Api\Data\PaymentAdditionalInfoInterface[] $paymentAdditionalInfo
     * @return $this
     */
    public function setPaymentAdditionalInfo($paymentAdditionalInfo)
    {
        $this->setData('payment_additional_info', $paymentAdditionalInfo);
        return $this;
    }

    /**
     * @return \Magento\GiftMessage\Api\Data\MessageInterface|null
     */
    public function getGiftMessage()
    {
        return $this->_get('gift_message');
    }

    /**
     * @param \Magento\GiftMessage\Api\Data\MessageInterface $giftMessage
     * @return $this
     */
    public function setGiftMessage(\Magento\GiftMessage\Api\Data\MessageInterface $giftMessage)
    {
        $this->setData('gift_message', $giftMessage);
        return $this;
    }

    /**
     * @return \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface[]|null
     */
    public function getAppliedTaxes()
    {
        return $this->_get('applied_taxes');
    }

    /**
     * @param \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface[] $appliedTaxes
     * @return $this
     */
    public function setAppliedTaxes($appliedTaxes)
    {
        $this->setData('applied_taxes', $appliedTaxes);
        return $this;
    }

    /**
     * @return \Magento\Tax\Api\Data\OrderTaxDetailsItemInterface[]|null
     */
    public function getItemAppliedTaxes()
    {
        return $this->_get('item_applied_taxes');
    }

    /**
     * @param \Magento\Tax\Api\Data\OrderTaxDetailsItemInterface[] $itemAppliedTaxes
     * @return $this
     */
    public function setItemAppliedTaxes($itemAppliedTaxes)
    {
        $this->setData('item_applied_taxes', $itemAppliedTaxes);
        return $this;
    }

    /**
     * @return boolean|null
     */
    public function getConvertingFromQuote()
    {
        return $this->_get('converting_from_quote');
    }

    /**
     * @param boolean $convertingFromQuote
     * @return $this
     */
    public function setConvertingFromQuote($convertingFromQuote)
    {
        $this->setData('converting_from_quote', $convertingFromQuote);
        return $this;
    }

    /**
     * @return \Amazon\Payment\Api\Data\OrderLinkInterface|null
     */
    public function getAmazonOrderReferenceId()
    {
        return $this->_get('amazon_order_reference_id');
    }

    /**
     * @param \Amazon\Payment\Api\Data\OrderLinkInterface $amazonOrderReferenceId
     * @return $this
     */
    public function setAmazonOrderReferenceId(\Amazon\Payment\Api\Data\OrderLinkInterface $amazonOrderReferenceId)
    {
        $this->setData('amazon_order_reference_id', $amazonOrderReferenceId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuoteationIncrementId()
    {
        return $this->_get('quoteation_increment_id');
    }

    /**
     * @param string $quoteationIncrementId
     * @return $this
     */
    public function setQuoteationIncrementId($quoteationIncrementId)
    {
        $this->setData('quoteation_increment_id', $quoteationIncrementId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuoteationBackendUrl()
    {
        return $this->_get('quoteation_backend_url');
    }

    /**
     * @param string $quoteationBackendUrl
     * @return $this
     */
    public function setQuoteationBackendUrl($quoteationBackendUrl)
    {
        $this->setData('quoteation_backend_url', $quoteationBackendUrl);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMyshippingAccountId()
    {
        return $this->_get('myshipping_account_id');
    }

    /**
     * @param int $myshippingAccountId
     * @return $this
     */
    public function setMyshippingAccountId($myshippingAccountId)
    {
        $this->setData('myshipping_account_id', $myshippingAccountId);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMyshippingCourierId()
    {
        return $this->_get('myshipping_courier_id');
    }

    /**
     * @param int $myshippingCourierId
     * @return $this
     */
    public function setMyshippingCourierId($myshippingCourierId)
    {
        $this->setData('myshipping_courier_id', $myshippingCourierId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMyshippingCourierMethod()
    {
        return $this->_get('myshipping_courier_method');
    }

    /**
     * @param string $myshippingCourierMethod
     * @return $this
     */
    public function setMyshippingCourierMethod($myshippingCourierMethod)
    {
        $this->setData('myshipping_courier_method', $myshippingCourierMethod);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMyshippingAccount()
    {
        return $this->_get('myshipping_account');
    }

    /**
     * @param string $myshippingAccount
     * @return $this
     */
    public function setMyshippingAccount($myshippingAccount)
    {
        $this->setData('myshipping_account', $myshippingAccount);
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getMyshippingSave()
    {
        return $this->_get('myshipping_save');
    }

    /**
     * @param bool $myshippingSave
     * @return $this
     */
    public function setMyshippingSave($myshippingSave)
    {
        $this->setData('myshipping_save', $myshippingSave);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTjTaxCalculationStatus()
    {
        return $this->_get('tj_tax_calculation_status');
    }

    /**
     * @param string $tjTaxCalculationStatus
     * @return $this
     */
    public function setTjTaxCalculationStatus($tjTaxCalculationStatus)
    {
        $this->setData('tj_tax_calculation_status', $tjTaxCalculationStatus);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTjTaxCalculationMessage()
    {
        return $this->_get('tj_tax_calculation_message');
    }

    /**
     * @param string $tjTaxCalculationMessage
     * @return $this
     */
    public function setTjTaxCalculationMessage($tjTaxCalculationMessage)
    {
        $this->setData('tj_tax_calculation_message', $tjTaxCalculationMessage);
        return $this;
    }
}
