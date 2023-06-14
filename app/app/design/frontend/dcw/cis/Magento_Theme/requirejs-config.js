/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    paths: {
        slick: 'js/slick.min',
        MultiFile: 'js/jquery.MultiFile',
    },
    shim: {
        slick: {
            deps: ['jquery']
        },
        MultiFile: {
            deps: ['jquery']
        }
    },
    config: {
        mixins: {
            'mage/collapsible': {
                'js/mage/collapsible-mixin': true
            }
        }
    }
};