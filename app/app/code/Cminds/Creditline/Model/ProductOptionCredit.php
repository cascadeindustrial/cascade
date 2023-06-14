<?php

namespace Cminds\Creditline\Model;

use Magento\Framework\Model\AbstractModel;
use Cminds\Creditline\Api\Data\ProductOptionCreditInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class ProductOptionCredit extends AbstractModel implements ProductOptionCreditInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Cminds\Creditline\Model\ResourceModel\ProductOptionCredit');
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionProductId()
    {
        return $this->getData(self::KEY_OPTION_PRODUCT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionProductId($productId)
    {
        return $this->setData(self::KEY_OPTION_PRODUCT_ID, $productId);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->getData(self::KEY_STORE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::KEY_STORE_ID, $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionPriceType()
    {
        return $this->getData(self::KEY_OPTION_PRICE_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionPriceType($optionType)
    {
        return $this->setData(self::KEY_OPTION_PRICE_TYPE, $optionType);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionPriceOptions()
    {
        return $this->getData(self::KEY_OPTION_PRICE_OPTIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionPriceOptions($priceOptions)
    {
        return $this->setData(self::KEY_OPTION_PRICE_OPTIONS, $priceOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionPrice()
    {
        return $this->getData(self::KEY_OPTION_PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionPrice($price)
    {
        return $this->setData(self::KEY_OPTION_PRICE, $price);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionCredits()
    {
        return $this->getData(self::KEY_OPTION_CREDITS);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionCredits($credits)
    {
        return $this->setData(self::KEY_OPTION_CREDITS, $credits);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionMinCredits()
    {
        return $this->getData(self::KEY_OPTION_MIN_CREDITS);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionMinCredits($credits)
    {
        return $this->setData(self::KEY_OPTION_MIN_CREDITS, (int)$credits);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionMaxCredits()
    {
        return $this->getData(self::KEY_OPTION_MAX_CREDITS);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionMaxCredits($credits)
    {
        return $this->setData(self::KEY_OPTION_MAX_CREDITS, (int)$credits);
    }
}