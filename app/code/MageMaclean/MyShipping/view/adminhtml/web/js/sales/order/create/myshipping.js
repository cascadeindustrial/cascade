define([
    'jquery',
    'Magento_Sales/order/create/scripts'
], function ($) {
    'use strict';

    AdminOrder.prototype.resetMyshipping = function() {
        var containers = $('.myshipping-container');
        containers.hide();
        $('.data', containers).attr('disabled', true);
    }

    AdminOrder.prototype.showMyshipping = function (accountId) {
        this.resetMyshipping();
        if(accountId == 'new') {
            var containerId = '#myshipping-new';
            var selectorId = '#s_method_myshipping_new';
        } else {
            var containerId = '#myshipping-account-' + accountId;
            var selectorId = '#s_method_myshipping_account_' + accountId;
        }

        $(selectorId).parent().append($(containerId).detach());
        $('.data', $(containerId)).attr('disabled', false);
        $(containerId).show();
    }

    AdminOrder.prototype.setMyshipping = function(method) {
        var data = {};
        data['order[shipping_method]'] = method;
        this.loadArea([
            'shipping_method',
            'totals',
            'billing_method'
        ], true, data);
    }
});
