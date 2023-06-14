<?php


namespace Cminds\Creditline\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Cminds\Creditline\Model\Transaction;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Action implements ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionHash()
    {
        return [
            Transaction::ACTION_MANUAL    => __('Manual'),
            Transaction::ACTION_REFUNDED  => __('Refunded'),
            Transaction::ACTION_USED      => __('Used'),
            Transaction::ACTION_REFILL    => __('Refill'),
            Transaction::ACTION_EARNING   => __('Earning'),
            Transaction::ACTION_PURCHASED => __('Purchased'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $result = [];
        foreach ($this->toOptionHash() as $value => $label) {
            $result[] = [
                'label' => $label,
                'value' => $value,
            ];
        }
    }
}
