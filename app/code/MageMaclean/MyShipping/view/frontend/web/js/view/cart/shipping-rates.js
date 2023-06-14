define([
    'jquery',
    'ko',
    'underscore',
    'Magento_Checkout/js/model/quote'
], function ($, ko, _, quote, selectShippingMethodAction, checkoutData) {
    'use strict';

    var mixin = {
        defaults: {
            template: 'MageMaclean_MyShipping/cart/shipping-rates'
        },
        initObservable: function () {
            var self = this;
            this._super();

            return this;
        },
        selectedMyshippingMethod: ko.computed(function () {
            return (
                quote.shippingMethod() &&
                quote.shippingMethod()['carrier_code'] == 'myshipping'
            ) ? quote.shippingMethod()['method_code'] : null;
        })
    };

    return function (target) {
        return target.extend(mixin);
    };
});
