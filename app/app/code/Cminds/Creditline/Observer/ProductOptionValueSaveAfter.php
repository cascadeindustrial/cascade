<?php

namespace Cminds\Creditline\Observer;

use Cminds\Creditline\Model\Product\Type;
use Cminds\Creditline\Ui\DataProvider\Product\Form\Modifier\Composite;
use Magento\Framework\Event\ObserverInterface;
use Cminds\Creditline\Model\ProductOptionCreditFactory;
use Cminds\Creditline\Model\ResourceModel\ProductOptionCredit;
use Magento\Framework\Event\Observer;
use Magento\Catalog\Model\Product;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class ProductOptionValueSaveAfter implements ObserverInterface
{
    public function __construct(
        ProductOptionCreditFactory $productOptionCreditFactory,
        ProductOptionCredit $productOptionCreditResource
    ) {
        $this->productOptionCreditFactory  = $productOptionCreditFactory;
        $this->productOptionCreditResource = $productOptionCreditResource;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        /** @var Product $product */
        $product = $observer->getEvent()->getObject();

        if (!($product instanceof Product) || !$product->getId() ||
            $product->getTypeId() != Type::TYPE_CREDITPOINTS) {
            return $this;
        }

        $options    = $this->getCreditOptions($product);
        $optionType = $this->getCreditOptionsType($product);
        /** @var Collection $collection */
        $collection = $creditOption = $this->productOptionCreditFactory->create()->getCollection();
        $optionIds  = $collection->addProductFilter($product->getId())
            ->addStoreFilter($product->getStoreId())->getAllIds();
        $optionIds  = array_flip($optionIds);

        $resource = $this->productOptionCreditResource;
        foreach ($options as $option) {
            $creditOptionId = isset($option[Composite::FIELD_OPTION_ID]) ? $option[Composite::FIELD_OPTION_ID] : 0;
            $storeId = isset($option['store_id']) ? $option['store_id'] : $product->getStoreId();
            $creditOption = $this->productOptionCreditFactory->create();
            $resource->load($creditOption, $creditOptionId, $resource->getIdFieldName());
            $creditOption
                ->setOptionProductId($product->getId())
                ->setStoreId($storeId)
                ->setOptionPrice($option[Composite::FIELD_PRICE_NAME])
                ->setOptionPriceOptions($optionType)
            ;
            if ($optionType == Composite::PRICE_TYPE_RANGE) {
                $creditOption->setOptionMinCredits($option[Composite::FIELD_MIN_CREDITS_NAME])
                    ->setOptionMaxCredits($option[Composite::FIELD_MAX_CREDITS_NAME]);
            } else {
                $creditOption->setOptionPriceType($option[Composite::FIELD_PRICE_TYPE_NAME])
                    ->setOptionCredits($option[Composite::FIELD_CREDITS_NAME]);
            }

            $this->productOptionCreditResource->save($creditOption);

            if (isset($optionIds[$creditOptionId])) {
                unset($optionIds[$creditOptionId]);
            }
        }

        foreach ($optionIds as $id => $option) {
            $creditOption = $this->productOptionCreditFactory->create();
            $resource->load($creditOption, $id, $resource->getIdFieldName());
            $this->productOptionCreditResource->delete($creditOption);
        }

        return $this;
    }

    /**
     * @param Product $product
     * @return array
     */
    private function getCreditOptionsType($product)
    {
        return $product->getData(Composite::DATA_CREDIT_SCOPE)[Composite::FIELD_TYPE_NAME];
    }

    /**
     * @param Product $product
     * @return array
     */
    private function getCreditOptions($product)
    {
        $type    = $this->getCreditOptionsType($product);
        $options = (array)$product->getData(Composite::DATA_CREDIT_SCOPE);
        if ($type == Composite::PRICE_TYPE_FIXED) {
            $options = $options[Composite::CONTAINER_PRICE_GRID_NAME];
        } else {
            $options = [$options];
        }

        return $options;
    }
}