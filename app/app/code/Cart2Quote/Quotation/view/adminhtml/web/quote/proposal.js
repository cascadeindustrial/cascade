/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

define(
    [
        'jquery',
        'uiComponent',
        'ko',
        'Cart2Quote_Quotation/quote/view/scripts'
    ],
    function (
        $,
        Component,
        ko
    ) {
        'use strict';

        return Component.extend({
            couponCode: ko.observable(quote.coupon.coupon_code),
            couponCodeAmount: ko.observable(quote.coupon.coupon_amount),
            couponCodeAsPercentage: ko.observable(quote.coupon.coupon_is_percentage),
            subtotalProposalAmount: ko.observable(null),
            subtotalProposalAsPercentage: ko.observable(false),
            defaults: {
                template: 'Cart2Quote_Quotation/quote/proposals',
                applyCouponCodeUrl: '',
                removeCouponCodeUrl: '',
                couponCodeAmountSelector: '#coupon-code-amount',
                applySubtotalProposalUrl: '',
                subtotalProposalAmountSelector: '#subtotal-proposal-amount'
            },
            validateBySelector: function (selector) {
                var valid = true;
                $(selector).each(function () {
                    if (!$.validator.validateElement(this)) {
                        valid = false;
                    }
                });

                return valid;
            },
            initSubscribe: function (element) {
                var selector = $(element).data('percentage-for');
                $(element).on('change', function (value) {
                    $(selector).toggleClass('validate-number-range number-range-0.01-100');
                });
            },
            applyCouponCode: function () {
                var self = this;
                if (!this.validateBySelector('fieldset#coupon-fieldset input')) {
                    return;
                }

                var amount = this.couponCodeAmount();
                var isPercentage = this.couponCodeAsPercentage();
                var code = this.couponCode();
                this.ajaxPost(
                    this.applyCouponCodeUrl,
                    {
                        amount: isPercentage ? 100 - amount : amount,
                        isPercentage: isPercentage,
                        code: code
                    },
                    function (response) {
                        if (response.couponCode) {
                            self.couponCode(response.couponCode);
                        }
                    }
                );
            },
            removeCouponCode: function () {
                this.couponCodeAmount(null);
                this.couponCodeAsPercentage(null);
                this.couponCode(null);
                this.ajaxPost(
                    this.removeCouponCodeUrl,
                    {}
                );
            },
            applySubtotalProposal: function () {
                if (!this.validateBySelector('fieldset#subtotal-proposal-fieldset input')) {
                    return;
                }

                var amount = this.subtotalProposalAmount();
                var isPercentage = this.subtotalProposalAsPercentage();
                this.ajaxPost(this.applySubtotalProposalUrl, {
                    amount: isPercentage ? 100 - amount : amount,
                    isPercentage: isPercentage
                });
            },
            resetSubtotalProposal: function () {
                this.subtotalProposalAmount(null);
                this.subtotalProposalAsPercentage(null);
                this.ajaxPost(
                    this.applySubtotalProposalUrl,
                    {
                        amount: 100,
                        isPercentage: true
                    }
                );
            },
            reloadAreas: function () {
                quote.loadArea(
                    ['shipping_method', 'billing_method', 'totals', 'items'],
                    true,
                    {reset_shipping: false}
                )
            },
            ajaxPost: function (url, data, success) {
                var self = this;
                data['form_key'] = window.FORM_KEY;
                return $.ajax({
                    url: url,
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    showLoader: true,
                    /** @inheritdoc */
                    success: function (response) {
                        if (success) {
                            success(response);
                        }
                        self.reloadAreas();
                    }
                });
            }
        });
    });
