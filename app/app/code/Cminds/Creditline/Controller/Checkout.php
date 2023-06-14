<?php


namespace Cminds\Creditline\Controller;

use Magento\Checkout\Model\Cart as CustomerCart;
use Cminds\Creditline\Model\Config;
use Magento\Checkout\Controller\Cart;
use Cminds\Creditline\Helper\Data;
use Magento\Checkout\Model\CartFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Data\Form\FormKey\Validator;

abstract class Checkout extends Cart
{
    /**
     * @var CartFactory
     */
    protected $cartFactory;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @param Data                   $creditHelper
     * @param CartFactory            $cartFactory
     * @param Session                $customerSession
     * @param Context                $context
     * @param ScopeConfigInterface   $scopeConfig
     * @param Session                $checkoutSession
     * @param StoreManagerInterface  $storeManager
     * @param Validator              $formKeyValidator
     * @param CustomerCart           $cart
     * @codeCoverageIgnore
     */
    public function __construct(
        Data $creditHelper,
        CartFactory $cartFactory,
        Session $customerSession,
        Context $context,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        CustomerCart $cart
    ) {
        $this->creditHelper = $creditHelper;
        $this->cartFactory = $cartFactory;
        $this->customerSession = $customerSession;
        $this->resultFactory = $context->getResultFactory();

        parent::__construct($context, $scopeConfig, $checkoutSession, $storeManager, $formKeyValidator, $cart);
    }

    /**
     * @return Session
     */
    protected function _getSession()
    {
        return $this->customerSession;
    }

    /**
     * @return void
     */
    protected function _processPost()
    {
        $isUseCredit = true;

        if ($this->getRequest()->getParam('remove-credit') == 1) {
            $isUseCredit = false;
        }
        $creditAmount = 0;
        if (!empty($this->getRequest()->getParam('credit-amount'))) {
            $creditAmount = (float)$this->getRequest()->getParam('credit-amount');

            $balanceAmount = $this->creditHelper->getBalance()->getAmount();
            if ($creditAmount > $balanceAmount) {
                $creditAmount = $balanceAmount;
            }
        }

        $quote = $this->cartFactory->create()->getQuote();
        $quote->setUseCredit($isUseCredit ? Config::USE_CREDIT_YES : Config::USE_CREDIT_NO)
            ->setBaseCreditlineAmountUsed(0)
            ->setCreditlineAmountUsed(0)
            ->setManualUsedCredit($creditAmount)
            ->collectTotals()
            ->save();
    }
}
