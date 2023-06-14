<?php

namespace Cminds\Creditline\Block\Adminhtml\Catalog\Product\Composite\Fieldset\Options\Type;

use Cminds\Creditline\Block\Adminhtml\Catalog\Product\Composite\Fieldset\CreditOptions;
use Cminds\Creditline\Ui\DataProvider\Product\Form\Modifier\Composite;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Range extends CreditOptions
{
    /**
     * {@inherite}
     */
    protected $_template = 'product/composite/fieldset/options/type/range.phtml';

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return Composite::PRICE_TYPE_RANGE;
    }
}