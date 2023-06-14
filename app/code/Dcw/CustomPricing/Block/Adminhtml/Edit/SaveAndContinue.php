<?php

namespace Dcw\CustomPricing\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveAndContinueButton
 */
class SaveAndContinue extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */

     public function getButtonData()
     {
         return [
             'label' => __('Save and Continue'),
             'class' => 'save',
             'data_attribute' => [
                 'mage-init' => [
                     'button' => ['event' => 'saveAndContinueEdit'],
                 ],
             ],
             'sort_order' => 80,
         ];
     }
}
