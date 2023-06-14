<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://magezon.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://magento.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_TabGrid
 * @copyright Copyright (C) 2017 Magezon (https://magezon.com)
 */

namespace Magezon\TabGrid\Block\Widget\Button;

class Item extends \Magento\Framework\DataObject
{
    /**
     * Object delete flag
     *
     * @var bool
     */
    protected $_isDeleted = false;

    /**
     * Set _isDeleted flag value (if $isDeleted parameter is defined) and return current flag value
     *
     * @param boolean $isDeleted
     * @return bool
     */
    public function isDeleted($isDeleted = null)
    {
        $result = $this->_isDeleted;
        if ($isDeleted !== null) {
            $this->_isDeleted = $isDeleted;
        }
        return $result;
    }
}
