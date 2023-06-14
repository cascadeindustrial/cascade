<?php
/**
 * Copyright (c) 2019.
 * Copyright Holder : CompactCode - CompactCode BvBa - Belgium
 * Copyright : Unless granted permission from CompactCode BvBa.
 * You can not distribute, reuse, edit, resell or sell this.
 */

namespace CompactCode\MessagePopUp\Model\Config\Source;

use \Magento\Framework\Option\ArrayInterface;

class MessageAnimationOut implements ArrayInterface
{
    public function toOptionArray()
    {
        $availableOptions = [
            'bounceOut' => 'Bounce Out',
            'bounceOutDown' => 'Bounce Out Down',
            'bounceOutLeft' => 'Bounce Out Left',
            'bounceOutRight' => 'Bounce Out Right',
            'bounceOutUp' => 'Bounce Out Up',
            'fadeOut' => 'Fade Out',
            'fadeOutDown' => 'Fade Out Down',
            'fadeOutDownBig' => 'Fade Out Down Big',
            'fadeOutLeft' => 'Fade Out Left',
            'fadeOutLeftBig' => 'Fade Out Left Big',
            'fadeOutUp' => 'Fade Out Up',
            'fadeOutUpBig' => 'Fade Out Up Big',
            'zoomOut' => 'Zoom Out',
            'zoomOutDown' => 'Zoom Out Down',
            'zoomOutLeft' => 'Zoom Out Left',
            'zoomOutRight' => 'Zoom Out Right',
            'zoomOutUp' => 'Zoom Out Up',

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
