<?php
namespace MageMaclean\MyShipping\Plugin\Cart;

class LayoutProcessor
{
    protected $_layoutProcessor;

    public function __construct(
        \MageMaclean\MyShipping\Model\Checkout\LayoutProcessor $layoutProcessor
    ) {
        $this->_layoutProcessor = $layoutProcessor;
    }

    public function afterProcess(
        \Magento\Checkout\Block\Cart\LayoutProcessor $subject,
        array $jsLayout
    ) {
        if (isset($jsLayout['components']['block-summary']['children']['block-rates']['children'])) {
            $fieldSetPointer = &$jsLayout['components']['block-summary']['children']['block-rates']['children'];

            $newComponent = $this->_layoutProcessor->createMyshippingNewComponent("MageMaclean_MyShipping/js/view/cart/myshipping-new", true, true);
            $accountComponents = $this->_layoutProcessor->createMyshippingAccountComponents("MageMaclean_MyShipping/js/view/cart/myshipping-account", true);

            $fieldSetPointer = $this->_layoutProcessor->merge($newComponent, $fieldSetPointer);
            $fieldSetPointer = $this->_layoutProcessor->merge($accountComponents, $fieldSetPointer);
        }

        return $jsLayout;
    }
}
