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
        courierOptions: ko.observable(),
        methodOptions: ko.observable(),
        isUpdating: false,
        isReady: false,
        defaults: {
            listens: {
                myshippingCourierId: 'onUpdateCourier',
                myshippingAccount: 'onUpdateData',
                myshippingCourierMethod: 'onUpdateData',
                myshippingSave: 'onUpdateData',
            },
            imports: {
                myshippingCourierId: '${ $.provider }:${ $.customScope }.myshipping_courier_id',
                myshippingCourierMethod: '${ $.provider }:${ $.customScope }.myshipping_courier_method',
                myshippingAccount: '${ $.provider }:${ $.customScope }.myshipping_account',
                myshippingSave: '${ $.provider }:${ $.customScope }.myshipping_save'
            }
        },
        initialize: function () {
            this._super();
            quote.shippingMethod.subscribe(this.onShippingMethodUpdate, this);

            var method = quote.shippingMethod();
            this.onShippingMethodUpdate(method);
            this.isReady = true;
        },

        onShippingMethodUpdate: function (method) {
            if (method && method != undefined && method['carrier_code'] == 'myshipping' && method['method_code'] == 'new') {
                this.isUpdating = true;

                var myshippingCourierElem = this.elems().find(el => { return el.index == 'myshipping_courier_id'; });
                var myshippingCourierMethodElem = this.elems().find(el => { return el.index == 'myshipping_courier_method'; });
                if(myshippingCourierElem && myshippingCourierMethodElem) {
                    var courierOptions = method['extension_attributes']['myshipping_couriers'];
                    var origCourierId = myshippingCourierElem.value();
                    var origMethod = myshippingCourierMethodElem.value();
                    if(!courierOptions || courierOptions == undefined) courierOptions = [];
                    
                    this.courierOptions(courierOptions);
                    myshippingCourierElem.setOptions(courierOptions);
                    
                    var courier = this.courierOptions().find(c => { return c['value'] === origCourierId; });
                    if(courier && courier['methods'] && courier['methods'].length) {
                        var methodOptions = courier['methods'];
                        this.methodOptions(methodOptions);
                        myshippingCourierElem.value(origCourierId);
                        myshippingCourierMethodElem.setOptions(methodOptions);
                        var method = this.methodOptions().find(m => { return m['value'] === origMethod; });
                        if(method) {
                            myshippingCourierMethodElem.value(origMethod);
                        } else {
                            myshippingCourierMethodElem.value(null);
                        }
                    } else {
                        methodOptions = [];
                        this.methodOptions(methodOptions);
                        myshippingCourierElem.value(null);
                        myshippingCourierMethodElem.setOptions(methodOptions);
                        myshippingCourierMethodElem.value(null);
                    }
                }

                this.isUpdating = false;
                this.onUpdateData();
            }
        },

        onUpdateCourier: function (courierId) {
            if(this.isUpdating) return;

            var myshippingCourierMethodElem = this.elems().find(el => { return el.index == 'myshipping_courier_method'; });
            if(myshippingCourierMethodElem) {
                var origMethod = myshippingCourierMethodElem.value();
                var courier = this.courierOptions().find(c => { return c['value'] === courierId; });
                if(courier && courier['methods'] && courier['methods'].length) {
                    var methodOptions = courier['methods'];
                    this.methodOptions(methodOptions);
                    myshippingCourierMethodElem.setOptions(methodOptions);
                    var method = this.methodOptions().find(m => { return m['value'] === origMethod; });
                    if(method) {
                        myshippingCourierMethodElem.value(origMethod);
                    } else {
                        myshippingCourierMethodElem.value(null);
                    }
                } else {
                    myshippingCourierMethodElem.value(null);
                }
            }
        },

        onUpdateData: function () {
            if(!this.isReady || this.isUpdating) return;

            if(quote.shippingAddress() && this.myshippingCourierId && this.myshippingCourierMethod && !_.isEmpty(this.myshippingCourierMethod)) {
                setShippingInformationAction();
            }
        }
    });
});
