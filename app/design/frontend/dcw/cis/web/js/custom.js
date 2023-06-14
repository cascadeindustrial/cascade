require(['jquery', 'slick', 'domReady!'], function($) {

	$(document).ready(function($) {

		//setTimeout(function(){ jQuery('.page.messages').insertAfter('.page-header'); }, 3000);
		function srcOpen(){
            setTimeout(function(){
				if(jQuery('.amsearch-products').hasClass('-waste')){
				    jQuery('.amsearch-products').parent().parent().closest('.amsearch-form-container').addClass('waste_open')
				}
				else{
				    jQuery('.amsearch-products').parent().parent().closest('.amsearch-form-container').removeClass('waste_open')
				}
			srcOpen()}, 0);
        }srcOpen()
        
		function sDisable(){
	    setTimeout(function(){ 
	    	var slidesx = jQuery('.amsearch-slider-block .amsearch-item.slick-slide').length
			//console.log('slidesx:' + slidesx)
			if(slidesx >= 1){
				jQuery('.amsearch-slider-block').addClass('slide1')
			}

			$('.slide1').slick({
			  dots: true,
			  infinite: false,
			  speed: 300,
			  slidesToShow: 2,
			  slidesToScroll: 1,
			  responsive: [
			    {
			      breakpoint: 1024,
			      settings: {
			        slidesToShow: 2,
			        slidesToScroll: 1,
			        infinite: true,
			        dots: true
			      }
			    },
			    {
			      breakpoint: 600,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1
			      }
			    },
			    {
			      breakpoint: 480,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1
			      }
			    }
			    // You can unslick at a given breakpoint now by adding:
			    // settings: "unslick"
			    // instead of a settings object
			  ]
			});

	    sDisable() }, 0);
	}sDisable()
		

		jQuery('.blog-pagination-item:contains("Previous Page")').addClass('prev');
		jQuery('.blog-pagination-item:contains("Next Page")').addClass('next');

		setTimeout(function(){ jQuery('.col-method:contains(" Use my own FedEx / UPS Account")').parent().addClass('use_my_own'); }, 1000);

		function abc(){
            setTimeout(function(){
                jQuery('.col.col-carrier').each(function(){
                    var x = jQuery(this).text();
                    if( x == "Use My Stored Account"){
                        jQuery(this).parent().find('.col-price').hide();   
                    }
                })
                jQuery('.col.col-carrier').each(function(){
                    var y = jQuery(this).text();
                    if( y == "Use My Shipping Account"){
                        jQuery(this).parent().find('.col-price').hide();   
                    }
                })

                abc();
            }, 3000);
        }
        abc();

        // jQuery('.tocart, .toquote, .towishlist').click(function(){
        //     setTimeout(function(){ jQuery('.page.messages').removeAttr("style"); }, 5000);
        // })
        // jQuery('.page.messages').click(function(){
        //     setTimeout(function(){ jQuery('.page.messages').hide(); }, 500);
        // })

        function pop_up_1(){
			setTimeout(function(){
				var ml = jQuery('.msg_cnt').text();
				//console.log(ml);
				if( ml.length > 1){
				    jQuery('.msg_cnt').parent().closest('.msg').addClass('active')
				}

				var clp1 = jQuery('.checkout-cart-index .message.message-notice.notice div').text();
				//console.log(clp1)
				if( clp1.length > 1){
				    jQuery('.checkout-cart-index .message.message-notice').addClass('active')
				}
				var clp2 = jQuery('.checkout-cart-index .message.message-success div').text();
				//console.log(clp2)
				if( clp2.length > 1){
				    jQuery('.checkout-cart-index .message.message-success').addClass('active')
				}

				jQuery('.page.messages').on('click', function(){
		            setTimeout(function(){ 
		            	jQuery('.msg .messages .msg_cnt').empty(); 
		            	jQuery('.page.messages .messages').addClass('active');
		            }, 500);
		            setTimeout(function(){ jQuery('.checkout-cart-index .page .messages').hide(); }, 500);
		        })
		        jQuery('.tocart, .toquote, .towishlist').on('click', function(){
		            setTimeout(function(){ jQuery('.page.messages .messages').removeClass('active'); }, 2000);
		        })

				pop_up_1()
			}, 100);
		}pop_up_1()

		setTimeout(function(){ jQuery('.checkout-cart-index .page .messages .message-notice').append('<a class="close_btn">x</a>'); }, 0);
		setTimeout(function(){ jQuery('.checkout-cart-index .page .messages .message-success').append('<a class="close_btn">x</a>'); }, 0);


		// jQuery('.tocart, .toquote, .towishlist').click(function(){
  //           setTimeout(function(){ jQuery('.page.messages').removeAttr("style"); }, 5000);
  //       })
  //       jQuery('.page.messages').click(function(){
  //           setTimeout(function(){ jQuery('.page.messages').hide(); }, 500);
  //       })

  //       setTimeout(function(){ jQuery('.checkout-cart-index .page .messages .message-notice').append('<a class="close_btn">x</a>'); }, 500);
		// setTimeout(function(){ jQuery('.checkout-cart-index .page .messages .message-success').append('<a class="close_btn">x</a>'); }, 500);
		// setTimeout(function(){ jQuery('.page.messages').addClass('act'); }, 5000);
		// setTimeout(function(){ jQuery('.account .page.messages').addClass('act'); }, 3000);
		// setTimeout(function(){ jQuery('.checkout-cart-index .page.messages').addClass('act'); }, 3000);
		// setTimeout(function(){ jQuery('.checkout-onepage-success .msg').addClass('act'); }, 2000);
		// jQuery('.checkout-onepage-success .msg').on('click', function(){
  //           setTimeout(function(){ jQuery('.checkout-onepage-success .msg').hide(); }, 500);
  //       })

		// setTimeout(function(){ jQuery('.page .message').append('<a class="close_btn">x</a>'); }, 5000);

		//Messages Sticky
		// setTimeout(function(){
		// 	var actualPosition = jQuery('.page.messages').offset().top;
		// 	jQuery(window).scroll(function (event) {
		// 	//console.log(jQuery('.page.messages').offset().top);
		// 	var x = jQuery('footer').offset().top;
		//   	if(jQuery(window).scrollTop() > x - 350) {
		//       jQuery('.page.messages').addClass('nonstiky');
		//     } else {
		//           jQuery('.page.messages').removeClass('nonstiky');}
		// 	});
		// }, 5000);
		// setTimeout(function(){
		// 	var actualPosition = jQuery('.page.messages').offset().top;
		// 	jQuery(window).scroll(function (event) {
		// 	//console.log(jQuery('.page.messages').offset().top);
		// 	var y = jQuery('.page-bottom').offset().top;
		//   	if(jQuery(window).scrollTop() > y - 350) {
		//       jQuery('.page.messages').addClass('nonstiky');
		//     } else {
		//           jQuery('.page.messages').removeClass('nonstiky');}
		// 	});
		// }, 5000);

		//$('.note').parent().parent().addClass('cst_width');

		jQuery("button.tocart").on('click', function() {    
			var at = jQuery(this).find('a');
			if(at.length == 1){
			    window.location.href = jQuery(this).find("a").attr("href");
			}
		})

		$(function() {
		  jQuery("button.tocart").removeAttr('disabled');
			jQuery("button.toquote").removeClass('disabled');
		});

		setTimeout(function(){
			jQuery("button.tocart").removeAttr('disabled');
			jQuery("button.toquote").removeClass('disabled');
		}, 0);	

		$('.product-options-wrapper.active .fieldset .field .label').removeAttr('title');

		jQuery('input.product-custom-option').each(function(){
		   jQuery(this).parent().closest(".field").addClass('custom_OPtions');
		});

		jQuery('textarea.product-custom-option').each(function(){
		   jQuery(this).parent().closest(".field").addClass('custom_OPtions');
		});

		jQuery('select.product-custom-option').each(function(){
		   jQuery(this).parent().closest(".field").addClass('custom_OPtions');
		});

		// if(jQuery('#expedited_shipping_price:radio').is(":checked")) {
  //           jQuery('.radiobtndelivery').find('.for-mobile').addClass("active");
  //           jQuery('.standard-delivery').find('.for-mobile').removeClass("active");
  //         }
  //         if(jQuery('#standard_shipping_price:radio').is(":checked")) {
  //             jQuery('.standard-delivery').find('.for-mobile').addClass("active");
  //             jQuery('.radiobtndelivery').find('.for-mobile').removeClass("active");
  //         }


		setTimeout(function(){
        if(jQuery('.edit_logged_in.simple_Product.logged_in input:radio').is(":checked")) {
           if(jQuery('#expedited_shipping_price:radio').is(":checked")) {
            jQuery('.radiobtndelivery').find('.for-mobile').addClass("active");
            jQuery('.standard-delivery').find('.for-mobile').removeClass("active");
          }
          if(jQuery('#standard_shipping_price:radio').is(":checked")) {
              jQuery('.standard-delivery').find('.for-mobile').addClass("active");
              jQuery('.radiobtndelivery').find('.for-mobile').removeClass("active");
          }
          if(jQuery('.radiobtndelivery .for-mobile').hasClass('active')){
              var ld1 = jQuery('.radiobtndelivery .for-mobile.active .delivery-date').html();
              console.log('ld1:' +ld1);
              // jQuery('.prices-ships #expdel').addClass('act');
              // jQuery('.prices-ships #standdel').removeClass('act');
              jQuery('.prices-ships #expdel span').html(ld1);
              jQuery('.prices-ships #expdel').css({'display':'block'});
              jQuery('.prices-ships #standdel').css({'display':'none'});
              var pc1 = jQuery('.for-mobile.active .discount-price .price').html();
              console.log('pc1:' +pc1);
              jQuery('.prices-ships .price-box .tax.weee .price').html(pc1);
              //jQuery('.prices-ships .price-box .special-price .price').html(pc1);
            }
            if(jQuery('.standard-delivery .for-mobile').hasClass('active')){
              var ld11 = jQuery('.standard-delivery .for-mobile.active .delivery-date').html();
              console.log('ld11:' +ld11);
              // jQuery('.prices-ships #expdel').removeClass('act');
              // jQuery('.prices-ships #standdel').addClass('act');
              jQuery('.prices-ships #expdel').css({'display':'none'});
              jQuery('.prices-ships #standdel').css({'display':'block'});
              jQuery('.prices-ships #standdel span').html(ld11);
              var pc11 = jQuery('.for-mobile.active .discount-price .price').html();
              console.log('pc11:' +pc11);
              jQuery('.prices-ships .price-box .tax.weee .price').html(pc11);
              //jQuery('.prices-ships .price-box .special-price .price').html(pc1);
            }
      }
      }, 3000);
		

		jQuery('.hor_layout .product-options-wrapper .custom_OPtions').hide();
        setTimeout(function(){
          jQuery('.product-options-wrapper.active .custom_OPtions').closest('#product_addtocart_form').addClass('act');
          jQuery('.product-options-wrapper.active .custom_OPtions').insertBefore('.newlayout');
          //jQuery('#product_addtocart_form').addClass('act');
          jQuery('#product_addtocart_form.act .custom_OPtions').show();
        }, 3000);

        jQuery('.product-cylindrical-proximitysensor-with-rigid.quotation-quote-configure .product-options-wrapper').insertBefore('.product.desc-read-more');
        jQuery('.product-ipsum-placeholder-text-goes-here1.quotation-quote-configure .product-options-wrapper').insertBefore('.product.desc-read-more');

		jQuery('.quotation-quote-configure .product-options-wrapper.active .custom_OPtions').insertBefore('.newlayout');
		setTimeout(function(){
			jQuery('.quotation-quote-configure.simple_Product .product-options-wrapper').insertBefore('.product.attribute.description');
		}, 500);
		setTimeout(function(){
			var x = jQuery('.downloads-index-index .shipping-details .price-wrapper .est-delivery').text().length
		  	console.log(x);
			if(x < 3 ){
				console.log('asas')
			    jQuery('.downloads-index-index .shipping-details .price-wrapper .est-delivery').addClass('none').css({'display':'none'});
			}
		}, 5000);

		jQuery('.hor_layout.config_pro_price.guest_usr .super-attribute-select').change(function(){
            // var x = jQuery('.standard-delivery.c-delivery .for-mobile .delivery-date').text();
            // console.log(x);
            jQuery('.prices-ships .ship_delivery').addClass('act');
        })
        if(jQuery('.hor_layout.config_pro_price').hasClass('edit_guest')){
        	jQuery('.prices-ships .ship_delivery').addClass('act');
        }

        jQuery('.ver_layout.config_pro_price .super-attribute-select').on('click', function(){
            jQuery('.prices-ships .ship_delivery').addClass('act');
            var x1 = jQuery('.prices-ships .ship_delivery').text().length
            console.log(x1);
          	if(x1 < 3 ){
            	console.log('asas')
              	jQuery('.prices-ships .ship_delivery').addClass('none')
          	}else{
				jQuery('.prices-ships .ship_delivery').removeClass('none')
		  	}
        })

        jQuery('.ver_layout.config_pro_price.guest_usr .super-attribute-select').change(function(){
            // var x = jQuery('.standard-delivery.c-delivery .for-mobile .delivery-date').text();
            // console.log(x);
            jQuery('.prices-ships .ship_delivery').addClass('act');
        })
        if(jQuery('.ver_layout.config_pro_price').hasClass('edit_guest')){
        	jQuery('.prices-ships .ship_delivery').addClass('act');
        }

        if(jQuery('.ver_layout.config_pro_price').hasClass('edit_logged_in')){
        	jQuery('.prices-ships .ship_delivery').addClass('act');
        }

        if(jQuery('.ship_delivery').hasClass('smp_none')){
        	jQuery(this).html(' ');
        }

		// jQuery('.super-attribute-select').change(function(){
  //           var x = jQuery('.standard-delivery.c-delivery .for-mobile .delivery-date').text();
  //           console.log(x);
  //           jQuery('.prices-ships .ship_delivery span').text(x);
  //       })

		//Alternate Backgroung
		// setTimeout(function(){
	 //        jQuery('#checkout-step-shipping_method .table-checkout-shipping-method tr:nth-child(4n+1)').css({'background-color': '#ffffff'});
		// 	jQuery('#checkout-step-shipping_method .table-checkout-shipping-method tr:nth-child(4n+2)').css({'background-color': '#ffffff'});
		// 	jQuery('#checkout-step-shipping_method .table-checkout-shipping-method tr:nth-child(4n+3)').css({'background-color': '#ccc' , 'width':'100%'});
		// 	jQuery('#checkout-step-shipping_method .table-checkout-shipping-method tr:nth-child(4n+4)').css({'background-color': '#ccc' , 'width':'100%'});
  //       }, 20000);

		

		if ($(window).width() <= 1024) {
			$('.col-sm-2 h4.link').click(function(){
				$(this).parent().find('.fot-toggle').slideToggle();
				$(this).parent().siblings().find('.fot-toggle').slideUp();
			})
			$('.column.main .mobile-filter').click(function(){
				$('.sidebar.sidebar-main').addClass('dcw_filter_active');
				$('body').addClass('filter_active');
			})
			$('.sidebar-main .filter_close').click(function(){
				$('.sidebar.sidebar-main').removeClass('dcw_filter_active');
				$('body').removeClass('filter_active');
			})
		};
		/******* wishlist mobile ******/
		if($(window).width() <= 767) {
			// $(".block.block-search").insertBefore( $(".section-item-content .navigation"));
			$('.nav-sections-items').append('<div class="mob_close"></div>');
			/** mobile menu header **/
			$('.mob_close').click(function(){
				$('.nav-toggle').trigger('click');
			});
			$('.nav-toggle').click(function(e){
				$('html').toggleClass('nav-before-open nav-open')
			});
			$('.navigation .level0 .fa').click(function(){
				$(this).parent().find('.mega_menu_section').slideToggle();
				$(this).parent().siblings().find('.mega_menu_section').slideUp();
				$(this).parent().find('.ambrands-list-popup').slideToggle();
			});
			$('.magamenu-left .level1 .fa').click(function(){
				$(this).parent().find('.megamenu-right').slideToggle();
				$(this).parent().siblings().find('.megamenu-right').slideUp();
			});
		}
		if($(window).width()<=768){
			$('.rsr-section-slider').slick({
				dots:true,
			    infinite:true,
			    speed:300,
			    speed:300,
			    dots:false,
			    prevArrow: false,
    			nextArrow: false,
			    mobileFirst:true,
			    slidesToShow:1,
			    slidesToScroll:1,
			    centerMode:true,
			    centerPadding:'60px'
			});
		}
	});
	$(document).ready(function($) {
		var header_heig = jQuery('.page-header').innerHeight();
		$('.page-wrapper').css({ 'padding-top': header_heig });
	});

	// blog
        if($(window).width() < 768) {
            $('.block-recent-posts').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite:false,
                 centerMode: true,
                 centerPadding: '130px',
				  dots: false,
				  arrows: false,
            });
        }
});