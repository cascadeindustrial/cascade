<?php
namespace Magento\Framework\View\Layout\ScheduledStructure;

/**
 * Interceptor class for @see \Magento\Framework\View\Layout\ScheduledStructure
 */
class Interceptor extends \Magento\Framework\View\Layout\ScheduledStructure implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(array $data = [])
    {
        $this->___init();
        parent::__construct($data);
    }

    /**
     * {@inheritdoc}
     */
    public function setElementToSortList($parentName, $elementName, $offsetOrSibling, $isAfter = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setElementToSortList');
        if (!$pluginInfo) {
            return parent::setElementToSortList($parentName, $elementName, $offsetOrSibling, $isAfter);
        } else {
            return $this->___callPlugins('setElementToSortList', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isListToSortEmpty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isListToSortEmpty');
        if (!$pluginInfo) {
            return parent::isListToSortEmpty();
        } else {
            return $this->___callPlugins('isListToSortEmpty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetElementToSort($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetElementToSort');
        if (!$pluginInfo) {
            return parent::unsetElementToSort($elementName);
        } else {
            return $this->___callPlugins('unsetElementToSort', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getElementToSort($elementName, array $default = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getElementToSort');
        if (!$pluginInfo) {
            return parent::getElementToSort($elementName, $default);
        } else {
            return $this->___callPlugins('getElementToSort', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getListToSort()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getListToSort');
        if (!$pluginInfo) {
            return parent::getListToSort();
        } else {
            return $this->___callPlugins('getListToSort', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getListToMove()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getListToMove');
        if (!$pluginInfo) {
            return parent::getListToMove();
        } else {
            return $this->___callPlugins('getListToMove', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getListToRemove()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getListToRemove');
        if (!$pluginInfo) {
            return parent::getListToRemove();
        } else {
            return $this->___callPlugins('getListToRemove', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getElements()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getElements');
        if (!$pluginInfo) {
            return parent::getElements();
        } else {
            return $this->___callPlugins('getElements', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getElement($elementName, $default = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getElement');
        if (!$pluginInfo) {
            return parent::getElement($elementName, $default);
        } else {
            return $this->___callPlugins('getElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isElementsEmpty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isElementsEmpty');
        if (!$pluginInfo) {
            return parent::isElementsEmpty();
        } else {
            return $this->___callPlugins('isElementsEmpty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setElement($elementName, array $data)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setElement');
        if (!$pluginInfo) {
            return parent::setElement($elementName, $data);
        } else {
            return $this->___callPlugins('setElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasElement($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasElement');
        if (!$pluginInfo) {
            return parent::hasElement($elementName);
        } else {
            return $this->___callPlugins('hasElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetElement($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetElement');
        if (!$pluginInfo) {
            return parent::unsetElement($elementName);
        } else {
            return $this->___callPlugins('unsetElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getElementToMove($elementName, $default = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getElementToMove');
        if (!$pluginInfo) {
            return parent::getElementToMove($elementName, $default);
        } else {
            return $this->___callPlugins('getElementToMove', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setElementToMove($elementName, array $data)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setElementToMove');
        if (!$pluginInfo) {
            return parent::setElementToMove($elementName, $data);
        } else {
            return $this->___callPlugins('setElementToMove', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetElementFromListToRemove($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetElementFromListToRemove');
        if (!$pluginInfo) {
            return parent::unsetElementFromListToRemove($elementName);
        } else {
            return $this->___callPlugins('unsetElementFromListToRemove', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setElementToRemoveList($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setElementToRemoveList');
        if (!$pluginInfo) {
            return parent::setElementToRemoveList($elementName);
        } else {
            return $this->___callPlugins('setElementToRemoveList', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStructure()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStructure');
        if (!$pluginInfo) {
            return parent::getStructure();
        } else {
            return $this->___callPlugins('getStructure', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStructureElement($elementName, $default = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStructureElement');
        if (!$pluginInfo) {
            return parent::getStructureElement($elementName, $default);
        } else {
            return $this->___callPlugins('getStructureElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isStructureEmpty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isStructureEmpty');
        if (!$pluginInfo) {
            return parent::isStructureEmpty();
        } else {
            return $this->___callPlugins('isStructureEmpty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasStructureElement($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasStructureElement');
        if (!$pluginInfo) {
            return parent::hasStructureElement($elementName);
        } else {
            return $this->___callPlugins('hasStructureElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStructureElement($elementName, array $data)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStructureElement');
        if (!$pluginInfo) {
            return parent::setStructureElement($elementName, $data);
        } else {
            return $this->___callPlugins('setStructureElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetStructureElement($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetStructureElement');
        if (!$pluginInfo) {
            return parent::unsetStructureElement($elementName);
        } else {
            return $this->___callPlugins('unsetStructureElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStructureElementData($elementName, $default = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStructureElementData');
        if (!$pluginInfo) {
            return parent::getStructureElementData($elementName, $default);
        } else {
            return $this->___callPlugins('getStructureElementData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStructureElementData($elementName, array $data)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStructureElementData');
        if (!$pluginInfo) {
            return parent::setStructureElementData($elementName, $data);
        } else {
            return $this->___callPlugins('setStructureElementData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPaths()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaths');
        if (!$pluginInfo) {
            return parent::getPaths();
        } else {
            return $this->___callPlugins('getPaths', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($elementName, $default = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPath');
        if (!$pluginInfo) {
            return parent::getPath($elementName, $default);
        } else {
            return $this->___callPlugins('getPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasPath($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasPath');
        if (!$pluginInfo) {
            return parent::hasPath($elementName);
        } else {
            return $this->___callPlugins('hasPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPathElement($elementName, $data)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPathElement');
        if (!$pluginInfo) {
            return parent::setPathElement($elementName, $data);
        } else {
            return $this->___callPlugins('setPathElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetPathElement($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetPathElement');
        if (!$pluginInfo) {
            return parent::unsetPathElement($elementName);
        } else {
            return $this->___callPlugins('unsetPathElement', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetElementFromBrokenParentList($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetElementFromBrokenParentList');
        if (!$pluginInfo) {
            return parent::unsetElementFromBrokenParentList($elementName);
        } else {
            return $this->___callPlugins('unsetElementFromBrokenParentList', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setElementToBrokenParentList($elementName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setElementToBrokenParentList');
        if (!$pluginInfo) {
            return parent::setElementToBrokenParentList($elementName);
        } else {
            return $this->___callPlugins('setElementToBrokenParentList', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flushPaths()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'flushPaths');
        if (!$pluginInfo) {
            return parent::flushPaths();
        } else {
            return $this->___callPlugins('flushPaths', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flushScheduledStructure()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'flushScheduledStructure');
        if (!$pluginInfo) {
            return parent::flushScheduledStructure();
        } else {
            return $this->___callPlugins('flushScheduledStructure', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toArray()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '__toArray');
        if (!$pluginInfo) {
            return parent::__toArray();
        } else {
            return $this->___callPlugins('__toArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function populateWithArray(array $data)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'populateWithArray');
        if (!$pluginInfo) {
            return parent::populateWithArray($data);
        } else {
            return $this->___callPlugins('populateWithArray', func_get_args(), $pluginInfo);
        }
    }
}
