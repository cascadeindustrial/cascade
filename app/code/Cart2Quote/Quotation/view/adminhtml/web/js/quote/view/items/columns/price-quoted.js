/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'jquery',
        'jquery/ui'
    ],
    function ($) {
        'use strict';

        $.widget(
            'mage.quotationPriceQuoted',
            {
                discountElementSelector: null,
                _create: function () {
                    this.init();
                    this.discountElementSelector = this.options.discountElementSelector;
                },

                init: function () {
                    var self = this;

                    $(self.element).change(function (event) {
                        self.priceListener(event);
                    });
                },

                priceListener: function (event) {
                    var costPrice = parseFloat($(event.target).data("costprice"));

                    if (costPrice > 0) { // build check for cost price value
                        var origPrice = parseFloat(event.target.defaultValue);
                        var newPrice = parseFloat(event.target.value.replace(",", "."));
                        this.checkCostPrice(newPrice, costPrice, origPrice, event.target);
                    }

                    this.percentageCheck(event);
                },

                checkCostPrice: function (newPrice, costPrice, origPrice, target) {
                    if (newPrice < costPrice) {
                        alert($.mage.__('Entered value lower than cost price'));
                        target.value = origPrice;
                    }
                },

                percentageCheck: function (event) {
                    var target = event.target;
                    if (parseFloat($(target).val()) !== parseFloat($(target).data("appliedPercentage"))) {
                        $('input[name="'+this.discountElementSelector+'"]').val('');
                    }
                }
            }
        );

        return $.mage.quotationPriceQuoted;
    }
);
