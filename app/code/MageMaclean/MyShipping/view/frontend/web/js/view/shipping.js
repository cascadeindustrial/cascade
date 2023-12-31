define(
    [
        'jquery',
        'underscore',
        'ko',
        'Magento_Checkout/js/model/quote',
        'mage/validation'
    ], 
    function ($, _, ko, quote) {
    'use strict';

    var mixin = {
        initObservable: function () {
            this._super();

            this.selectedMethod = ko.computed(function() {
                var method = quote.shippingMethod();
                return method != null ? method['method_code'] + '_' + method['carrier_code'] : null;
            }, this);

            return this;
        },
        validateShippingInformation: function () {
            var result = this._super();
            if(!result) return result;

            if(quote.shippingMethod()['carrier_code'] == 'myshipping') {
                this.triggerMyshippingValidateEvent();

                if(this.source.get('params.invalid'))
                    result = false;
            }

            return result;
        },
        triggerMyshippingValidateEvent: function () {
            this.source.set('params.invalid', false);
            this.source.trigger('myshipping.' + quote.shippingMethod()['method_code'] + '.data.validate');
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
 });
