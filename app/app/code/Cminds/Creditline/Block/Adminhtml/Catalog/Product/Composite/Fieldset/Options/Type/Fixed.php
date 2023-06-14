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
class Fixed extends CreditOptions
{
    /**
     * {@inheritdoc}
     */
    protected $_template = 'product/composite/fieldset/options/type/fixed.phtml';

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return Composite::PRICE_TYPE_FIXED;
    }
}