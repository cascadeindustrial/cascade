var config = {

    config: {
        mixins: {
            // Checkout Shipping Validation
            'Magento_Checkout/js/view/shipping': {
                'MageMaclean_MyShipping/js/view/shipping': true
            },
            
            // Checkout Sidebar Summary Shipping Method Title
            'Magento_Checkout/js/view/shipping-information': {
                'MageMaclean_MyShipping/js/view/shipping-information': true
            },

            // Cart Sidebar Summary
            'Magento_Checkout/js/view/summary/shipping': {
                'MageMaclean_MyShipping/js/view/summary/shipping': true
            },

            // Cart Shipping Rates
            'Magento_Checkout/js/view/cart/shipping-rates': {
                'MageMaclean_MyShipping/js/view/cart/shipping-rates': true
            },

            // Totals Processor Override
            'Magento_Checkout/js/model/cart/totals-processor/default': {
                'MageMaclean_MyShipping/js/model/cart/totals-processor/default': true
            }
        }
    },
    "map": {
        "*": {
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': 'MageMaclean_MyShipping/js/model/shipping-save-processor/payload-extender-override',
        }
    }
 };
