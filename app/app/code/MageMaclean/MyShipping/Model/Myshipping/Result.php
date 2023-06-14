<?php
namespace MageMaclean\MyShipping\Model\Myshipping;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Carrier;

use MageMaclean\MyShipping\Model\Repository\CourierRepository;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use MageMaclean\MyShipping\Api\Data\MyshippingResultInterface;
use MageMaclean\MyShipping\Api\Data\CourierInterface;

class Result extends \Magento\Framework\Model\AbstractExtensibleModel implements MyshippingResultInterface
{
    protected $_helper;
    protected $_courierRepo;
    protected $_accountRepo;
    protected $_itemPriceCalculator;

    protected $_account;
    protected $_courier;

    /**
     * Result constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param Helper $helper
     * @param CourierRepository $courierRepo
     * @param AccountRepository $accountRepo
     * @param ItemPriceCalculator $itemPriceCalculator
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        Helper $helper,
        CourierRepository $courierRepo,
        AccountRepository $accountRepo,
        ItemPriceCalculator $itemPriceCalculator,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_courierRepo = $courierRepo;
        $this->_accountRepo = $accountRepo;
        $this->_itemPriceCalculator = $itemPriceCalculator;

        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $resource, $resourceCollection, $data);
    }
    /**
     * @inheritDoc
     */
    public function getShippingPrice() {
        if(
            ($this->getMyshippingCourierId() || $this->getMyshippingAccountId()) &&
            $this->getMyshippingCourierMethod() &&
            sizeof($this->getItems())
        ) {
            return $this->_itemPriceCalculator->getShippingPrice($this->getItems(), $this);
        }

        return 0;
    }

    /**
     * @inheritDoc
     */
    public function setItems(array $items)
    {
        $this->setData('items', $items);
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        return (array) $this->getData('items');
    }

    /**
     * @inheritDoc
     */
    public function setMyshippingAccountId($value) {
        $this->setData('myshipping_account_id', $value);
    }

    /**
     * @inheritDoc
     */
    public function getMyshippingAccountId() : int
    {
        return (int) $this->getData('myshipping_account_id');
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
    public function getMyshippingAccount() : ?string
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
    public function getMyshippingSave() : bool
    {
        return (boolean)$this->getData('myshipping_save');
    }

    /**
     * @inheritDoc
     */
    public function isNew() {
        return $this->getMyshippingAccountId() > 0 ? false : true;
    }

    /**
     * @inheritDoc
     */
    public function isAccount() {
        return $this->getMyshippingAccountId() > 0 ? true : false;
    }

    /**
     * @inheritDoc
     */
    public function getAccount() {
        if(is_null($this->_account)) {
            $this->_account = $this->isAccount() && $this->getMyshippingAccountId() ? $this->_accountRepo->getById($this->getMyshippingAccountId()) : false;
        }
        return $this->_account;
    }


    /**
     * @return CourierInterface | bool
     */
    public function getCourier() {
        if(is_null($this->_courier)) {
            $this->_courier = ($this->getMyshippingCourierId()) ? $this->_courierRepo->get($this->getMyshippingCourierId()) : false;
        }
        return $this->_courier;
    }

    /**
     * @inheritDoc
     */
    public function getCode() {
        return $this->getCarrier() . '_' . $this->getMethod();
    }

    /**
     * @inheritDoc
     */
    public function getCarrier() {
        return Carrier::CODE;
    }

    /**
     * @inheritDoc
     */
    public function getMethod() {
        return ($this->isAccount()) ? 'account_' . $this->getMyshippingAccountId() : Carrier::CODE_METHOD_NEW;
    }

    /**
     * @inheritDoc
     */
    public function getCarrierTitle() {
        if($this->isNew()) {
            return $this->_helper->getConfigData("new_title");
        } else {
            $accountTitleMask = $this->_helper->getConfigData("account_title");
            return $this->_helper->getMaskString($accountTitleMask, $this->getCourier()->getTitle(), $this->getMyshippingAccount(), false);
        }
    }

    /**
     * @inheritDoc
     */
    public function getMethodTitle() {
        if($this->isNew()) {
            return $this->_helper->getConfigData("new_name");
        } else {
            $accountMethodMask = $this->_helper->getConfigData("account_name");
            return $this->_helper->getMaskString($accountMethodMask, $this->getCourier()->getTitle(), $this->getMyshippingAccount(), false);
        }
    }


    /**
     * @inheritDoc
     */
    public function getShippingDescription() {
        if(
            $this->getMyshippingCourierId() && $this->getMyshippingAccount() && $this->getMyshippingCourierMethod() 
        ) { 
            $shippingDescriptionMask = $this->_helper->getConfigData("format_shipping_description");
            $courierTitle = $this->getCourier()->getTitle();
            $methodTitle = $this->getCourier()->getMethodTitle($this->getMyshippingCourierMethod());
            $account = $this->getMyshippingAccount();

            return $this->_helper->getMaskString($shippingDescriptionMask, $courierTitle, $account, $methodTitle);
        } else {
            return $this->getCarrierTitle() . ' - ' . $this->getMethodTitle();
        }
    }

    /**
     * @inheritDoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritDoc
     */
    public function setExtensionAttributes(
        \MageMaclean\MyShipping\Api\Data\MyshippingResultExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
