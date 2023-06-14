<?php


namespace Cminds\Creditline\Controller;

use Magento\Framework\App\Action\Action;
use Cminds\Creditline\Helper\Calculation;
use Cminds\Creditline\Model\BalanceFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Escaper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;

abstract class Account extends Action
{
    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @param BalanceFactory      $earningFactory
     * @param CustomerFactory    $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param Session            $customerSession
     * @param Escaper            $escaper
     * @param Calculation        $calculationHelper
     * @param Context      $context
     */
    public function __construct(
        BalanceFactory $earningFactory,
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        Session $customerSession,
        Escaper $escaper,
        Calculation $calculationHelper,
        Context $context
    ) {
        $this->balanceFactory    = $earningFactory;
        $this->customerFactory   = $customerFactory;
        $this->storeManager      = $storeManager;
        $this->customerSession   = $customerSession;
        $this->context           = $context;
        $this->escaper           = $escaper;
        $this->resultFactory     = $context->getResultFactory();
        $this->calculationHelper = $calculationHelper;

        parent::__construct($context);
    }

    /**
     * @return Session
     */
    protected function _getSession()
    {
        return $this->customerSession;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $action = $this->getRequest()->getActionName();

        if ($action != 'external' && $action != 'postexternal') {
            if (!$this->customerSession->authenticate()) {
                $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            }
        }

        return parent::dispatch($request);
    }
}
