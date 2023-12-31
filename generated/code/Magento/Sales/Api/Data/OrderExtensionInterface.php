<?php
namespace Magento\Sales\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Sales\Api\Data\OrderInterface
 */
interface OrderExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\Sales\Api\Data\ShippingAssignmentInterface[]|null
     */
    public function getShippingAssignments();

    /**
     * @param \Magento\Sales\Api\Data\ShippingAssignmentInterface[] $shippingAssignments
     * @return $this
     */
    public function setShippingAssignments($shippingAssignments);

    /**
     * @return \Magento\Payment\Api\Data\PaymentAdditionalInfoInterface[]|null
     */
    public function getPaymentAdditionalInfo();

    /**
     * @param \Magento\Payment\Api\Data\PaymentAdditionalInfoInterface[] $paymentAdditionalInfo
     * @return $this
     */
    public function setPaymentAdditionalInfo($paymentAdditionalInfo);

    /**
     * @return \Magento\GiftMessage\Api\Data\MessageInterface|null
     */
    public function getGiftMessage();

    /**
     * @param \Magento\GiftMessage\Api\Data\MessageInterface $giftMessage
     * @return $this
     */
    public function setGiftMessage(\Magento\GiftMessage\Api\Data\MessageInterface $giftMessage);

    /**
     * @return \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface[]|null
     */
    public function getAppliedTaxes();

    /**
     * @param \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface[] $appliedTaxes
     * @return $this
     */
    public function setAppliedTaxes($appliedTaxes);

    /**
     * @return \Magento\Tax\Api\Data\OrderTaxDetailsItemInterface[]|null
     */
    public function getItemAppliedTaxes();

    /**
     * @param \Magento\Tax\Api\Data\OrderTaxDetailsItemInterface[] $itemAppliedTaxes
     * @return $this
     */
    public function setItemAppliedTaxes($itemAppliedTaxes);

    /**
     * @return boolean|null
     */
    public function getConvertingFromQuote();

    /**
     * @param boolean $convertingFromQuote
     * @return $this
     */
    public function setConvertingFromQuote($convertingFromQuote);

    /**
     * @return \Amazon\Payment\Api\Data\OrderLinkInterface|null
     */
    public function getAmazonOrderReferenceId();

    /**
     * @param \Amazon\Payment\Api\Data\OrderLinkInterface $amazonOrderReferenceId
     * @return $this
     */
    public function setAmazonOrderReferenceId(\Amazon\Payment\Api\Data\OrderLinkInterface $amazonOrderReferenceId);

    /**
     * @return string|null
     */
    public function getQuoteationIncrementId();

    /**
     * @param string $quoteationIncrementId
     * @return $this
     */
    public function setQuoteationIncrementId($quoteationIncrementId);

    /**
     * @return string|null
     */
    public function getQuoteationBackendUrl();

    /**
     * @param string $quoteationBackendUrl
     * @return $this
     */
    public function setQuoteationBackendUrl($quoteationBackendUrl);

    /**
     * @return int|null
     */
    public function getMyshippingAccountId();

    /**
     * @param int $myshippingAccountId
     * @return $this
     */
    public function setMyshippingAccountId($myshippingAccountId);

    /**
     * @return int|null
     */
    public function getMyshippingCourierId();

    /**
     * @param int $myshippingCourierId
     * @return $this
     */
    public function setMyshippingCourierId($myshippingCourierId);

    /**
     * @return string|null
     */
    public function getMyshippingCourierMethod();

    /**
     * @param string $myshippingCourierMethod
     * @return $this
     */
    public function setMyshippingCourierMethod($myshippingCourierMethod);

    /**
     * @return string|null
     */
    public function getMyshippingAccount();

    /**
     * @param string $myshippingAccount
     * @return $this
     */
    public function setMyshippingAccount($myshippingAccount);

    /**
     * @return bool|null
     */
    public function getMyshippingSave();

    /**
     * @param bool $myshippingSave
     * @return $this
     */
    public function setMyshippingSave($myshippingSave);

    /**
     * @return string|null
     */
    public function getTjTaxCalculationStatus();

    /**
     * @param string $tjTaxCalculationStatus
     * @return $this
     */
    public function setTjTaxCalculationStatus($tjTaxCalculationStatus);

    /**
     * @return string|null
     */
    public function getTjTaxCalculationMessage();

    /**
     * @param string $tjTaxCalculationMessage
     * @return $this
     */
    public function setTjTaxCalculationMessage($tjTaxCalculationMessage);
}
