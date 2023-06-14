<?php
namespace Cminds\Creditline\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class PaymentReminder implements ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionHash()
    {
        return [
            1   => __('Once a month'),
            2   => __('Every X days'),
            3   => __('Manually'),
            4   => __('No need to repay')
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

        return $result;
    }
}
