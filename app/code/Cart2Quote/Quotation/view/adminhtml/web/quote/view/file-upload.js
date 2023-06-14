/*
 * Copyright (c) 2021. Cart2Quote B.V. All rights reserved.
 * See COPYING.txt for license details.
 */

var newUploadRow;
var upload;
var removeFile;
define([
    'jquery',
    'Magento_Ui/js/modal/confirm'
], function ($, confirm) {
    'use strict';

    return function () {
        var fileRowNumber = 1;

        /**
         * New file upload row
         *
         */
        newUploadRow = function () {
            $('.select-file:first-child').clone().insertAfter('.select-file:last').addClass('new-upload');
            $('.new-upload:last').find('input').val('');
            fileRowNumber = (fileRowNumber + 1);
        };

        upload  = function (uploadActionUrl) {
            var message = 'Are you sure you want to upload these files?';

            confirm({
                content: $.mage.__(message),
                actions: {
                    confirm: function () {
                        $('#file_upload').attr('action', uploadActionUrl);
                        $('#file_upload').submit();
                    }
                }
            });
        }

        removeFile = function (removeActionUrl) {
            var message = 'Are you sure you want to remove this file?';

            confirm({
                content: $.mage.__(message),
                actions: {
                    confirm: function () {
                        $('#file_upload').attr('action', removeActionUrl);
                        $('#file_upload').submit();
                    }
                }
            });
        }
    };
});
