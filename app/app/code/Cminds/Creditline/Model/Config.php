<?php


namespace Cminds\Creditline\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Model\Session;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Config
{
    const REFILL_PRODUCT_SKU = 'creditline';

    const USE_CREDIT_UNDEFINED = 0;
    const USE_CREDIT_NO = 1;
    const USE_CREDIT_YES = 2;
    //const FREE_METHOD = 'free';
    const FREE_METHOD = 'quotation_quote';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var Product
     */
    protected $refillProduct;

    /**
     * @var Session
     */
    protected $customerSession;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ProductFactory $productFactory,
        Session $customerSession
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->productFactory = $productFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getShareBalance($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'creditline/general/share_balance',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isAutoRefundEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'creditline/general/auto_refund_enabled',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isAutoApplyEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'creditline/general/auto_apply_enabled',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getEmailBalanceUpdateTemplate($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'creditline/email/balance_update_template',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isSendBalanceUpdate($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'creditline/email/send_balance_update',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getEmailSender($storeId = null)
    {
        $senderIdentity = $this->getEmailSenderIdentity($storeId);

        $configPath = 'trans_email/ident_' . $senderIdentity . '/email';

        return $this->scopeConfig->getValue($configPath, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getEmailSenderIdentity($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'creditline/email/email_identity',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isEnableSendFriend($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'creditline/general/enable_send_friend',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return Product|false
     */
    public function getRefillProduct()
    {
        if ($this->refillProduct === null) {
            $this->refillProduct = false;

            $product = $this->productFactory->create();
            if ($id = $product->getIdBySku(self::REFILL_PRODUCT_SKU)) {
                $product = $product->load($id);
                if ($product->isAvailable()) {
                    $this->refillProduct = $product;
                }
            }
        }

        return $this->refillProduct;
    }

    /**
     * IsEnabled
     * @return boolean
     */
    public function isEnabled(){
        return $this->scopeConfig->getValue('creditline/general/creditline_active',ScopeInterface::SCOPE_STORE);
    }

    /**
     * isExistGroup
     * @return boolean
     */
    public function isExistGroup(){
        $groupid = $this->getGroupId();
        $group =  $this->scopeConfig->getValue('creditline/general/creditline_customer',ScopeInterface::SCOPE_STORE);
        $group = explode(',', $group);
        if(in_array($groupid, $group)){
            return true;
        }
        return false;
    }

    public function getGroupId(){
        $groupid = 0;
        if($this->customerSession->isLoggedIn()){
            $groupid = $this->customerSession->getCustomer()->getGroupId();
        }
        return $groupid;
    }
}
