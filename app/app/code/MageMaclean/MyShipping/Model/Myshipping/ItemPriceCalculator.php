<?php
namespace MageMaclean\MyShipping\Model\Myshipping;

use Magento\Quote\Model\Quote\Address\RateRequest;

use MageMaclean\MyShipping\Model\Carrier;
use MageMaclean\MyShipping\Api\Data\MyshippingResultInterface;

class ItemPriceCalculator
{
    /**
     * @param array $items
     * @return float|int|mixed
     */
    protected function _getPackageQty(array $items) {
        $packageQty = 0;

        foreach ($items as $item) {
            /**
             * Skip if this item is virtual
             */
            if ($item->getProduct()->isVirtual()) {
                continue;
            }

            /**
             * Children weight we calculate for parent
             */
            if ($item->getParentItem()) {
                continue;
            }

            $itemQty = (float)$item->getQty();
            if ($item->getHasChildren() && $item->isShipSeparately()) {
                foreach ($item->getChildren() as $child) {
                    if ($child->getProduct()->isVirtual()) {
                        continue;
                    }
                    $packageQty += $child->getTotalQty();
                }
            } else {
                if (!$item->getProduct()->isVirtual()) {
                    $packageQty += $itemQty;
                }
            }
        }

        return $packageQty;
    }
    /**
     * @param array $items
     * @param MyshippingRateResultInterface $myshippingRateResult
     * @return float
     */
    public function getShippingPrice(array $items, MyshippingResultInterface $myshippingResult)
    {
        $numBoxes = $this->_getPackageQty($items);
        $shippingPrice = 0;

        if(!$myshippingResult->getCourier()) return $shippingPrice;
        if(!$myshippingResult->getMyshippingCourierMethod()) return $shippingPrice;
        if(empty($myshippingResult->getMyshippingCourierMethod())) return $shippingPrice;

        $courier = $myshippingResult->getCourier();
        $method = $courier->getMethodByCode($myshippingResult->getMyshippingCourierMethod());
        if(!$method) return $shippingPrice;

        $methodPrice = (float) $method['method_price'];
        if ($method['method_price_type'] === 'O') {
            $shippingPrice = $methodPrice;
        } elseif ($method['method_price_type'] === 'I') {
            $shippingPrice = $numBoxes * $methodPrice;
        }

        return $this->_getFinalPriceWithHandlingFee($numBoxes, $shippingPrice, $method['method_handling_fee'], $method['method_handling_type'], $method['method_handling_action']);
    }


    /**
     * @param array $items
     * @param array $methodData
     * @return float
     */
    public function getShippingPriceByMethodData($items, $methodData)
    {
        $numBoxes = $this->_getPackageQty($items);
        $shippingPrice = 0;

        $methodPrice = (float) $methodData['method_price'];
        if ($methodData['method_price_type'] === 'O') {
            $shippingPrice = $methodPrice;

        } elseif ($methodData['method_price_type'] === 'I') {
            $shippingPrice = $numBoxes * $methodPrice;
        }

        return $this->_getFinalPriceWithHandlingFee($numBoxes, $shippingPrice, $methodData['method_handling_fee'], $methodData['method_handling_type'], $methodData['method_handling_action']);
    }


    /**
     * @param RateRequest $request
     * @param float $basePrice
     * @return float
     */
    public function getShippingPricePerItem(
        RateRequest $request,
        $basePrice
    ) {
        return $request->getPackageQty() * $basePrice;
    }

    /**
     * @param int $numBoxes
     * @param float $basePrice
     * @return float
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getShippingPricePerOrder(
        RateRequest $request,
        $basePrice
    ) {
        return $basePrice;
    }

    /**
     * Calculate final price with handling fee
     *
     * @param int $numBoxes
     * @param float $cost
     * @param float $handlingFee
     * @param string $handlingType
     * @param string $handlingAction
     * @return float
     */
    public function _getFinalPriceWithHandlingFee($numBoxes, $cost, $handlingFee, $handlingType, $handlingAction)
    {
        if (!$handlingType) {
            $handlingType = Carrier::HANDLING_TYPE_FIXED;
        }
        if (!$handlingAction) {
            $handlingAction = Carrier::HANDLING_ACTION_PERORDER;
        }

        return $handlingAction == Carrier::HANDLING_ACTION_PERPACKAGE ? $this->_getPerpackagePrice(
            $numBoxes,
            $cost,
            $handlingType,
            $handlingFee
        ) : $this->_getPerorderPrice(
            $numBoxes,
            $cost,
            $handlingType,
            $handlingFee
        );
    }

    /**
     * Get final price for shipping method with handling fee per package
     *
     * @param int $numBoxes
     * @param float $cost
     * @param string $handlingType
     * @param float $handlingFee
     * @return float
     */
    protected function _getPerpackagePrice($numBoxes, $cost, $handlingType, $handlingFee)
    {
        if ($handlingType == Carrier::HANDLING_TYPE_PERCENT) {
            return $cost + (($cost * ($handlingFee / 100) * $numBoxes));
        }

        return $cost + ($handlingFee * $numBoxes);
    }

    /**
     * Get final price for shipping method with handling fee per order
     *
     * @param int $numBoxes
     * @param float $cost
     * @param string $handlingType
     * @param float $handlingFee
     * @return float
     */
    protected function _getPerorderPrice($numBoxes, $cost, $handlingType, $handlingFee)
    {
        if ($handlingType == Carrier::HANDLING_TYPE_PERCENT) {
            return $cost + $cost * $handlingFee / 100;
        }

        return $cost + $handlingFee;
    }
}
