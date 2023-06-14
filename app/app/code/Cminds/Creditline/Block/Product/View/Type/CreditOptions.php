<?php

namespace Cminds\Creditline\Block\Product\View\Type;

use Cminds\Creditline\Ui\DataProvider\Product\Form\Modifier\Composite;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Catalog\Block\Product\View\AbstractView;
use Cminds\Creditline\Helper\CreditOption;
use Cminds\Creditline\Model\ProductOptionCreditFactory;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Framework\Json\EncoderInterface;
use Magento\ConfigurableProduct\Helper\Data;
use Magento\Catalog\Helper\Product;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CreditOptions extends AbstractView
{
    /**
     * Catalog product
     *
     * @var Product
     */
    protected $catalogProduct = null;

    /**
     * Current customer
     *
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * Prices
     *
     * @var array
     */
    protected $_prices = [];

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var Data $imageHelper
     */
    protected $helper;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var array
     */
    protected $options = [];

    public function __construct(
        CreditOption $optionHelper,
        ProductOptionCreditFactory $productOptionCreditFactory,
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        Data $helper,
        Product $catalogProduct,
        CurrentCustomer $currentCustomer,
        PriceCurrencyInterface $priceCurrency,
        ConfigurableAttributeData $configurableAttributeData,
        array $data = []
    ) {
        $this->optionHelper               = $optionHelper;
        $this->productOptionCreditFactory = $productOptionCreditFactory;
        $this->priceCurrency              = $priceCurrency;
        $this->helper                     = $helper;
        $this->jsonEncoder                = $jsonEncoder;
        $this->catalogProduct             = $catalogProduct;
        $this->currentCustomer            = $currentCustomer;
        $this->configurableAttributeData  = $configurableAttributeData;

        parent::__construct($context, $arrayUtils, $data);
    }

    /**
     * @return Collection
     */
    public function getOptions()
    {
        if (!$this->options) {
            $product = $this->getProduct();
            $this->options = $this->productOptionCreditFactory->create()->getCollection()
                ->addProductFilter($product->getId())
                ->addStoreFilter($product->getStoreId())
            ;
        }

        return $this->options;
    }

    /**
     * @return bool
     */
    public function hasOptions()
    {
        return $this->getOptions()->getFirstItem()->getOptionPriceOptions() != Composite::PRICE_TYPE_SINGLE;
    }

    /**
     * Get Allowed Products
     *
     * @return Product[]
     */
    public function getAllowProducts()
    {
        if (!$this->hasAllowProducts()) {
            $products = $this->getProduct()->getTypeInstance()->getSalableUsedProducts($this->getProduct(), null);
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }

    /**
     * Retrieve current store
     *
     * @return Store
     */
    public function getCurrentStore()
    {
        return $this->_storeManager->getStore();
    }

    /**
     * Returns additional values for js config, con be overridden by descendants
     *
     * @return array
     */
    protected function _getAdditionalConfig()
    {
        return [];
    }

    /**
     * Composes configuration for js
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $store          = $this->getCurrentStore();
        $currentProduct = $this->getProduct();

        $regularPrice = $currentProduct->getPriceInfo()->getPrice('regular_price');
        $finalPrice   = $currentProduct->getPriceInfo()->getPrice('final_price');

        $options        = $this->helper->getOptions($currentProduct, $this->getAllowProducts());
        $attributesData = $this->configurableAttributeData->getAttributesData($currentProduct, $options);

        $config = [
            'attributes'   => $attributesData['attributes'],
            'template'     => str_replace('%s', '<%- data.price %>', $store->getCurrentCurrency()->getOutputFormat()),
            'optionPrices' => $this->getOptionPrices(),
            'prices'       => [
                'oldPrice'   => [
                    'amount' => $this->_registerJsPrice($regularPrice->getAmount()->getValue()),
                ],
                'basePrice'  => [
                    'amount' => $this->_registerJsPrice($finalPrice->getAmount()->getBaseAmount()),
                ],
                'finalPrice' => [
                    'amount' => $this->_registerJsPrice($finalPrice->getAmount()->getValue()),
                ],
            ],
            'productId'  => $currentProduct->getId(),
            'chooseText' => __('Choose an Option...'),
            'images'     => isset($options['images']) ? $options['images'] : [],
            'index'      => isset($options['index']) ? $options['index'] : [],
        ];

        if ($currentProduct->hasPreconfiguredValues() && !empty($attributesData['defaultValues'])) {
            $config['defaultValues'] = $attributesData['defaultValues'];
        }

        $config = array_merge($config, $this->_getAdditionalConfig());

        return $this->jsonEncoder->encode($config);
    }

    /**
     * @return array
     */
    protected function getOptionPrices()
    {
        $prices = [];
        $product = $this->getProduct();
        $customOptions = $product->getCustomOptions();
        foreach ($this->getOptions() as $option) {
            $option->setValue($option->getId());
            $customOptions['optionCreditOption'] = $option;
            $product->setCustomOptions($customOptions);
            $product->reloadPriceInfo();
            $priceInfo = $product->getPriceInfo();

            $prices[$option->getId()] =
                [
                    'oldPrice' => [
                        'amount' => $this->_registerJsPrice(
                            $priceInfo->getPrice('regular_price')->getAmount()->getValue()
                        ),
                    ],
                    'basePrice' => [
                        'amount' => $this->_registerJsPrice(
                            $priceInfo->getPrice('final_price')->getAmount()->getBaseAmount()
                        ),
                    ],
                    'finalPrice' => [
                        'amount' => $this->_registerJsPrice(
                            $priceInfo->getPrice('final_price')->getAmount()->getValue()
                        ),
                    ]
                ];
        }
        return $prices;
    }

    /**
     * Replace ',' on '.' for js
     *
     * @param float $price
     * @return string
     */
    protected function _registerJsPrice($price)
    {
        return str_replace(',', '.', $price);
    }
}
