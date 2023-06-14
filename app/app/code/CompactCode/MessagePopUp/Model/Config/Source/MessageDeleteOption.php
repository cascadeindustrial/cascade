<?php
/**
 * Copyright (c) 2019.
 * Copyright Holder : CompactCode - CompactCode BvBa - Belgium
 * Copyright : Unless granted permission from CompactCode BvBa.
 * You can not distribute, reuse, edit, resell or sell this.
 */

namespace CompactCode\MessagePopUp\Model\Config\Source;

class MessageDeleteOption
{
    public function toOptionArray()
    {
        $availableOptions = [
            '0' => 'Automatically',
            '1' => 'Manual',
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
