define(
    [
        'jquery',
        'underscore',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'uiRegistry'
    ],function ($, _, Component, quote, uiRegistry, $t) {
    'use strict';

    var mixin = {
        initialize: function () {
            this._super();
        },

        isMyshipping: function () { return quote.shippingMethod() && quote.shippingMethod()['carrier_code'] == 'myshipping'; },

        getShippingMethodTitle: function () {
            if(!this.isCalculated()) return '';
            if(!this.isMyshipping()) return this._super();

            var method = quote.shippingMethod();
            var methodCode = method['method_code'];
            var myshippingInfo = uiRegistry.get('checkoutProvider').myshipping[methodCode];
            
            var shippingMethodTitle = '';
            if(methodCode == 'new') {
                if(!myshippingInfo['myshipping_courier_id']) return this._super();
                var courierOptions = method['extension_attributes']['myshipping_couriers'];
                if(!courierOptions) return quote.shippingMethod()['carrier_title'];

                var courier = courierOptions.find(function (c) { return c['value'] == myshippingInfo['myshipping_courier_id']; });
                shippingMethodTitle += courier ? courier['title'] : quote.shippingMethod()['carrier_title'];
                if(courier && myshippingInfo['myshipping_courier_method']) {
                    var courierMethod = courier['methods'].find(m => { return m['method_code'] == myshippingInfo['myshipping_courier_method']; });
                    shippingMethodTitle += courierMethod ? ' - ' + courierMethod['method_name'] : '';
                }
            } else {
                var methodOptions = method['extension_attributes']['myshipping_courier_methods'];
                var courierMethod = methodOptions.find(m => { return m['value'] == myshippingInfo['myshipping_courier_method']; });
                shippingMethodTitle += courierMethod ? courierMethod['courier_title'] + ' - ' + courierMethod['method_name'] : method['method_title'];
            }

            return shippingMethodTitle;
        }

    };

    return function (target) {
        return target.extend(mixin);
    };
 });
