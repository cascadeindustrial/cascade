<?php

namespace Cminds\Creditline\Model\Product;

use Magento\Catalog\Model\Product;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

use Cminds\Creditline\Ui\DataProvider\Product\Form\Modifier\Composite;
use Cminds\Creditline\Model\ResourceModel\ProductOptionCredit\Collection as OptionCollection;
use Magento\Catalog\Model\Product\Type\Price as ProductTypePrice;
use Cminds\Creditline\Helper\CreditOption;
use Cminds\Creditline\Model\ResourceModel\ProductOptionCredit\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\CatalogRule\Model\ResourceModel\RuleFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\ManagerInterface;
use Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Price extends ProductTypePrice
{
    /**
     * @var array
     */
    private $options = [];

    public function __construct(
        CreditOption $optionHelper,
        CollectionFactory $productOptionCreditCollection,
        RequestInterface $request,
        ProductRepository $productRepository,
        RuleFactory $ruleFactory,
        StoreManagerInterface $storeManager,
        TimezoneInterface $localeDate,
        Session $customerSession,
        ManagerInterface $eventManager,
        PriceCurrencyInterface $priceCurrency,
        GroupManagementInterface $groupManagement,
        ProductTierPriceInterfaceFactory $tierPriceFactory,
        ScopeConfigInterface $config
    ) {
        $this->request                       = $request;
        $this->productRepository             = $productRepository;
        $this->productOptionCreditCollection = $productOptionCreditCollection;
        $this->optionHelper                  = $optionHelper;

        parent::__construct($ruleFactory, $storeManager, $localeDate, $customerSession, $eventManager, $priceCurrency,
            $groupManagement, $tierPriceFactory, $config);

    }

    /**
     * {@inheritdoc}
     */
    public function getPrice($product)
    {
        $product = $this->calcPrice($product);

        return parent::getPrice($product);
    }

    /**
     * {@inheritdoc}
     */
    public function getFinalPrice($qty, $product)
    {
        if ($qty === null && $product->getCalculatedFinalPrice() !== null) {
            return $product->getCalculatedFinalPrice();
        }

        $product = $this->calcPrice($product);

        $finalPrice = parent::getFinalPrice($qty, $product);

        $product->setData('final_price', $finalPrice);

        return max(0, $product->getData('final_price'));
    }

    /**
     * @param Product $product
     * @throws \Exception
     *
     * @return Product
     */
    protected function calcPrice($product)
    {
        $options = $this->getOptionsByProduct($product);
        $option  = $options->getFirstItem();

        $creditOption = $product->getCustomOption('option_creditOption');
        if ($creditOption && ($value = $creditOption->getValue())) {
            $id = $value;
            $creditOption = $product->getCustomOption('option_creditOptionId');
            if ($creditOption) {
                $id = $creditOption->getValue();
            }
            $price = 0;
            if ($option  = $options->getItemById($id)) {
                $product = $this->productRepository->getById($product->getId());
                $price = $this->optionHelper->getOptionPrice($option, $value);
                if (!$price) {
                    throw new \Exception('Can not add this product to cart');
                }
            }
            $product->setPrice($price);
        } elseif ($option->getOptionPriceOptions() == Composite::PRICE_TYPE_SINGLE) {
            $product = $this->productRepository->getById($product->getId());
            $product->setPrice($this->optionHelper->getOptionPrice($option));
        }

        return $product;
    }

    /**
     * @param Product $product
     * @return OptionCollection
     */
    private function getOptionsByProduct($product)
    {
        if (!isset($this->options[$product->getId()])) {
            $this->options[$product->getId()] = $this->productOptionCreditCollection->create()
                ->addProductFilter($product->getId());
        }

        return $this->options[$product->getId()];
    }
}
