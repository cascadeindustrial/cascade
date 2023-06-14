<?php


namespace Cminds\Creditline\Block\Checkout\Cart;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Cminds\Creditline\Helper\Data as CreditHelper;
use Cminds\Creditline\Helper\Calculation;
use Cminds\Creditline\Model\Config;
use Magento\Checkout\Model\Cart;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Credit extends Template
{

    /**
     * @var int
     */
    protected $isPaypal;

    /**
     * @var CreditHelper
     */
    protected $creditHelper;

    /**
     * @var Config
     */
    private $config;

    protected $cartData;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param Calculation            $calculation
     * @param CreditHelper           $creditHelper
     * @param Context                $context
     * @param array                  $data
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Calculation $calculation,
        CreditHelper $creditHelper,
        Context $context,
        Config $config,
        Cart $cartData,
        array $data = []
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->calculation   = $calculation;
        $this->creditHelper  = $creditHelper;
        $this->config        = $config;
        $this->cartData        = $cartData;
        $this->isPaypal      = isset($data['isPaypal']) ? $data['isPaypal'] : false;

        parent::__construct($context);
    }


    /**
     * @return Balance
     */
    public function getBalance()
    {
        return $this->creditHelper->getBalance();
    }

    /**
     * @return float
     */
    public function getAmountToUse()
    {
        $quote = $this->creditHelper->getQuote();

        $toUse  = $quote->getGrandTotal();
        $totals = $quote->getTotals();

        $tax      = isset($totals['tax']) ? $totals['tax']->getValue() : 0;
        $shipping = isset($totals['shipping']) ? $totals['shipping']->getValue() : 0;

        $toUse = $this->calculation->calc($toUse, $tax, $shipping);

        if ($toUse > $this->getBalance()->getAmount()) {
            $toUse = $this->getBalance()->getAmount();
        }

        return $toUse;
    }

    /**
     * @return float
     */
    public function getUsedAmount()
    {
        return $this->creditHelper->getQuote()->getCreditlineAmountUsed();
    }

    /**
     * @param float $amount
     * @param bool  $includeContainer
     * @return string
     */
    public function getFormatedPrice($amount, $includeContainer)
    {
        return $this->priceCurrency->convertAndFormat($amount, $includeContainer);
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        if(!$this->config->isExistGroup()){
            return false;
        }

        if ($this->getBalance()->getAmount() > 0 && $this->creditHelper->isAllowedForQuote()) {
            return true;
        }

        $itemsCollection = $this->cartData->getQuote()->getItemsCollection();
        $itemsVisible = $this->cartData->getQuote()->getAllVisibleItems();
         $items = $this->cartData->getQuote()->getAllItems();
        $logFile='/var/log/iscreditline.log';
           $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
            // $logger = new \Zend\Log\Logger();
            // $logger->addWriter($writer);
            
        foreach($items as $item) {
          if($item->getSku() == 'creditline')
                {                   
                    return false;
                }
}
                return false;
    }

    /**
     * @return int
     */
    public function isPaypal()
    {
        return (int)$this->isPaypal;
    }
}
