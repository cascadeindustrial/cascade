define([
    'jquery',
    'uiRegistry'
], function ($, uiRegistry) {
    'use strict';
    return function (payloadExtender) {
        if(payloadExtender.addressInformation.shipping_carrier_code == 'myshipping') {
            var methodCode = payloadExtender.addressInformation.shipping_method_code;
            var myshippingInfo = uiRegistry.get('checkoutProvider').myshipping[methodCode];

            if(!payloadExtender.addressInformation.extension_attributes) {
                payloadExtender.addressInformation.extension_attributes = {};
            }

            payloadExtender.addressInformation.extension_attributes = {
                ...payloadExtender.addressInformation.extension_attributes,
                ...myshippingInfo
            };
        }
        return payloadExtender;
    };
});
