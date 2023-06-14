<?php


namespace Cminds\Creditline\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Stdlib\DateTime;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Transaction extends AbstractDb
{

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('cminds_creditline_transaction', 'transaction_id');
    }

    /**
     * @param AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$object->getId()) {
            $object->setCreatedAt((new \DateTime())->format(DateTime::DATETIME_PHP_FORMAT));
        }

        $object->setUpdatedAt((new \DateTime())->format(DateTime::DATETIME_PHP_FORMAT));

        return parent::_beforeSave($object);
    }
}
