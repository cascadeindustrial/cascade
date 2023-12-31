/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

define(
    [
        'jquery',
        'Magento_Customer/js/customer-data'
    ],
    function (
        $,
        storage
    ) {
        'use strict';

        var cacheKey = 'checkout-data-quotation';

        /**
         * Get the data from the customer
         * @returns {*}
         */
        var getData = function () {
            var data = storage.get(cacheKey)();

            if ($.isEmptyObject(data)) {
                /** Init data */
                data = {
                    'quotation_guest_field_data': getCheckoutConfig("quotation_guest_field_data"),
                    'quotation_field_data': getCheckoutConfig("quotation_field_data"),
                    'quotation_product_data': getCheckoutConfig("quotation_product_data"),
                    'quotation_store_config_data': getCheckoutConfig("quotation_store_config_data")
                };
                saveData(data);
            }

            return data;
        };

        /**
         * Save the data to the customer
         * @param checkoutData
         */
        var saveData = function (checkoutData) {
            storage.set(cacheKey, checkoutData);
        };

        /**
         * Get data from checkout config
         *
         * @param data
         * @returns {*}
         */
        var getCheckoutConfig = function (data) {
            var checkoutConfigData = checkoutConfig[data];
            if (typeof checkoutConfigData === "undefined") {
                checkoutConfigData = {};
            }

            return checkoutConfigData;
        };

        /**
         * This model provides functions to read and write to the quotation checkout data
         */
        return {

            /**
             * Set guest field data
             * @param data
             */
            setQuotationGuestFieldsFromData: function (data) {
                var obj = getData();
                obj.quotation_guest_field_data = data;
                saveData(obj);
            },

            /**
             * Get guest field data
             * @returns {*}
             */
            getQuotationGuestFieldsFromData: function () {
                return getData().quotation_guest_field_data;
            },

            /**
             * Set quotation field data
             * @param data
             */
            setQuotationFieldsFromData: function (data) {
                var obj = getData();
                obj.quotation_field_data = data;
                saveData(obj);
            },

            /**
             * Get quotation field data
             * @returns {*}
             */
            getQuotationFieldsFromData: function () {
                return getData().quotation_field_data;
            },

            /**
             * Set quotation product data
             * @param data
             */
            setQuotationProductsFromData: function (data) {
                var obj = getData();
                obj.quotation_product_data = data;
                saveData(obj);
            },

            /**
             * Get quotation product data
             * @returns {*}
             */
            getQuotationProductsFromData: function () {
                return getData().quotation_product_data;
            },

            /**
             * Set Quotation config data
             * @param data
             */
            setQuotationConfigDataFromData: function (data) {
                var obj = getData();
                obj.quotation_store_config_data = data;
                saveData(obj);
            },

            /**
             * Get quotation config data
             * @returns {*}
             */
            getQuotationConfigDataFromData: function () {
                return getData().quotation_store_config_data;
            },

            /**
             * Set quotation customer data
             * @param data
             */
            setQuotationCustomerDataFromData: function (data) {
                var obj = getData();
                obj.quotationCustomerData = data;
                saveData(obj);
            },

            /**
             * Get quotation customer data
             * @returns {*}
             */
            getQuotationCustomerDataFromData: function () {
                return getData().quotationCustomerData;
            },

            /**
             * Set quotation guest checkout data
             * @param data
             */
            setQuotationGuestCheckoutFromData: function (data) {
                var obj = getData();
                obj.quotationGuestCheckout = data;
                saveData(obj);
            },

            /**
             * Get quotaiton guest checkout data
             * @returns {*}
             */
            getQuotationGuestCheckoutFromData: function () {
                return getData().quotationGuestCheckout;
            }
        }
    }
);
