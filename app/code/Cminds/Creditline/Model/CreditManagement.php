<?php

namespace Cminds\Creditline\Model;

use Cminds\Creditline\Api\CreditManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CreditManagement implements CreditManagementInterface
{
    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($cartId, $creditAmount)
    {
        $quote = $this->cartRepository->get($cartId);
        $quote->setUseCredit(Config::USE_CREDIT_YES)
            ->setBaseCreditlineAmountUsed(0)
            ->setCreditlineAmountUsed(0)
            ->setManualUsedCredit($creditAmount)
            ->collectTotals()
            ->save();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function cancel($cartId, $creditAmount)
    {
        $quote = $this->cartRepository->get($cartId);
        $quote->setUseCredit(Config::USE_CREDIT_NO)
            ->setBaseCreditlineAmountUsed(0)
            ->setCreditlineAmountUsed(0)
            ->setManualUsedCredit($creditAmount)
            ->collectTotals()
            ->save();

        return true;
    }
}
