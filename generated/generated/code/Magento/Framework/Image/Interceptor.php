<?php
namespace Magento\Framework\Image;

/**
 * Interceptor class for @see \Magento\Framework\Image
 */
class Interceptor extends \Magento\Framework\Image implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Image\Adapter\AdapterInterface $adapter, $fileName = null)
    {
        $this->___init();
        parent::__construct($adapter, $fileName);
    }

    /**
     * {@inheritdoc}
     */
    public function open()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'open');
        if (!$pluginInfo) {
            return parent::open();
        } else {
            return $this->___callPlugins('open', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'display');
        if (!$pluginInfo) {
            return parent::display();
        } else {
            return $this->___callPlugins('display', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save($destination = null, $newFileName = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($destination, $newFileName);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rotate($angle)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'rotate');
        if (!$pluginInfo) {
            return parent::rotate($angle);
        } else {
            return $this->___callPlugins('rotate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function crop($top = 0, $left = 0, $right = 0, $bottom = 0)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'crop');
        if (!$pluginInfo) {
            return parent::crop($top, $left, $right, $bottom);
        } else {
            return $this->___callPlugins('crop', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resize($width, $height = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resize');
        if (!$pluginInfo) {
            return parent::resize($width, $height);
        } else {
            return $this->___callPlugins('resize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function keepAspectRatio($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'keepAspectRatio');
        if (!$pluginInfo) {
            return parent::keepAspectRatio($value);
        } else {
            return $this->___callPlugins('keepAspectRatio', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function keepFrame($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'keepFrame');
        if (!$pluginInfo) {
            return parent::keepFrame($value);
        } else {
            return $this->___callPlugins('keepFrame', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function keepTransparency($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'keepTransparency');
        if (!$pluginInfo) {
            return parent::keepTransparency($value);
        } else {
            return $this->___callPlugins('keepTransparency', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function constrainOnly($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'constrainOnly');
        if (!$pluginInfo) {
            return parent::constrainOnly($value);
        } else {
            return $this->___callPlugins('constrainOnly', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function backgroundColor($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'backgroundColor');
        if (!$pluginInfo) {
            return parent::backgroundColor($value);
        } else {
            return $this->___callPlugins('backgroundColor', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function quality($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'quality');
        if (!$pluginInfo) {
            return parent::quality($value);
        } else {
            return $this->___callPlugins('quality', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function watermark($watermarkImage, $positionX = 0, $positionY = 0, $watermarkImageOpacity = 30, $repeat = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'watermark');
        if (!$pluginInfo) {
            return parent::watermark($watermarkImage, $positionX, $positionY, $watermarkImageOpacity, $repeat);
        } else {
            return $this->___callPlugins('watermark', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMimeType');
        if (!$pluginInfo) {
            return parent::getMimeType();
        } else {
            return $this->___callPlugins('getMimeType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getImageType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImageType');
        if (!$pluginInfo) {
            return parent::getImageType();
        } else {
            return $this->___callPlugins('getImageType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'process');
        if (!$pluginInfo) {
            return parent::process();
        } else {
            return $this->___callPlugins('process', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function instruction()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'instruction');
        if (!$pluginInfo) {
            return parent::instruction();
        } else {
            return $this->___callPlugins('instruction', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setImageBackgroundColor($color)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setImageBackgroundColor');
        if (!$pluginInfo) {
            return parent::setImageBackgroundColor($color);
        } else {
            return $this->___callPlugins('setImageBackgroundColor', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWatermarkPosition($position)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWatermarkPosition');
        if (!$pluginInfo) {
            return parent::setWatermarkPosition($position);
        } else {
            return $this->___callPlugins('setWatermarkPosition', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWatermarkImageOpacity($imageOpacity)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWatermarkImageOpacity');
        if (!$pluginInfo) {
            return parent::setWatermarkImageOpacity($imageOpacity);
        } else {
            return $this->___callPlugins('setWatermarkImageOpacity', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWatermarkWidth($width)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWatermarkWidth');
        if (!$pluginInfo) {
            return parent::setWatermarkWidth($width);
        } else {
            return $this->___callPlugins('setWatermarkWidth', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWatermarkHeight($height)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWatermarkHeight');
        if (!$pluginInfo) {
            return parent::setWatermarkHeight($height);
        } else {
            return $this->___callPlugins('setWatermarkHeight', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalWidth()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalWidth');
        if (!$pluginInfo) {
            return parent::getOriginalWidth();
        } else {
            return $this->___callPlugins('getOriginalWidth', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalHeight()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalHeight');
        if (!$pluginInfo) {
            return parent::getOriginalHeight();
        } else {
            return $this->___callPlugins('getOriginalHeight', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createPngFromString($text, $font = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createPngFromString');
        if (!$pluginInfo) {
            return parent::createPngFromString($text, $font);
        } else {
            return $this->___callPlugins('createPngFromString', func_get_args(), $pluginInfo);
        }
    }
}
