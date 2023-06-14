<?php
namespace MageMaclean\MyShipping\Plugin\Checkout;

class LayoutProcessor
{
    protected $_layoutProcessor;

    public function __construct(
        \MageMaclean\MyShipping\Model\Checkout\LayoutProcessor $layoutProcessor
    ) {
        $this->_layoutProcessor = $layoutProcessor;
    }

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children'])
        ) {
            $fieldSetPointer = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children'];

            $newComponent = $this->_layoutProcessor->createMyshippingNewComponent("MageMaclean_MyShipping/js/view/checkout/myshipping-new");
            $accountComponents = $this->_layoutProcessor->createMyshippingAccountComponents("MageMaclean_MyShipping/js/view/checkout/myshipping-account");

            $fieldSetPointer = $this->_layoutProcessor->merge($newComponent, $fieldSetPointer);
            $fieldSetPointer = $this->_layoutProcessor->merge($accountComponents, $fieldSetPointer);
        }

        return $jsLayout;
    }
}
