/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

define(
    [
        'jquery',
        'jquery/ui',
        'uiComponent',
        'ko'
    ],
    function ($, ui, Component, ko) {
        'use strict';

        /**
         * This is the js model that is used for the grid in the Cart2Quote settings.
         */
        return Component.extend({
            defaults: {
                template: 'Cart2Quote_Quotation/system/config/quote/items'
            },

            /**
             * Address data as array of JSON objects
             */
            itemsFields: ko.observableArray(),

            /**
             * Complete parsed data
             */
            itemsData: null,

            /**
             * Config field name
             */
            name: null,

            /**
             * Config field label
             */
            label: null,

            /**
             * Config field Html ID
             */
            htmlId: null,

            /**
             * Config field saved data
             */
            initFieldValue: null,

            /**
             * Observable field container: mandatory for a observable array with observable vars
             *
             * @param label
             * @param name
             * @param visibility
             */
            observableField: function (label, name, visibility) {
                this.label = ko.observable(label);
                this.name = ko.observable(name);
                this.visibility = ko.observable(visibility);
            },

            /**
             * @return {exports.initObservable}
             */
            initObservable: function () {
                var self = this;
                this.initFields(this.data);
                var initialData = this.initFieldValue;

                if (initialData === null) {
                    initialData = this.getDefaultValues();
                } else {
                    //merge new elements in existing settings
                    if (initialData.length !== this.getDefaultValues().length) {
                        $.each(this.getDefaultValues(), function (defaultIndex, defaultValue) {
                            var found = false;
                            var defaultName = defaultValue.name;
                            $.each(initialData, function (index, value) {
                                var name = value.name;
                                if (name === defaultName) {
                                    found = true;
                                    return false; //break
                                }
                            });

                            //add new element to current settings
                            if (found === false) {
                                initialData.push(defaultValue);
                            }
                        });
                    }
                }

                this.itemsFields = ko.observableArray(
                    ko.utils.arrayMap(initialData, function (field) {
                        return new self.observableField(
                            field.label,
                            field.name,
                            field.visibility,
                        );
                    })
                );

                return this;
            },

            /**
             * Get default field values
             *
             * @returns {*[]}
             */
            getDefaultValues: function () {
                var items = this.items;
                var defaultValues = [];
                for (var i = 0; i < items.length; ++i) {
                    defaultValues.push(
                        {
                            label: items[i].label,
                            name: items[i].value,
                            visibility: true,
                        });
                }

                return defaultValues;
            },

            /**
             * Initialize the fields
             *
             * @param data
             */
            initFields: function (data) {
                this.itemsData = ko.utils.parseJson(data);
                this.name = this.itemsData.field.name;
                this.label = this.itemsData.field.label;
                this.items = this.itemsData.field.items;
                this.htmlId = this.itemsData.field.htmlId;
                this.initFieldValue = ko.utils.parseJson(this.itemsData.field.fieldValue);
            },

            /**
             * Get the status fields
             * @returns {*}
             */
            getItemsFields: function () {
                var self = this;
                return self.itemsFields();
            }
        });
    }
);
