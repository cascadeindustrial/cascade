define([
    'mage/utils/wrapper',
    'uiRegistry',
    'underscore',
    'MageMaclean_MyShipping/js/model/resource-url-manager',
    'Magento_Checkout/js/model/quote',
    'mage/storage',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Checkout/js/model/cart/cache',
    'Magento_Customer/js/customer-data'
], function (wrapper, uiRegistry, _, resourceUrlManager, quote, storage, totalsService, errorProcessor, cartCache, customerData) {
    'use strict';


    return function (target) {
        /**
         * Load data from server.
         *
         * @param {Object} address
         */
        var loadMyshippingFromServer = function (address) {
            var serviceUrl,
                payload;

            var methodCode = quote.shippingMethod()['method_code'];
            var myshippingInfo = uiRegistry.get('checkoutProvider').myshipping[methodCode];

            // Start loader for totals block
            totalsService.isLoading(true);
            serviceUrl = resourceUrlManager.getUrlForTotalsEstimationForNewAddress(quote);
            payload = {
                myshippingInformation: {
                    'shipping_address': _.pick(address, cartCache.requiredFields),
                    ...myshippingInfo
                }
            };

            if (quote.shippingMethod() && quote.shippingMethod()['method_code']) {
                payload.myshippingInformation['shipping_method_code'] = quote.shippingMethod()['method_code'];
                payload.myshippingInformation['shipping_carrier_code'] = quote.shippingMethod()['carrier_code'];
            }

            return storage.post(
                serviceUrl, JSON.stringify(payload), false
            ).done(function (result) {
                var data = {
                    totals: result,
                    address: address,
                    cartVersion: customerData.get('cart')()['data_id'],
                    shippingMethodCode: null,
                    shippingCarrierCode: null
                };

                if (quote.shippingMethod() && quote.shippingMethod()['method_code']) {
                    data.shippingMethodCode = quote.shippingMethod()['method_code'];
                    data.shippingCarrierCode = quote.shippingMethod()['carrier_code'];
                }

                quote.setTotals(result);
                cartCache.set('cart-data', data);
            }).fail(function (response) {
                errorProcessor.process(response);
            }).always(function () {
                // Stop loader for totals block
                totalsService.isLoading(false);
            });
        };

        var estimateTotals = wrapper.wrap(target.estimateTotals, function(originalAction, address) {
            if(!quote.shippingMethod() || quote.shippingMethod()['carrier_code'] != 'myshipping') return originalAction(address);

            var data = {
                shippingMethodCode: null,
                shippingCarrierCode: null
            };

            if (quote.shippingMethod() && quote.shippingMethod()['method_code']) {
                data.shippingMethodCode = quote.shippingMethod()['method_code'];
                data.shippingCarrierCode = quote.shippingMethod()['carrier_code'];
            }

            return loadMyshippingFromServer(address);
        });

        target.estimateTotals = estimateTotals;
        return target;
    };
});
