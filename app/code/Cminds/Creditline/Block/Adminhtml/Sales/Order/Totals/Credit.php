<?php


namespace Cminds\Creditline\Block\Adminhtml\Sales\Order\Totals;

use Magento\Sales\Block\Adminhtml\Order\Totals;
use Cminds\Creditline\Helper\CreditOption;
use Cminds\Creditline\Model\ProductOptionCreditFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Helper\Admin;
use Magento\Framework\DataObject;
use Cminds\Creditline\Model\Product\Type;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Credit extends Totals
{

    public function __construct(
        CreditOption $optionHelper,
        ProductOptionCreditFactory $productOptionCreditFactory,
        Context $context,
        Registry $registry,
        Admin $adminHelper,
        array $data = []
    ) {
        parent::__construct($context, $registry, $adminHelper, $data);

        $this->optionHelper               = $optionHelper;
        $this->productOptionCreditFactory = $productOptionCreditFactory;
    }
    /**
     * Determine display parameters before rendering HTML.
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        $this->setCanDisplayTotalPaid($this->getParentBlock()->getCanDisplayTotalPaid());
        $this->setCanDisplayTotalRefunded($this->getParentBlock()->getCanDisplayTotalRefunded());
        $this->setCanDisplayTotalDue($this->getParentBlock()->getCanDisplayTotalDue());

        return $this;
    }

    /**
     * Initialize totals object.
     *
     * @return $this
     */
    public function initTotals()
    {
        $total = new DataObject([
            'code'       => $this->getNameInLayout(),
            'block_name' => $this->getNameInLayout(),
            'area'       => $this->getDisplayArea(),
            'strong'     => $this->getStrong(),
        ]);

        $this->getParentBlock()->addTotal($total, $this->getAfterCondition());

        return $this;
    }

    /**
     * Retrieve current order model instance.
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * Compatibility with Item
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }


    /**
     * @return string
     */
    public function displayPrices()
    {
        return $this->displayCreditlineAmount();
    }

    /**
     * @return string
     */
    public function displayCreditlineAmount()
    {
        $dataObject = $this->getSource();
        if ($dataObject instanceof \Magento\Sales\Model\Order) {
            $order = $dataObject;
        } else {
            $order = $dataObject->getOrder();
        }
        return $order->formatPrice(-$dataObject->getCreditlineAmount());
    }

    /**
     * Price attribute HTML getter.
     *
     * @param string $code
     * @param bool   $strong
     * @param string $separator
     *
     * @return string
     */
    public function displayPriceAttribute($code, $strong = false, $separator = '<br/>')
    {
        return $this->_adminHelper->displayPriceAttribute($this->getSource(), $code, $strong, $separator);
    }

    /**
     * Source order getter.
     *
     * @return Order
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * @return int
     */
    public function getPurchasedCredits()
    {
        $order = $this->getSource();

        $credits = 0;
        /** @var Item $item */
        foreach ($order->getAllItems() as $item) {
            if ($item->getProductType() == Type::TYPE_CREDITPOINTS
                || $item->getRealProductType() == Type::TYPE_CREDITPOINTS
            ) {
                $productOptions = $item->getProductOptions();
                if (!isset($productOptions['info_buyRequest'])) {
                    continue;
                }
                $requestOptions = $productOptions['info_buyRequest'];

                $option = $this->productOptionCreditFactory->create();
                $value  = !empty($requestOptions['creditOption']) ? $requestOptions['creditOption'] : 0;
                $data   = !empty($requestOptions['creditOptionData']) ? $requestOptions['creditOptionData'] : [];
                $option->setData($data);

                $credits += $this->optionHelper->getOptionCredits($option, $value) * $item->getQtyInvoiced();
            }
        }

        return $credits;
    }
}
