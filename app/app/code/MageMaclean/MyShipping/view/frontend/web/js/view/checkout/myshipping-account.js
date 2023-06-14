define([
    'jquery',
    'ko',
    'underscore',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/set-shipping-information'
], function ($, ko, _, Component, quote, setShippingInformationAction) {
    'use strict';

    return Component.extend({
        defaults: {
            listens: {
                myshippingCourierMethod: 'onUpdateData'
            },
            imports: {
                myshippingAccountId: '${ $.provider }:${ $.customScope }.myshipping_account_id',
                myshippingCourierId: '${ $.provider }:${ $.customScope }.myshipping_courier_id',
                myshippingCourierMethod: '${ $.provider }:${ $.customScope }.myshipping_courier_method'
            }
        },
        onUpdateData: function () {
            if(quote.shippingAddress() && this.myshippingCourierMethod) {
                setShippingInformationAction();
            }
        }
    });
});
