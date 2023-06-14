<?php
declare(strict_types=1);

namespace MageMaclean\MyShipping\Block\Account;

use MageMaclean\MyShipping\Model\Repository\AccountRepository;
use Magento\Framework\Exception\NoSuchEntityException;

class Grid extends \Magento\Framework\View\Element\Template
{
    private $currentCustomer;
    private $accountRepository;
    private $accountCollection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        AccountRepository $accountRepository,
        array $data = []
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->accountRepository = $accountRepository;

        parent::__construct($context, $data);
    }

    protected function _prepareLayout(): void
    {
        parent::_prepareLayout();
        $this->preparePager();
    }

    public function getAddAccountUrl(): string
    {
        return $this->getUrl('myshipping_account/account/new', ['_secure' => true]);
    }
    
    public function getDeleteUrl($accountId): string
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $key_form = $objectManager->get('Magento\Framework\Data\Form\FormKey');
        $formKey = $key_form->getFormKey();
        return $this->getUrl('myshipping_account/account/delete', ['_secure' => true, 'id' => $accountId, 'form_key' => $formKey]);
    }
    
    public function getAccountEditUrl($accountId): string
    {
        return $this->getUrl('myshipping_account/account/edit', ['_secure' => true, 'id' => $accountId]);
    }

    public function getCustomer(): \Magento\Customer\Api\Data\CustomerInterface
    {
        $customer = $this->getData('customer');
        if ($customer === null) {
            $customer = $this->currentCustomer->getCustomer();
            $this->setData('customer', $customer);
        }
        return $customer;
    }

    private function preparePager(): void
    {
        $accountCollection = $this->getAccountCollection();
        if (null !== $accountCollection) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'myshipping.accounts.pager'
            )->setCollection($accountCollection);
            $this->setChild('pager', $pager);
        }
    }

    public function getAccountCollection(): \MageMaclean\MyShipping\Model\ResourceModel\Account\Collection
    {
        if (null === $this->accountCollection) {
            if (null === $this->getCustomer()) {
                throw new NoSuchEntityException(__('Customer not logged in'));
            }

            $collection = $this->accountRepository->getListByCustomerId($this->getCustomer()->getId());
            $this->accountCollection = $collection;
        }
        return $this->accountCollection;
    }
}
