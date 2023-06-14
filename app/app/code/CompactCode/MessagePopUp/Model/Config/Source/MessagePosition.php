<?php
/**
 * Copyright (c) 2019.
 * Copyright Holder : CompactCode - CompactCode BvBa - Belgium
 * Copyright : Unless granted permission from CompactCode BvBa.
 * You can not distribute, reuse, edit, resell or sell this.
 */

namespace CompactCode\MessagePopUp\Model\Config\Source;

use \Magento\Framework\Option\ArrayInterface;

class MessagePosition implements ArrayInterface
{

    protected $options;

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $availableOptions = [
            'cc-message-left-bottom-corner' => 'Left Bottom',
            'cc-message-left-top-corner' => 'Left Top',
            'cc-message-right-bottom-corner' => 'Right Bottom',
            'cc-message-right-top-corner' => 'Right Top'
        ];

        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
