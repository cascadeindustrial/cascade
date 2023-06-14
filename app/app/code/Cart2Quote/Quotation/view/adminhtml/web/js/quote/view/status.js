/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    "jquery"
], function ($) {
    'use strict';

    return function (config) {
        var selected = config.status_id;
        var statuses = config.data;
        for (var i = 0; i < statuses.length; ++i) {

            if (statuses[i].name == selected) {
                if (statuses[i].finalized) {
                    $(':button').prop('disabled', true);
                    $('select[id="quote_status"]').prop('disabled', true);
                    $("#change-shipping-method").attr('disabled', 'disabled');
                    $('button[id="quote-pdf"]').prop('disabled', false);
                    $('button[id="edit"]').prop('disabled', false);
                    break;
                } else if (statuses[i].locked) {
                    $(':button').prop('disabled', true);
                    $("#change-shipping-method").attr('disabled', 'disabled');
                    $('button[id="quote-pdf"]').prop('disabled', false);
                    $('button[id="edit"]').prop('disabled', false);
                    $('button[id="saveQuote"]').prop('disabled', false);
                    break;
                }
                break;
            }
        }

        $('select[id="quote_status"]').on('change', function () {
            if(isStatusLockedOrFinalized(selected) && isStatusLockedOrFinalized(this.value))
            {
                $('button[id="saveQuote"]').prop('disabled', false);
            }
            else if(isStatusLocked(selected) && !isStatusLockedOrFinalized(this.value))
            {
                $('button[id="saveQuote"]').prop('disabled', true);
            }
            else if(!isStatusLockedOrFinalized(selected))
            {
                $('button[id="saveQuote"]').prop('disabled', false);
            }
        });

        /**
         * Function to check if status is locked or finalized
         * @param value
         * @returns {boolean}
         */
        function isStatusLockedOrFinalized(value)
        {
            for (var i = 0; i < statuses.length; ++i) {
                if (statuses[i].name == value) {
                    if (statuses[i].locked || statuses[i].finalized) {
                        return true;
                        break;
                    }
                }
            }
            return false;
        }

        /**
         * Function to check if status is locked
         * @param value
         * @returns {boolean}
         */
        function isStatusLocked(value)
        {
            for (var i = 0; i < statuses.length; ++i) {
                if (statuses[i].name == value) {
                    if (statuses[i].locked) {
                        return true;
                        break;
                    }
                }
            }
            return false;
        }
    };
});
