<?php

namespace Cminds\Creditline\Block\Adminhtml\Sales\Order\View\Items;

use Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer;
use Cminds\Creditline\Helper\CreditOption;
use Cminds\Creditline\Model\ProductOptionCreditFactory;
use Magento\Backend\Block\Template\Context;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\Framework\Registry;
use Magento\GiftMessage\Helper\Message;
use Magento\Checkout\Helper\Data;
use Magento\Framework\DataObject;
use Cminds\Creditline\Model\Product\Type;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Renderer extends DefaultRenderer
{
    public function __construct(
        CreditOption $optionHelper,
        ProductOptionCreditFactory $productOptionCredit,
        Context $context,
        StockRegistryInterface $stockRegistry,
        StockConfigurationInterface $stockConfiguration,
        Registry $registry,
        Message $messageHelper,
        Data $checkoutHelper,
        array $data = []
    ) {
        parent::__construct(
            $context, $stockRegistry, $stockConfiguration, $registry, $messageHelper, $checkoutHelper, $data
        );

        $this->optionHelper        = $optionHelper;
        $this->productOptionCredit = $productOptionCredit;
    }

    /**
     * @param DataObject $item
     * @param string     $column
     * @param null       $field
     * @return string
     */
    public function getColumnHtml(DataObject $item, $column, $field = null)
    {
        $html = parent::getColumnHtml($item, $column, $field);

        if ($column == 'credit') {
            $html .= $this->getCreditHtml($item);
        }

        return $html;
    }

    /**
     * @param Item $item
     * @return string
     */
    protected function getCreditHtml($item)
    {
        $credits = 0;
        if ($item->getProductType() == Type::TYPE_CREDITPOINTS) {
            $option = $this->productOptionCredit->create();
            $value  = $item->getBuyRequest()->getData('creditOption', 0);
            $data   = (array)$item->getBuyRequest()->getData('creditOptionData');
            $option->setData($data);
            $credits = $this->optionHelper->getOptionCredits($option, $value) * $item->getQtyOrdered();
        }

        return $credits;
    }
}
