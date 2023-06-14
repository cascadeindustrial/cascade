// Checkout payment methods view mixin
define([
    'Magento_Checkout/js/model/payment-service',
    'Amasty_Checkout/js/view/utils',
    'mage/translate'
], function (paymentService, viewUtils, $t) {
    'use strict';

        var vaultGroupTitle = $t('Saved Payment Methods');


    return function (Component) {
        return Component.extend({
            /**
             * add loader block for payment
             */
            isLoading: paymentService.isLoading,

            getGroupTitle: function (newValue) {
                var paymentMethodLayoutConfig = viewUtils.getBlockLayoutConfig('payment_method');

                if (newValue().index === 'vaultGroup') {
                   return vaultGroupTitle;
               }

                if (newValue().index === 'methodGroup'
                    && paymentMethodLayoutConfig !== null
                ) {
                    return paymentMethodLayoutConfig.title;
                }

                return this._super(newValue);
            }
        });
    };
});
