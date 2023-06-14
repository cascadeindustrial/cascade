<?php


namespace Cminds\Creditline\Block\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Cminds\Creditline\Model\Config;
use Cminds\Creditline\Helper\Data;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Refill extends Template
{
    /**
     * @var Config
     */
    protected $config;

    protected $creditHelper;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @param Config  $config
     * @param Context $context
     */
    public function __construct(
        Data $creditHelper,
        CustomerFactory $customerFactory,
        Session $customerSession,
        Config $config,
        Context $context
    ) {
        $this->creditHelper = $creditHelper;
        $this->customerFactory = $customerFactory;
        $this->customerSession = $customerSession;
        $this->config = $config;

        parent::__construct($context);
    }

    /**
     * @return Customer
     */
    protected function getCustomer()
    {
        return $this->customerFactory->create()->load($this->customerSession->getCustomerId());
    }

    /**
     * @return Balance
     */
    public function getBalance()
    {
        return $this->creditHelper->getBalance($this->getCustomer()->getId());
    }

    /**
     * @return Product|false
     */
    public function getRefillProduct()
    {
        return $this->config->getRefillProduct();
    }

    /**
     * @return ProductCustomOptionInterface[]
     */
    public function getOptions()
    {
        $product = $this->getRefillProduct();

        return $product->getOptions();
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        if ($this->getRefillProduct()) {
            return true;
        }

        return false;
    }
}