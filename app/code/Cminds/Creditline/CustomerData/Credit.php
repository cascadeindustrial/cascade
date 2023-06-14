<?php

namespace Cminds\Creditline\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Pricing\PriceCurrencyInterface as PricingHelper;
use Cminds\Creditline\Helper\Data;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Credit implements SectionSourceInterface
{
    /**
     * @var Data
     */
    protected $creditHelper;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var PricingHelper
     */
    protected $pricingHelper;

    /**
     * @param Data          $creditHelper
     * @param Session       $customerSession
     * @param PricingHelper $pricingHelper
     */
    public function __construct(
        Data $creditHelper,
        Session $customerSession,
        PricingHelper $pricingHelper
    ) {
        $this->creditHelper = $creditHelper;
        $this->customerSession = $customerSession;
        $this->pricingHelper = $pricingHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        return [
            'amount' => $this->pricingHelper->format($this->getBalance()->getAmount(), false),
        ];
    }

    /**
     * @return Balance
     */
    public function getBalance()
    {
        return $this->creditHelper->getBalance();
    }
}
