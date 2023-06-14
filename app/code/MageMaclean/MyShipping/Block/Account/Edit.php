<?php
namespace MageMaclean\MyShipping\Block\Account;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

use MageMaclean\MyShipping\Helper\Data as Helper;
use MageMaclean\MyShipping\Model\Repository\CourierListRepository;
use MageMaclean\MyShipping\Model\AccountFactory;

class Edit extends \Magento\Framework\View\Element\Template
{
    protected $_customerSession;
    protected $currentCustomer;
    protected $dataObjectHelper;

    protected $_helper;
    protected $_courierListRepository;
    protected $_accountFactory;
    protected $_account = null;

    protected $_courierCollection;
    protected $_courierOptions;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        Helper $helper,
        CourierListRepository $courierListRepository,
        AccountFactory $accountFactory,
        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->currentCustomer = $currentCustomer;
        $this->dataObjectHelper = $dataObjectHelper;

        $this->_helper = $helper;
        $this->_courierListRepository = $courierListRepository;
        $this->_accountFactory = $accountFactory;

        parent::__construct(
            $context,
            $data
        );
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->initAccountObject();

        $this->pageConfig->getTitle()->set($this->getTitle());

        if ($postedData = $this->_customerSession->getMyshippingAccountFormData(true)) {
            $this->dataObjectHelper->populateWithArray(
                $this->_account,
                $postedData,
                \MageMaclean\MyShipping\Api\Data\AccountInterface::class
            );
        }
        return $this;
    }

    private function initAccountObject()
    {
        // Init address object
        if ($accountId = $this->getRequest()->getParam('id')) {
            try {
                $this->_account = $this->_accountFactory->create()->load($accountId);
                if ($this->_account->getCustomerId() != $this->_customerSession->getCustomerId()) {
                    $this->_account = null;
                }
            } catch (NoSuchEntityException $e) {
                $this->_account = null;
            }
        }

        if ($this->_account === null || !$this->_account->getId()) {
            $this->_account = $this->_accountFactory->create();
            $this->_account->setCustomerId($this->getCustomer()->getId());
        }
    }

    public function getTitle()
    {
        if ($this->getAccount()->getId()) {
            $title = __('Edit Shipping Account');
        } else {
            $title = __('Add New Shipping Account');
        }
        return $title;
    }

    public function getBackUrl()
    {
        if ($this->getData('back_url')) {
            return $this->getData('back_url');
        }

        return $this->getUrl('myshipping/account/');
    }

    public function getSaveUrl()
    {
        return $this->_urlBuilder->getUrl(
            'myshipping_account/account/formPost',
            ['_secure' => true, 'id' => $this->getAccount()->getId()]
        );
    }

    public function getAccount()
    {
        return $this->_account;
    }

    public function getCustomer()
    {
        return $this->currentCustomer->getCustomer();
    }

    public function getBackButtonUrl()
    {
        return $this->getUrl('myshipping/account');
    }

    public function getCourierCollection() {
        if(is_null($this->_courierCollection)) {
            $this->_courierCollection = $this->_courierListRepository->getStoreList($this->_customerSession->getStoreId());
        }
        return $this->_courierCollection;
    }

    public function getCourierOptions() {
        if(is_null($this->_courierOptions)) {
            $options = array();

            $courierCollection = $this->getCourierCollection();
            if($courierCollection) {
                foreach($courierCollection as $courier) {
                    $options[] = array(
                        'value' => $courier->getId(),
                        'label' => $courier->getTitle()
                    );
                }
            } else {
                $options[] = array(
                    'value' => 0,
                    'label' => 'No couriers have been configured.'
                );
            }
            $this->_courierOptions = $options;
        }

        return $this->_courierOptions;
    }

    public function getAccountValidationClasses() {
        return $this->_helper->getAccountValidationClasses();
    }

    public function getConfig($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
