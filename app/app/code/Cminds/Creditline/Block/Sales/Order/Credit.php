<?php


namespace Cminds\Creditline\Block\Sales\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\DataObject;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Credit extends Template
{
    /**
     * @return $this
     */
    public function initTotals()
    {
        if (floatval($this->getSource()->getCreditlineAmount()) == 0) {
            return $this;
        }

        $total = new DataObject([
            'code'       => $this->getNameInLayout(),
            'block_name' => $this->getNameInLayout(),
            'area'       => $this->getArea(),
        ]);

        $this->getParentBlock()->addTotal($total, $this->getAfterTotal());

        return $this;
    }

    /**
     * @return Order
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
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
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }
}
