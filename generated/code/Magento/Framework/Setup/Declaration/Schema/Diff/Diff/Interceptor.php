<?php
namespace Magento\Framework\Setup\Declaration\Schema\Diff\Diff;

/**
 * Interceptor class for @see \Magento\Framework\Setup\Declaration\Schema\Diff\Diff
 */
class Interceptor extends \Magento\Framework\Setup\Declaration\Schema\Diff\Diff implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Component\ComponentRegistrar $componentRegistrar, \Magento\Framework\Setup\Declaration\Schema\ElementHistoryFactory $elementHistoryFactory, array $tableIndexes, array $destructiveOperations)
    {
        $this->___init();
        parent::__construct($componentRegistrar, $elementHistoryFactory, $tableIndexes, $destructiveOperations);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAll');
        if (!$pluginInfo) {
            return parent::getAll();
        } else {
            return $this->___callPlugins('getAll', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getChange($table, $operation)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getChange');
        if (!$pluginInfo) {
            return parent::getChange($table, $operation);
        } else {
            return $this->___callPlugins('getChange', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canBeRegistered(\Magento\Framework\Setup\Declaration\Schema\Dto\ElementInterface $object, $operation) : bool
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canBeRegistered');
        if (!$pluginInfo) {
            return parent::canBeRegistered($object, $operation);
        } else {
            return $this->___callPlugins('canBeRegistered', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register(\Magento\Framework\Setup\Declaration\Schema\Dto\ElementInterface $dtoObject, $operation, ?\Magento\Framework\Setup\Declaration\Schema\Dto\ElementInterface $oldDtoObject = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'register');
        if (!$pluginInfo) {
            return parent::register($dtoObject, $operation, $oldDtoObject);
        } else {
            return $this->___callPlugins('register', func_get_args(), $pluginInfo);
        }
    }
}
