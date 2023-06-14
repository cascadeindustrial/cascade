<?php

namespace Cminds\Creditline\Block\Adminhtml\Catalog\Product\Composite\Fieldset;

use Cminds\Creditline\Block\Product\View\Type\CreditOptions as CreditOptionsBlock;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CreditOptions extends CreditOptionsBlock
{
    /**
     * {@inheritdoc}
     */
    public function getJsonConfig()
    {
        $store          = $this->getCurrentStore();
        $attributesData = $this->getAttributesData();

        $config = [
            'attributes'         => $attributesData['attributes'],
            'optionPrices'       => $this->getOptionPrices(),
            'disablePriceReload' => 1,
            'prices'             => [
                'oldPrice' => [
                    'amount' => $this->_registerJsPrice('0.00'),
                ],
                'basePrice' => [
                    'amount' => $this->_registerJsPrice('0.00'),
                ],
                'finalPrice' => [
                    'amount' => $this->_registerJsPrice('0.00'),
                ],
            ],
            'productId'  => $this->getProduct()->getId(),
            'chooseText' => __('Choose an Option...'),
            'template'   => str_replace('%s', '<%- data.price %>', $store->getCurrentCurrency()->getOutputFormat()),
        ];

        $config = array_merge($config, $this->_getAdditionalConfig());

        return $this->jsonEncoder->encode($config);
    }

    /**
     * @return array
     */
    public function getAttributesData()
    {
        $attributeId   = $this->getProduct()->getId();
        $defaultValues = [];
        $attributes    = [];
        $attributeOptionsData = $this->getAttributeOptionsData();
        if ($attributeOptionsData) {
            $attributes[$attributeId] = [
                'id'       => $attributeId,
                'code'     => 'credits',
                'label'    => __('Credits'),
                'options'  => $attributeOptionsData,
                'position' => 0,
            ];
            $defaultValues[$attributeId] = 0;
        }
        return [
            'attributes' => $attributes,
            'defaultValues' => $defaultValues,
        ];
    }

    /**
     * @return array
     */
    protected function getAttributeOptionsData()
    {
        $attributeOptionsData = [];
        /** @var ProductOptionCredit $option */
        foreach ($this->getOptions() as $option) {
            $optionId = $option->getId();
            $attributeOptionsData[] = [
                'id'       => $optionId,
                'label'    => $option->getOptionCredits(),
                'products' => [$option->getId()],
            ];
        }
        return $attributeOptionsData;
    }

    /**
     * @return string
     */
    public function getOptionType()
    {
        return $this->getOptions()->getFirstItem()->getOptionPriceOptions();
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return $this->getProduct()->isSaleable() && $this->getOptionType() == $this->getType();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return '';
    }
}