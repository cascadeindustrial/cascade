define(
    [
        'jquery',
        'Magento_Customer/js/model/customer'
    ],
    function ($, customer) {
        'use strict';

        return {
            /**
             * Validate Login Form on checkout if available
             *
             * @returns {Boolean}
             */
            validate: function () {
                var loginForm = 'form[data-role=email-with-possible-login]',
                    password = $(loginForm).find('#customer-password'),
                    passwordRepeat = $(loginForm).find('#password-confirmation'),
                    createAcc = window.checkoutConfig.quoteData.additional_options.create_account;

                if (customer.isLoggedIn() || createAcc !== '2') {
                    return true;
                }

                if (password.val()) {
                    if ($(password).valid() && $(passwordRepeat).valid()){
                       return $(loginForm).validation() && $(loginForm).validation('isValid');
                   }else{
                        return false;
                    }
                }

                return true;
            }
        };
    }
);
