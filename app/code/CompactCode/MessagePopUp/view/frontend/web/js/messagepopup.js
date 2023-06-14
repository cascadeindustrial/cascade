/*
 * Copyright (c) 2019.
 * Copyright Holder : CompactCode - CompactCode BvBa - Belgium
 * Copyright : Unless granted permission from CompactCode BvBa.
 * You can not distribute, reuse, edit, resell or sell this.
 */

define([
        'jquery'
    ], function ($) {
        $.widget('mage.messagepopup', {
            options: {
                messageClass: 'message',
                positionClass: 'cc-message-left-bottom-corner',
                deleteOption: 0,
                animationIn: 'bounceIn',
                animationOut: 'bounceOut',
                animationTime: 5,
                fixed_enable: 0,
                fixed_below: 0,
                fixed_class: 'cc-message-fixed-top',
                fixed_additional_classes: '',
                fixed_set: false,
                previous_positionclass : ''
            },

            _create: function () {
                $('.messageContainer').append($('.page.messages'));
                this._bindObserver('.messageContainer');
            },

            _bindObserver: function (target) {
                var self = this;
                var messageClass = this.options.messageClass;
                const observer = new MutationObserver(function (mutations) {
                    self.options.fixed_set = false;
                    mutations.forEach(function (mutation) {
                        if (mutation.addedNodes.length) {
                            if ($(mutation.addedNodes).hasClass(messageClass)) {
                                self._giveMessageClass($(mutation.addedNodes));
                            }
                        }
                    })
                });
                var config = {attributes: true, childList: true, subtree: true};
                observer.observe(document.querySelector(target), config);
            },
            _getPositionClass: function () {
                if (this.options.fixed_set) {
                    return this.options.fixed_class + ' ' + this.options.fixed_additional_classes;
                }

                if (this.options.fixed_enable) {
                    var messagessize = ($('.' + this.options.messageClass).size()) - 1;
                    if ((this.options.fixed_below !== 0 && messagessize < this.options.fixed_below) || this.options.fixed_below === '0') {
                        this.options.fixed_set = true;
                        return this.options.fixed_class + ' ' + this.options.fixed_additional_classes;
                    }
                }

                return this.options.positionClass;
            },
            _giveMessageClass: function (messageElement) {
                var positionClass = this._getPositionClass();
                var deleteOption = this.options.deleteOption;
                var messageAnimationIn = this.options.animationIn;
                var messageAnimationTime = (1000 * this.options.animationTime);
                var self = this;
                $('.messageContainer').removeClass(this.options.previous_positionclass);
                $('.messageContainer').addClass(positionClass);
                this.options.previous_positionclass = positionClass;
                messageElement.addClass(' animated ' + messageAnimationIn);
                if (deleteOption == 1) {
                    messageElement.append('<div class="cc-delete-message"> </div>');
                    messageElement.css('padding-right', '40px');
                    $('.cc-delete-message').on('click', function () {
                        self._removeMessage(messageElement, messageAnimationIn);
                    })
                } else {
                    var timeout = messageAnimationTime + (1000 * messageElement.index());
                    setTimeout(function () {
                        self._removeMessage(messageElement, messageAnimationIn);
                    }, timeout);
                }
            },

            _removeMessage: function (messageElement, messageAnimationIn) {
                var messageAnimationOut = this.options.animationOut;
                var animationEnd = 'animationend oAnimationEnd mozAnimationEnd webkitAnimationEnd';
                var element = $(messageElement);
                element.removeClass(messageAnimationIn);
                element.addClass(' animated ' + messageAnimationOut).one(animationEnd, function () {
                    element.removeClass(' animated ' + messageAnimationOut);
                    element.remove();
                });
            }
        });
        return $.mage.messagepopup;
    }
);