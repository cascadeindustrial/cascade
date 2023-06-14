<?php
/**
 * Copyright (c) 2019.
 * Copyright Holder : CompactCode - CompactCode BvBa - Belgium
 * Copyright : Unless granted permission from CompactCode BvBa.
 * You can not distribute, reuse, edit, resell or sell this.
 */

namespace CompactCode\MessagePopUp\Helper;

use Magento\Store\Model\ScopeInterface;
use CompactCode\Base\Helper\Data as BaseHelper;

class Data extends BaseHelper
{

    const GENERAL = 'general';
    const SECTION = "ccmessagepopup";
    const SETTINGS = "messagepopup";
    const ANIMATION = "messageanimation";

    public function getModuleEnable()
    {
        $field = 'enable';
        $group = self::GENERAL;
        $path = $this->getPath(self::SECTION, $group, $field);
        return $this->getConfig($path, ScopeInterface::SCOPE_WEBSITE);
    }

    public function getFixedEnabled()
    {
        $field = 'fixed_enable';
        $group = self::GENERAL;
        $path = $this->getPath(self::SECTION, $group, $field);
        return $this->getConfig($path, ScopeInterface::SCOPE_WEBSITE);
    }

    public function getFixedBelow()
    {
        $field = 'fixed_below';
        $group = self::GENERAL;
        $path = $this->getPath(self::SECTION, $group, $field);
        $value = $this->getConfig($path, ScopeInterface::SCOPE_WEBSITE);
        if (!isset($value) || empty($value)) {
            $value = 0;
        }
        return $value;
    }

    public function getFixedClasses()
    {
        $field = 'fixed_classes';
        $group = self::GENERAL;
        $path = $this->getPath(self::SECTION, $group, $field);
        $value = $this->getConfig($path, ScopeInterface::SCOPE_WEBSITE);
        if (!isset($value) || empty($value)) {
            $value = 0;
        }
        return $value;
    }

    public function getMessageClass()
    {
        $field = 'message_class';
        $group = self::SETTINGS;
        $section = self::SECTION;

        $path = $this->getPath($section, $group, $field);
        return $this->getConfig($path, ScopeInterface::SCOPE_WEBSITES);
    }

    public function getMessagePosition()
    {
        $field = 'message_position';
        $group = self::SETTINGS;
        $section = self::SECTION;

        $path = $this->getPath($section, $group, $field);
        return $this->getConfig($path, ScopeInterface::SCOPE_WEBSITES);
    }

    public function getMessageDeleteOption()
    {
        $field = 'message_delete_option';
        $group = self::ANIMATION;
        $section = self::SECTION;

        $path = $this->getPath($section, $group, $field);
        return $this->getConfig($path, ScopeInterface::SCOPE_WEBSITES);
    }

    public function getMessageAnimationIn()
    {
        $field = 'message_animation_in';
        $group = self::ANIMATION;
        $section = self::SECTION;

        $path = $this->getPath($section, $group, $field);
        return $this->getConfig($path, ScopeInterface::SCOPE_WEBSITES);
    }

    public function getMessageAnimationOut()
    {
        $field = 'message_animation_out';
        $group = self::ANIMATION;
        $section = self::SECTION;

        $path = $this->getPath($section, $group, $field);
        return $this->getConfig($path, ScopeInterface::SCOPE_WEBSITES);
    }

    public function getMessageAnimationTime()
    {
        $field = 'message_animation_time';
        $group = self::ANIMATION;
        $section = self::SECTION;

        $path = $this->getPath($section, $group, $field);
        return $this->getConfig($path, ScopeInterface::SCOPE_WEBSITES);
    }
}
