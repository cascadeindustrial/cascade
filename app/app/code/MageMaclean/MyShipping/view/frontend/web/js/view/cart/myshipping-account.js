define([
    'jquery',
    'ko',
    'underscore',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/checkout-data'
], function ($, ko, _, Component, quote, selectShippingMethodAction, checkoutData) {
    'use strict';

    return Component.extend({
        defaults: {
            links: {
                myshippingAccountId: '${ $.provider }:${ $.customScope }.myshipping_account_id',
                myshippingCourierId: '${ $.provider }:${ $.customScope }.myshipping_courier_id',
                myshippingCourierMethod: '${ $.provider }:${ $.customScope }.myshipping_courier_method'
            },
            listens: {
                myshippingCourierMethod: 'onUpdateInformation'
            }
        },

        onUpdateInformation: function () {
            if(!this.myshippingAccountId
                || this.myshippingCourierMethod == undefined
                || this.myshippingCourierMethod == "")
                return;

            var methodData = quote.shippingMethod();
            if(methodData) {
                selectShippingMethodAction(methodData);
                checkoutData.setSelectedShippingRate(methodData['carrier_code'] + '_' + methodData['method_code']);
            }
        }
    });
});
