<?php

namespace Cminds\Creditline\Model\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Cminds\Creditline\Ui\DataProvider\Product\Form\Modifier\Composite;
use Magento\Catalog\Model\Product\Type\Virtual;
use Cminds\Creditline\Helper\CreditOption;
use Cminds\Creditline\Model\ResourceModel\ProductOptionCredit\CollectionFactory;
use Magento\Catalog\Model\Product\Option;
use Magento\Eav\Model\Config;
use Magento\Catalog\Model\Product\Type as CatalogProductType;
use Magento\Framework\Event\ManagerInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Framework\Filesystem;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Type extends Virtual
{
    const TYPE_CREDITPOINTS_FIELD = 'creditline_box';
    const TYPE_CREDITPOINTS = 'creditpoints';

    /**
     * @var array
     */
    private $creditOptions = [];

    public function __construct(
        CreditOption $optionHelper,
        CollectionFactory $productOptionCreditCollection,
        Option $catalogProductOption,
        Config $eavConfig,
        CatalogProductType $catalogProductType,
        ManagerInterface $eventManager,
        Database $fileStorageDb,
        Filesystem $filesystem,
        Registry $coreRegistry,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository
    ) {
        $this->optionHelper                  = $optionHelper;
        $this->productOptionCreditCollection = $productOptionCreditCollection;

        parent::__construct(
            $catalogProductOption,
            $eavConfig,
            $catalogProductType,
            $eventManager,
            $fileStorageDb,
            $filesystem,
            $coreRegistry,
            $logger,
            $productRepository
        );
    }

    /**
     * {@inheritdoc}
     */
    public function hasRequiredOptions($product)
    {
        return $this->getCreditOptions($product->getId())->getFirstItem()
            ->getOptionPriceOptions() != Composite::PRICE_TYPE_SINGLE;
    }

    /**
     * {@inheritdoc}
     */
    public function isSalable($product)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function canConfigure($product)
    {
        $option = $this->getCreditOptions($product->getId())->getFirstItem();

        $result = true;
        if ($option->getOptionPriceOptions() == Composite::PRICE_TYPE_SINGLE) {
            $result = false;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderOptions($product)
    {
        $options = parent::getOrderOptions($product);

        if ($product->getTypeId() != self::TYPE_CREDITPOINTS) {
            return $options;
        }

        $option = $this->getCreditOptions($product->getId())->getFirstItem();
        if ($option->getOptionPriceOptions() == Composite::PRICE_TYPE_SINGLE) {
            $options['info_buyRequest']['creditOption'] = $option->getId();
        }

        $optionsId = isset($options['info_buyRequest']['creditOptionId'])
            ? $options['info_buyRequest']['creditOptionId']
            : $options['info_buyRequest']['creditOption'];

        $productOption = $this->getCreditOptionsById($product->getId(), $optionsId);
        if (!$productOption) {
            return $options;
        }
        $productOption->setId('creditOption')
            ->setType(Option::OPTION_GROUP_TEXT);

        $options['info_buyRequest']['creditOptionData'] = $productOption->getData();

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareOptions(DataObject $buyRequest, $product, $processMode)
    {
        $options = parent::_prepareOptions($buyRequest, $product, $processMode);

        if (is_object($buyRequest)) {
            $buyRequest = $buyRequest->getData();
        }
        if (!empty($buyRequest['creditOption'])) {
            $options['creditOption']    = $buyRequest['creditOption'];
        }
        if (!empty($buyRequest['creditOptionId'])) {
            $options['creditOptionId'] = $buyRequest['creditOptionId'];
        }

        $isValid = $this->validateOption($product, $buyRequest);

        if (!$isValid) {
            throw new LocalizedException($this->getSpecifyOptionMessage());
        }

        return $options;
    }

    /**
     * @param Product $product
     * @param array $buyRequest
     * @return bool
     */
    private function validateOption($product, $buyRequest)
    {
        $optionId = !empty($buyRequest['creditOption']) ? $buyRequest['creditOption'] : 0;
        $optionId = !empty($buyRequest['creditOptionId']) ? $buyRequest['creditOptionId'] : $optionId;

        if ($product->getTypeId() == Composite::PRICE_TYPE_SINGLE) {
            return true;
        }

        $result = ($optionId && $this->getCreditOptionsById($product->getId(), $optionId)) ||
            !(empty($optionId) && $this->hasRequiredOptions($product));

        return $result;
    }

    /**
     * @param int $productId
     * @return Collection
     */
    private function getCreditOptions($productId)
    {
        if (empty($this->creditOptions[$productId])) {
            $this->creditOptions[$productId] = $this->productOptionCreditCollection->create()
                ->addProductFilter($productId);
        }

        return $this->creditOptions[$productId];
    }

    /**
     * @param int $productId
     * @param int $optionId
     * @return ProductOptionCredit
     */
    private function getCreditOptionsById($productId, $optionId)
    {
        return $this->getCreditOptions($productId)->getItemById($optionId);
    }
}
