<?php
namespace MageMaclean\MyShipping\Block\Account;

use MageMaclean\MyShipping\Block\Account\Grid as AccountsGrid;
use MageMaclean\MyShipping\Model\Repository\AccountRepository;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $currentCustomer;
    protected $customerRepository;

    protected $accountRepository;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository = null,
        AccountRepository $accountRepository,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        array $data = [],
        AccountsGrid $accountsGrid = null
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->accountRepository = $accountRepository;
        $this->accountsGrid = $accountsGrid ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(AccountsGrid::class);
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Stored Shipping Accounts'));
        return parent::_prepareLayout();
    }

    public function getAddAccountUrl()
    {
        return $this->accountsGrid->getAddAccountUrl();
    }

    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('customer/account/', ['_secure' => true]);
    }

    public function getDeleteUrl()
    {
        return $this->accountsGrid->getDeleteUrl();
    }

    public function getAccountEditUrl($accountId)
    {
        return $this->accountsGrid->getAccountEditUrl($accountId);
    }

    public function getCustomer()
    {
        $customer = null;
        try {
            $customer = $this->currentCustomer->getCustomer();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
        }
        return $customer;
    }
}
