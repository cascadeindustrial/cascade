<?php


namespace Cminds\Creditline\Model\ResourceModel\ProductOptionCredit;

use Cminds\Creditline\Api\Data\ProductOptionCreditInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\DB\Select;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Cminds\Creditline\Model\ProductOptionCredit',
            'Cminds\Creditline\Model\ResourceModel\ProductOptionCredit');
    }

    /**
     * @param int $typeId
     * @param int $storeId
     *
     * @return $this
     */
    public function addTypeFilter($typeId, $storeId)
    {
        $this->getSelect()
            ->where('option_type_id = ?', intval($typeId))
            ->where('store_id in (?)', [0, $storeId])
        ;

        return $this;
    }

    /**
     * @param int $productId
     *
     * @return $this
     */
    public function addProductFilter($productId)
    {
        $this->getSelect()
            ->where(ProductOptionCreditInterface::KEY_OPTION_PRODUCT_ID.' = ?', intval($productId))
        ;

        return $this;
    }

    /**
     * @param int $storeId
     *
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        $this->getSelect()
            ->where(ProductOptionCreditInterface::KEY_STORE_ID.' IN (?, 0)', intval($storeId))
        ;

        return $this;
    }
}