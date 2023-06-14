<?php


namespace Cminds\Creditline\Model;

use Magento\Framework\Model\AbstractModel;
use Cminds\Creditline\Helper\Message as MessageHelper;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Transaction extends AbstractModel
{
    const ACTION_MANUAL    = 'manual';
    const ACTION_EARNING   = 'earning';
    const ACTION_REFUNDED  = 'refunded';
    const ACTION_USED      = 'used';
    const ACTION_REFILL    = 'refill';
    const ACTION_PURCHASED = 'purchased';

    /**
     * @var Balance
     */
    protected $balance;

    /**
     * @var BalanceFactory
     */
    protected $balanceFactory;

    /**
     * @var MessageHelper
     */
    protected $messageHelper;

    /**
     * @param BalanceFactory $balanceFactory
     * @param MessageHelper  $messageHelper
     * @param Context        $context
     * @param Registry       $registry
     */
    public function __construct(
        BalanceFactory $balanceFactory,
        MessageHelper $messageHelper,
        Context $context,
        Registry $registry
    ) {
        $this->balanceFactory = $balanceFactory;
        $this->messageHelper = $messageHelper;

        parent::__construct($context, $registry);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Cminds\Creditline\Model\ResourceModel\Transaction');
    }

    /**
     * @return Balance|false
     */
    public function getBalance()
    {
        if (!$this->getBalanceId()) {
            return false;
        }

        if ($this->balance === null) {
            $this->balance = $this->balanceFactory->create()->load($this->getBalanceId());
        }

        return $this->balance;
    }

    /**
     * @return string
     */
    public function getBackendMessage()
    {
        return $this->messageHelper->getBackendTransactionMessage($this->getMessage());
    }

    /**
     * @return string
     */
    public function getFrontendMessage()
    {
        return $this->messageHelper->getFrontendTransactionMessage($this->getMessage());
    }

    /**
     * @return string
     */
    public function getEmailMessage()
    {
        return $this->messageHelper->getEmailTransactionMessage($this->getMessage());
    }
}
