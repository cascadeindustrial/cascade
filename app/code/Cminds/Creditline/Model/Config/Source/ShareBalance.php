<?php


namespace Cminds\Creditline\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Cminds\Creditline\Model\Balance;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class ShareBalance implements ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionHash()
    {
        return [
            Balance::SHARE_BALANCE_GLOBAL   => __('Global'),
            Balance::SHARE_BALANCE_CURRENCY => __('Per Currency'),
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
