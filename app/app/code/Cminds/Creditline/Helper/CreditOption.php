<?php


namespace Cminds\Creditline\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Cminds\Creditline\Api\Data\ProductOptionCreditInterface;
use Cminds\Creditline\Ui\DataProvider\Product\Form\Modifier\Composite;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class CreditOption extends AbstractHelper
{
    /**
     * @param ProductOptionCreditInterface $option
     * @param int                          $value
     * @return float
     */
    public function getOptionPrice($option, $value = 0)
    {
        switch ($option->getOptionPriceOptions()) {
            case Composite::PRICE_TYPE_SINGLE:
            case Composite::PRICE_TYPE_FIXED:
                $price = $option->getOptionPrice();
                if ($option->getOptionPriceType() == 'percent') {
                    $price = $price / 100 * $option->getOptionCredits();
                }
                break;
            case Composite::PRICE_TYPE_RANGE:
                $price = $option->getOptionPrice() * $value;
                break;
            default:
                $price = 0;
        }

        return $price;
    }
    /**
     * @param ProductOptionCreditInterface $option
     * @param int                          $value
     * @return int
     */
    public function getOptionCredits($option, $value = 0)
    {
        switch ($option->getOptionPriceOptions()) {
            case Composite::PRICE_TYPE_SINGLE:
            case Composite::PRICE_TYPE_FIXED:
                $credits = $option->getOptionCredits();
                break;
            case Composite::PRICE_TYPE_RANGE:
                $credits = $value;
                break;
            default:
                $credits = 0;
        }

        return $credits;
    }
}
