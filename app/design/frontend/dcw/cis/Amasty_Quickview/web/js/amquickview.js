define([
    "jquery",
    "Magento_Customer/js/customer-data",
    'Magento_Ui/js/modal/modal'
], function ($, customerData) {

    $.widget('mage.amQuickview', {
        options: {},
        customerData: customerData,

        _create: function (options) {
            var self = this;

            if (this.checkFancybox()  || window.self !== window.top) {
                return;
            }

            self.element.on(
                {
                    'mouseenter': function() {self.showLen(this)},
                    'mouseleave': function() {self.hideLen(this)},
                    'touchstart': function() {self.showLen(this)},
                    'touchend'  : function(){
                        var event = this;
                        setTimeout(function(){
                            self.hideLen(event);
                        }, 2000);
                    }
                }
            );
        },

        createHover : function(element) {
            var self = this;
            var productId = this.getProductId(element);

            if (!productId) {
                console.log("We didn't find price block with product id");
                return false;
            }

            var hover = $('<span>', {
                class : 'amquickview-hover'
            });
            hover.attr("style", this.options['css']);

            element.css({
                position : 'relative'
            });

            var link = $('<a />', {
                class : 'amquickview-link',
                id : 'amquickview-link-' + productId
            });
            link.attr('data-product-id', productId);
            link.html(this.options['text']);

            hover.appendTo(element).hide();
            link.click(function(event) {
                self.showPopup(event)
            });
            link.appendTo(hover);

            return hover;
        },

        showPopup : function(e) {
            if (!$.fancybox) {
                $.fancybox = jQuery.fancybox;
            }
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            var element = $(e.target);

            if (undefined == this.options['url']) {
                return false;
            }

            if (element.hasClass('am-quickview-icon')) {
                element = element.parent();
            }

            var productId = element.attr('data-product-id');
            if (!productId) return;

            var url = this.options['url'] +"?id=" + productId;
            var fancyboxVersion = jQuery.fancybox ? parseInt(jQuery.fancybox.version.split('.')[0]) : 0;

            switch (fancyboxVersion) {
                case 2:
                    $.fancybox.open({
                        customerData: this.customerData,
                        padding :0,
                        href    : url,
                        type    : 'iframe',
                        opts   : {
                            iframe : {
                                css : {
                                    width : '800px'
                                }
                            },
                            smallBtn : true,
                            toolbar : false,
                            baseClass : 'amquickview-fancybox-wrapper',
                            afterClose : function() {
                                var sections = ['cart'];
                                this.customerData.reload(sections);
                            }
                        }
                    });
                    break;
                case 3:
                default:
                    $.fancybox.open({
                        customerData: this.customerData,
                        src    : url,
                        type   : 'iframe',
                        opts   : {
                            iframe : {
                                css : {
                                    width : '800px'
                                }
                            },
                            smallBtn : true,
                            baseClass : 'amquickview-fancybox-wrapper',
                            toolbar : false,
                            afterClose : function() {
                                var sections = ['cart'];
                                this.customerData.reload(sections);
                            }
                        }
                    });
                    break;
            }

            this.hideLen(element.parent());
            return false;
        },

        showLen : function(element) {
            if (!element) return false;
            element = $(element);
            var parent = $(element).find(this.options.selectors.productContainer);

            if (parent.length != 0) {
                element = parent;
            }
            var hover = element.find('.amquickview-hover').first();

            if (!hover.length) {
                hover = this.createHover(element);
            }

            if (hover) {
                hover.show();
            }
        },

        checkFancybox : function() {
            var fancyboxVersion = jQuery.fancybox ? parseInt(jQuery.fancybox.version.split('.')[0]) : 0;

            if (fancyboxVersion == 0) {
                console.error('Fancybox library is not loaded');
                return true;
            }

            return false;
        },

        hideLen : function(element) {
            if (! element) return false;

            var hover = $(element).find('.amquickview-hover').first();
            if (hover.length) {
                hover.hide();
            }
        },

        getProductId: function(parent) {
            var parentElement = parent.closest(this.options.selectors.productImageContainer);

            if (parentElement.length != 0) {
                parent = parentElement;
            }

            var element = parent.find('[data-product-id]');

            if (element[0]) {
                var id = $(element[0]).attr('data-product-id');
                id = parseInt(id);
                if (id) {
                    return id;
                }
            }

            element = parent.find('[data-post]');

            if (element[0]) {
                var dataPost = $(element[0]).attr('data-post');
                dataPost = $.parseJSON(dataPost);
                if (dataPost && dataPost.data && dataPost.data.product) {
                    id = parseInt(dataPost.data.product);
                    return id;
                }
            }

            return false;
        }
    });

    return $.mage.amQuickview;
});
