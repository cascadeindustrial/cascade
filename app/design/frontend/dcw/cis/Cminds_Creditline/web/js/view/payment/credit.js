define([
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Cminds_Creditline/js/action/apply-credit',
    'Magento_Customer/js/model/customer'
], function (ko, Component, quote, priceUtils, applyCreditAction,customer) {
    'use strict';

    var amountUsed = ko.observable(window.checkoutConfig.creditConfig.amountUsed);
    var isApplied = ko.observable(amountUsed() > 0);
    var isLoading = ko.observable(false);
    var isAllowed = window.checkoutConfig.creditConfig.isAllowed;

    return Component.extend({
        defaults: {
            template: 'Cminds_Creditline/payment/credit'
        },
        isLoading: isLoading,
        isApplied: isApplied,
        isAllowed: isAllowed,

        amount: window.checkoutConfig.creditConfig.amount,

        initObservable: function () {
            this._super();

            return this;
        },

        formatBalanceAmount: function () {
            return priceUtils.formatPrice(this.amount, quote.getPriceFormat());
        },

        applyCredit: function () {
            if (applyCreditAction(true, isLoading)) {
                this.isApplied(true);
            }
        },

        isLoggedIn: function () {
            return customer.isLoggedIn();
        },

        cancelCredit: function () {
            applyCreditAction(false, isLoading);

            this.isApplied(false);
        }
    });
});
