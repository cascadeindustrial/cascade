<?php

namespace Cminds\Creditline\Block\Product\View;

use Cminds\Creditline\Ui\DataProvider\Product\Form\Modifier\Composite;
use Magento\Catalog\Block\Product\View\AbstractView;
use Cminds\Creditline\Model\ResourceModel\ProductOptionCredit\CollectionFactory;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Stdlib\ArrayUtils;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Options extends AbstractView
{
    public function __construct(
        CollectionFactory $optionCollection,
        Context $context,
        ArrayUtils $arrayUtils,
        array $data = []
    ) {
        $this->optionCollection = $optionCollection;

        parent::__construct($context, $arrayUtils, $data);
    }

    /**
     * @return string
     */
    public function getOptionType()
    {
        return $this->optionCollection->create()->addProductFilter($this->getProduct()->getId())
                    ->addStoreFilter($this->_storeManager->getStore()->getId())
                    ->getFirstItem()
                    ->getOptionPriceOptions();
    }

    /**
     * @return bool
     */
    public function isShowOptions()
    {
        $type = $this->getOptionType();
        if ($type && $type != Composite::PRICE_TYPE_SINGLE) {
            return true;
        }

        return false;
    }
}
