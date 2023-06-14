<?php
namespace MageMaclean\MyShipping\Model\Myshipping;

class Quote extends \Magento\Framework\Model\AbstractModel implements \MageMaclean\MyShipping\Api\Data\MyshippingQuoteInterface
{
    /**
     * Init resource class
     */
    protected function _construct()
    {
        $this->_init(\MageMaclean\MyShipping\Model\ResourceModel\Myshipping\Quote::class);
    }

    /**
     * @inheritDoc
     */
    public function getQuoteAddressId() : int
    {
        return (int)$this->getData('quote_address_id');
    }

    /**
     * @inheritDoc
     */
    public function setQuoteAddressId(int $quoteAddressId)
    {
        return $this->setData('quote_address_id', $quoteAddressId);
    }

    /**
     * @inheritDoc
     */
    public function getMyshippingAccountId() : int
    {
        return (int)$this->getData('myshipping_account_id');
    }

    /**
     * @inheritDoc
     */
    public function setMyshippingAccountId(int $myshippingAccountId)
    {
        return $this->setData('myshipping_account_id', $myshippingAccountId);
    }

    /**
     * @inheritDoc
     */
    public function setMyshippingCourierId(int $value) {
        $this->setData('myshipping_courier_id', $value);
    }

    /**
     * @inheritDoc
     */
    public function getMyshippingCourierId() : int
    {
        return (int) $this->getData('myshipping_courier_id');
    }

    /**
     * @inheritDoc
     */
    public function setMyshippingCourierMethod(string $value) {
        $this->setData('myshipping_courier_method', $value);
    }

    /**
     * @inheritDoc
     */
    public function getMyshippingCourierMethod() : string
    {
        return (string)$this->getData('myshipping_courier_method');
    }

    /**
     * @inheritDoc
     */
    public function setMyshippingAccount(string $value) {
        $this->setData('myshipping_account', $value);
    }

    /**
     * @inheritDoc
     */
    public function getMyshippingAccount()
    {
        return $this->getData('myshipping_account');
    }

    /**
     * @inheritDoc
     */
    public function setMyshippingSave(bool $value) {
        $this->setData('myshipping_save', $value);
    }

    /**
     * @inheritDoc
     */
    public function getMyshippingSave()
    {
        return $this->getData('myshipping_save');
    }
}
