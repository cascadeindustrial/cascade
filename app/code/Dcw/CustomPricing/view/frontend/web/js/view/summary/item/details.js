/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
 
define(
    [
        'uiComponent'
    ],
    function (Component) {
        "use strict";
        var quoteItemData = window.checkoutConfig.quoteItemData;
        return Component.extend({
        defaults: {
            template: 'Dcw_CustomPricing/summary/item/details'
        },
        quoteItemData: quoteItemData,
        getValue: function(quoteItem) {
            return quoteItem.name;
        },
        getShippingOption: function(quoteItem) {
            var item = this.getItem(quoteItem.item_id);
           // alert(item.sku);
            return item.shipping_option;
        },
        getStandardDelivery: function(quoteItem) {
            var item = this.getItem(quoteItem.item_id);
           // alert(item.sku);
            return item.standard_delivery_time;
        },
        getExpedictedDelivery: function(quoteItem) {
            var item = this.getItem(quoteItem.item_id);
           // alert(item.sku);
            return item.expedited_delivery_time;
        },
        getItem: function(item_id) {
            var itemElement = null;
            _.each(this.quoteItemData, function(element, index) {
                if(element.item_id == item_id) {
                    itemElement = element;
                }
            })
            return itemElement;
        }
    });
});
