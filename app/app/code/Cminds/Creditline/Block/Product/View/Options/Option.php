<?php

namespace Cminds\Creditline\Block\Product\View\Options;

use Magento\Bundle\Block\Catalog\Product\Price;
use Cminds\Creditline\Helper\CreditOption;
use Cminds\Creditline\Model\ResourceModel\ProductOptionCredit\Collection;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Json\EncoderInterface;
use Magento\Catalog\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Math\Random;
use Magento\Checkout\Helper\Cart;
use Magento\Tax\Helper\Data as TaxHelperData;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Option extends Price
{
    public function __construct(
        CreditOption $optionHelper,
        Collection $optionCollection,
        Context $context,
        EncoderInterface $jsonEncoder,
        Data $catalogData,
        Registry $registry,
        StringUtils $string,
        Random $mathRandom,
        Cart $cartHelper,
        TaxHelperData $taxData,
        array $data = []
    ) {
        $this->optionHelper     = $optionHelper;
        $this->optionCollection = $optionCollection;

        parent::__construct($context, $jsonEncoder, $catalogData, $registry,
            $string, $mathRandom, $cartHelper, $taxData, $data);
    }

    /**
     * @return Collection
     */
    protected function getOptionsCollection()
    {
        return $this->optionCollection->addProductFilter($this->getProduct()->getId())
            ->addStoreFilter($this->_storeManager->getStore()->getId());
    }

    /**
     * @return array
     */
    public function getOptionData()
    {
        $collection = $this->getOptionsCollection();

        $data = [];
        /** @var ProductOptionCreditInterface $option */
        foreach ($collection as $option) {
            $data[] = [
                'id'        => $option->getId(),
                'type'      => $option->getOptionPriceType(),
                'credits'   => $option->getOptionCredits(),
                'min'       => $option->getOptionMinCredits(),
                'max'       => $option->getOptionMaxCredits(),
                'rangeRate' => $option->getOptionPrice(),
                'price'     => $this->optionHelper->getOptionPrice($option),
            ];
        }

        return $data;
    }
}