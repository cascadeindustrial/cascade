<?php
/**
 * Copyright (c) 2019.
 * Copyright Holder : CompactCode - CompactCode BvBa - Belgium
 * Copyright : Unless granted permission from CompactCode BvBa.
 * You can not distribute, reuse, edit, resell or sell this.
 */

namespace CompactCode\MessagePopUp\Model\Config\Source;

use \Magento\Framework\Option\ArrayInterface;

class MessageAnimationIn implements ArrayInterface
{
    public function toOptionArray()
    {
        $availableOptions = [
            'bounceIn' => 'Bounce In',
            'bounceInDown' => 'Bounce In Down',
            'bounceInLeft' => 'Bounce In Left',
            'bounceInRight' => 'Bounce In Right',
            'bounceInUp' => 'Bounce In Up',
            'fadeIn' => 'Fade In',
            'fadeInDown' => 'Fade In Down',
            'fadeInDownBig' => 'Fade In Down Big',
            'fadeInLeft' => 'Fade In Left',
            'fadeInLeftBig' => 'Fade In Left Big',
            'fadeInRight' => 'Fade In Right',
            'fadeInRightBig' => 'Fade In Right Big',
            'fadeInUp' => 'Fade In Up',
            'fadeInUpBig' => 'Fade In Up Big',
            'zoomIn' => 'Zoom In',
            'zoomInDown' => 'Zoom In Down',
            'zoomInLeft' => 'Zoom In Left',
            'zoomInRight' => 'Zoom In Right',
            'zoomInUp' => 'Zoom In Up',

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
