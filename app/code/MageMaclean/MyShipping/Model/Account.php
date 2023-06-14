<?php
namespace MageMaclean\MyShipping\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;

use MageMaclean\MyShipping\Model\Repository\CourierRepository;
use MageMaclean\MyShipping\Model\ResourceModel\Account as ResourceModel;
use MageMaclean\MyShipping\Api\Data\AccountInterface;
use MageMaclean\MyShipping\Api\Data\CourierInterface;

class Account extends \Magento\Framework\Model\AbstractExtensibleModel implements AccountInterface
{
    protected $_courierRepository;

    /** 
    * @var CourierInterface $_courier;
    */
    protected $_courier;

    /**
     * Init resource class
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param CourierRepository $courierRepo
     * @param array $data
     */

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        CourierRepository $courierRepo,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_courierRepository = $courierRepo;

        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $resource, $resourceCollection);
    }

    /**
     * Return Shipping Method Code
     *
     * @return string
     */
    public function getCode() {
        return Carrier::CODE . '_' . $this->getMethodCode();
    }

    public function getMethodCode() {
        return 'account_' . $this->getId();
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId() : int
    {
        return (int)$this->getData('customer_id');
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(int $customerId)
    {
        return $this->setData('customer_id', $customerId);
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
    public function getPosition() : int
    {
        return (int)$this->getData('position');
    }

    /**
     * @inheritDoc
     */
    public function setPosition(int $position) {
        return $this->setData("position", $position);
    }

    /**
     * @return CourierInterface
     */
    public function getCourier() {
        if(is_null($this->_courier) && $this->getMyshippingCourierId()) {
            $this->_courier = $this->_courierRepository->get($this->getMyshippingCourierId());
        }

        return $this->_courier;
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
        \MageMaclean\MyShipping\Api\Data\AccountExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
