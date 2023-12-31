/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function(targetModule){

        var reloadPrice = targetModule.prototype._reloadPrice;
        var reloadPriceWrapper = wrapper.wrap(reloadPrice, function(original){
            //do extra stuff

            //call original method
            var result = original();

            //do extra stuff
            var simpleSku = this.options.spConfig.skus[this.simpleProduct];
            var simpleModelNo = this.options.spConfig.modelno[this.simpleProduct];
            var simpleStandard = this.options.spConfig.standard[this.simpleProduct];
            var simpleExpedited = this.options.spConfig.expedited[this.simpleProduct];
            var simplePrice = this.options.spConfig.price[this.simpleProduct];

            if(simplePrice != '') {
                $('.conf-delivery .discount-price .original-discount').html(simplePrice);
                $('.conf-delivery .discount-price .original-standard-discount').html(simplePrice);
            }
            if(simpleSku != '') {
                $('div.product-info-main .sku .value').html(simpleSku);
            }
            if(simpleModelNo != '') {
                $('div.product-info-main .sku .model').html(simpleModelNo);
            }
            if(simpleExpedited != '') {
                $('.conf-delivery .radiobtndelivery .delivery-date').html(simpleExpedited);
            }
            if(simpleStandard != '') {
                 $('.conf-delivery .standard-delivery .delivery-date').html(simpleStandard);
            }

            if(simpleStandard != '') {
                var stad = $('.config_pro_price.guest_usr .prices-ships .deltime-option').html(simpleStandard);
                console.log(simpleStandard);
            }

            // if(simpleStandard != '') {
            //     var stad = $('.config_pro_price.guest_usr .prices-ships .del_dates').html(simpleStandard);
            //     console.log('guest in')
            //     console.log(simpleStandard);
            // }

            if(simpleStandard != '') {
                var editstad = $('.config_pro_price.edit_guest .prices-ships .deltime-option').html(simpleStandard);
                console.log(simpleStandard);
            }

            if(simpleStandard != '') {
                var editstad1 = $('.ver_layout.config_pro_price.logged_in .prices-ships .deltime-option').removeClass('smp_none').html(simpleStandard);
                console.log('simpleStandard:' +simpleStandard);
            }else{
                $('.ver_layout.config_pro_price.logged_in .prices-ships .deltime-option').addClass('smp_none');
            }

            if(simpleStandard == '') {
                var editstad2 = $('.ver_layout.config_pro_price .prices-ships .deltime-option').addClass('smp_none').html();
                console.log('simpleStandard+None:' +simpleStandard);
            }else{
                $('.ver_layout.config_pro_price .prices-ships .deltime-option').removeClass('smp_none');
            }
            


            //return original value
            return result;
        });

        targetModule.prototype._reloadPrice = reloadPriceWrapper;
        return targetModule;
    };
});