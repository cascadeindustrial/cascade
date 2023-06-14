<?php

namespace Cminds\Creditline\Plugin\Order;

use Magento\Quote\Model\Quote\Item;
use Magento\Sales\Block\Adminhtml\Order\Create\Items\Grid;
use Cminds\Creditline\Helper\CreditOption;
use Cminds\Creditline\Model\ProductOptionCreditFactory;
use Cminds\Creditline\Model\Product\Type;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class GridItem
{
    public function __construct(
        CreditOption $optionHelper,
        ProductOptionCreditFactory $productOptionCredit
    ) {
        $this->optionHelper        = $optionHelper;
        $this->productOptionCredit = $productOptionCredit;
    }

    /**
     * @param Grid      $subject
     * @param callable $proceed
     * @param Item      $item
     * @return string
     */
    public function aroundGetItemUnitPriceHtml(Grid $subject, $proceed, Item $item)
    {
        $result = $proceed($item);

        $result .= $this->getCreditHtml($item);

        return $result;
    }

    /**
     * @param Grid      $subject
     * @param callable $proceed
     * @param Item      $item
     * @return string
     */
    public function aroundGetItemRowTotalHtml(Grid $subject, $proceed, Item $item)
    {
        $result = $proceed($item);

        $result .= $this->getCreditHtml($item, $item->getQty());

        return $result;
    }

    /**
     * @param Item $item
     * @param int  $qty
     * @return string
     */
    protected function getCreditHtml(Item $item, $qty = 1)
    {
        $html = '';
        if ($item->getProductType() == Type::TYPE_CREDITPOINTS) {
            $option = $this->productOptionCredit->create();
            $value  = $item->getProduct()->getCustomOption('option_creditOption');
            $data   = (array)$item->getBuyRequest()->getData('creditOptionData');
            $option->setData($data);

            $credits = $this->optionHelper->getOptionCredits($option, $value) * $qty;

            $html .= '<br>';
            $html .= __('Credit Lines: %1', $credits);
            $html .= '<br>';
        }

        return $html;
    }
}