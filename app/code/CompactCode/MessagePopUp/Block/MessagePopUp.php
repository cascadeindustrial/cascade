<?php
/**
 * Copyright (c) 2019.
 * Copyright Holder : CompactCode - CompactCode BvBa - Belgium
 * Copyright : Unless granted permission from CompactCode BvBa.
 * You can not distribute, reuse, edit, resell or sell this.
 */

namespace CompactCode\MessagePopUp\Block;

use CompactCode\MessagePopUp\Helper\Data as Helper;
use Magento\Framework\View\Element\Template;

class MessagePopUp extends Template
{

    /**
     * @var Helper
     */
    protected $helper;

    public function __construct(
        Template\Context $context,
        Helper $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * @return mixed
     */
    public function getModuleEnable()
    {
        return $this->helper->getModuleEnable();
    }

    /**
     * @return bool
     */
    public function getFixedEnabled()
    {
        return (bool)$this->helper->getFixedEnabled();
    }

    /**
     * @return int|mixed
     */
    public function getFixedBelow()
    {
        return $this->helper->getFixedBelow();
    }

    /**
     * @return int|mixed|string
     */
    public function getFixedClasses()
    {
        $classes = $this->helper->getFixedClasses();

        if (isset($classes) && !empty($classes)) {
            return $classes;
        }

        return "";
    }

    /**
     * @return mixed
     */
    public function getMessageClass()
    {
        return $this->helper->getMessageClass();
    }

    /**
     * @return mixed
     */
    public function getMessagePosition()
    {
        return $this->helper->getMessagePosition();
    }

    /**
     * @return mixed
     */
    public function getMessageDeleteOption()
    {
        return $this->helper->getMessageDeleteOption();
    }

    /**
     * @return mixed
     */
    public function getMessageAnimationIn()
    {
        return $this->helper->getMessageAnimationIn();
    }

    /**
     * @return mixed
     */
    public function getMessageAnimationOut()
    {
        return $this->helper->getMessageAnimationOut();
    }

    public function getMessageAnimationTime()
    {
        return $this->helper->getMessageAnimationTime();
    }
}
