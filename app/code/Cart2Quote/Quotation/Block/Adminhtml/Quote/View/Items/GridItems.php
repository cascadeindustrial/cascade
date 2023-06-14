<?php
/**
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Items;

use Cart2Quote\Quotation\Model\Quote\TierItem;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Quote\Model\Quote\Item;

/**
 * Class GridItems
 *
 * @package Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Items
 */
class GridItems extends \Magento\Sales\Block\Adminhtml\Order\View\Items
{
    const FOOTER_TYPE = 'footer';

    /**
     * Quotation Quote
     *
     * @var \Cart2Quote\Quotation\Model\Quote
     */
    protected $quote;

    /**
     * @var \Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Items
     */
    protected $itemsBlock;

    /**
     * Quote items
     *
     * @var \Magento\Quote\Model\ResourceModel\Quote\Item\Collection
     */
    protected $items;

    /**
     * Tier item collection factory
     *
     * @var \Cart2Quote\Quotation\Model\ResourceModel\Quote\TierItem\CollectionFactory
     */
    protected $tierItemCollectionFactory;

    /**
     * @var \Cart2Quote\Quotation\Model\Quote\SectionFactory
     */
    private $sectionFactory;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var \Cart2Quote\Quotation\Helper\QuotationTaxHelper
     */
    private $quotationTaxHelper;

    /**
     * @var \Cart2Quote\Quotation\Helper\QuoteItems
     */
    private $quoteItemsHelper;

    /**
     * GridItems constructor.
     *
     * @param \Cart2Quote\Quotation\Helper\QuoteItems $quoteItemsHelper
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Cart2Quote\Quotation\Model\Quote\SectionFactory $sectionFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\Framework\Registry $registry
     * @param \Cart2Quote\Quotation\Model\Quote $quote
     * @param \Cart2Quote\Quotation\Model\ResourceModel\Quote\TierItem\CollectionFactory $tierItemCollectionFactory
     * @param \Cart2Quote\Quotation\Helper\QuotationTaxHelper $quotationTaxHelper
     * @param array $data
     */
    public function __construct(
        \Cart2Quote\Quotation\Helper\QuoteItems $quoteItemsHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Cart2Quote\Quotation\Model\Quote\SectionFactory $sectionFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Magento\Framework\Registry $registry,
        \Cart2Quote\Quotation\Model\Quote $quote,
        \Cart2Quote\Quotation\Model\ResourceModel\Quote\TierItem\CollectionFactory $tierItemCollectionFactory,
        \Cart2Quote\Quotation\Helper\QuotationTaxHelper $quotationTaxHelper,
        array $data = []
    ) {
        $this->quoteItemsHelper = $quoteItemsHelper;
        $this->quote = $quote;
        $this->tierItemCollectionFactory = $tierItemCollectionFactory;
        $this->quotationTaxHelper = $quotationTaxHelper;
        parent::__construct($context, $stockRegistry, $stockConfiguration, $registry, $data);
        $this->sectionFactory = $sectionFactory;
        $this->jsonEncoder = $jsonEncoder;
    }

    /**
     * Retrieve rendered item footer html content
     *
     * @return string
     */
    public function getItemFooterHtml()
    {
        return $this->getItemRenderer(self::FOOTER_TYPE)->setCanEditQty($this->canEditQty())->toHtml();
    }

    /**
     * Check availability to edit quantity of item
     * - Overwritten because we don't want the payment to be validated on a quote.
     *
     * @return bool
     */
    public function canEditQty()
    {
        return true;
    }

    /**
     * Get the quote items
     *
     * @return \Magento\Quote\Model\ResourceModel\Quote\Item\Collection
     */
    public function getItemsCollection()
    {
        if (!$this->items) {
            $this->items = $this->getItemsGridBlock()->getItems();
            $this->addTierItemsToQuoteItem();
        }

        return $this->items;
    }

    /**
     * Get items grid block
     *
     * @return bool|\Cart2Quote\Quotation\Block\Adminhtml\Quote\View\Items\Grid
     * @throws \Exception
     */
    public function getItemsGridBlock()
    {
        if (!$block = $this->getChildBlock('items_grid')) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Quote Items render error: "items_grid" needs to be a child of the block "items"')
            );
        }

        return $block;
    }

    /**
     * Add the tier items to the quote item if the tier items are not set on the quote item.
     *
     * @return void
     */
    protected function addTierItemsToQuoteItem()
    {
        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($this->items as &$item) {
            if (!$item->getCurrentTierItem() || !$item->getTierItems()) {
                $tierItemFactory = $this->tierItemCollectionFactory->create();
                $item = $tierItemFactory->setItemTiers($item);
            }
        }
    }

    /**
     * Retrieve all items
     *
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection
     */
    public function getItems()
    {
        return $this->getOrder()->getItemsCollection();
    }

    /**
     * OVERWRITE Magento getOrder function
     * - Retrieve quote model object
     *
     * @return \Cart2Quote\Quotation\Model\Quote
     */
    public function getOrder()
    {
        return $this->getQuote();
    }

    /**
     * Check gift messages availability
     *
     * @param Item|null $item
     * @return bool|null|string
     */
    public function isGiftMessagesAvailable($item = null)
    {
        return $this->getItemsGridBlock()->isGiftMessagesAvailable($item);
    }

    /**
     * Get discount amount
     *
     * @return float
     */
    public function getDiscountAmount()
    {
        return $this->getItemsGridBlock()->getDiscountAmount();
    }

    /**
     * Retrieve rendered item html content
     *
     * @param \Magento\Framework\DataObject $item
     * @return string
     */
    public function getEmptyItemHtml(\Magento\Framework\DataObject $item)
    {
        if ($item->getOrderItem()) {
            $type = $item->getOrderItem()->getProductType();
        } else {
            $type = $item->getProductType();
        }

        $item->setFirst(false);

        return $this->getItemRenderer($type)->setItem($item)->setCanEditQty(true)->setEmpty(true)->toHtml();
    }

    /**
     * Render the rows in combination with the tiers
     * First row that is rendered is the selected tier,
     * after the selected tier the other tiers are rendered ordered by the qty
     *
     * @param Item $item
     * @return string
     */
    public function getTierItemsHtml($item)
    {
        $html = '';
        $tierItemCount = 0;

        // Render selected tier first and afterwards the other tiers
        if ($currentTierItem = $item->getCurrentTierItem()) {
            $this->setDefaultValuesToItem($item, $currentTierItem, $tierItemCount);
            $html .= $this->getItemHtml($item);
            $tierItemCount++;
        }

        foreach ($this->getTierItems($item) as $tierItem) {
            $this->setDefaultValuesToItem($item, $tierItem, $tierItemCount);

            if ($item->getIsSelectedTier()) {
                continue;
            }

            $html .= $this->getItemHtml($item);
            $tierItemCount++;
        }

        return $html;
    }

    /**
     * Set the default values on the quote item
     * - The values can be used in the view
     *
     * @param Item $item
     * @param TierItem $tierItem
     * @param Int $tierItemCount
     * @return Item $item
     */
    public function setDefaultValuesToItem($item, $tierItem, $tierItemCount)
    {
        if ($tierItem) {
            $tierItemId = $tierItem->getId();
            $currentTierItemId = 0;
            if ($item->getCurrentTierItem()) {
                $currentTierItemId = $item->getCurrentTierItem()->getId();
            }

            //sync quote
            if ($item->getQuote()) {
                if (!$tierItem->getQuote()) {
                    $tierItem->setQuote($item->getQuote());
                }
                if ($tierItem->getItem() && !$tierItem->getItem()->getQuote()) {
                    $tierItem->getItem()->setQuote($item->getQuote());
                }
            }

            $item->setTierItem($tierItem);
            $item->setIsFirstTierItem($tierItemCount == 0);
            $item->setTierItemCount($tierItemCount);
            $item->setIsSelectedTier($tierItemId == $currentTierItemId);
        }

        return $item;
    }

    /**
     * Get tier items
     *
     * @param TierItem $item
     * @return array
     */
    public function getTierItems($item)
    {
        if ($tierItems = $item->getTierItems()) {
            return $tierItems;
        }

        return [];
    }

    /**
     * Get sections from quote
     *
     * @return \Cart2Quote\Quotation\Api\Data\Quote\SectionInterface[]
     */
    public function getSections()
    {
        return $this->getQuote()->getSections(['label' => __('Not Assigned to Section')]);
    }

    /**
     * Overwrite the beforeToHtml
     */
    protected function _beforeToHtml()
    {
        $this->setOrder($this->getQuote());
    }

    /**
     * Retrieve quote model object
     *
     * @return \Cart2Quote\Quotation\Model\Quote
     */
    public function getQuote()
    {
        if (!$quote = $this->_coreRegistry->registry('current_quote')) {
            $this->_coreRegistry->register(
                'current_quote',
                $this->quote->load($this->getRequest()->getParam('quote_id'))
            );
        }

        return $quote;
    }

    /**
     * Get including/excluding tax message
     *
     * @return \Magento\Framework\Phrase
     */
    public function getInclExclTaxMessage()
    {
        if ($this->quotationTaxHelper->priceIncludesTax($this->getStore())
            && $this->quotationTaxHelper->applyTaxOnCustomPrice($this->getStore())
        ) {
            return __('* - Enter custom price including tax');
        } else {
            return __('* - Enter custom price excluding tax');
        }
    }

    /**
     * Get store
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        return $this->getQuote()->getStore();
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        $itemsConfig = $this->getItemsGridConfig();
        $columns = array_key_exists('columns', $this->_data) ? $this->_data['columns'] : [];
        if (isset($itemsConfig)) {
            foreach ($itemsConfig as $itemConfig) {
                if ($itemConfig['visibility'] == false) {
                    unset($columns[$itemConfig['name']]);
                }
            }

            return $columns;
        }

        return parent::getColumns();
    }

    /**
     * Get statuses configuration settings
     *
     * @return array
     */
    public function getItemsGridConfig()
    {
        return $this->quoteItemsHelper->getQuoteItemsConfigArray() ?? [];
    }
}
