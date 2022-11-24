
(function ($) {
    'use strict';

    /* Custom Functions */
    jQuery.fn.exists = function () {
        return this.length > 0;
    };

    /* Preloader */
    $('.tm-preloader .tm-button').on('click', function () {
        $('.tm-preloader').fadeOut();
    });
    $(window).on('load', function () {
        $('.tm-preloader').fadeOut();
    });


    var dialia = {

        /* Scroll To Section */
        scrollToSection: function () {
            $('.hash-scroll-link').on('click', function (event) {
                event.preventDefault();
                var $anchor = $(this);
                var headerHeight = $('.tm-header-bottomside').height();
                $('html, body').stop().animate({
                    scrollTop: ($($anchor.attr('href')).offset().top - headerHeight)
                }, 1000);
            });
        },

        /* Meanmenu Activation */
        meanmenuActivation: function () {
            $('.tm-header-nav').meanmenu({
                meanMenuContainer: '.tm-mobilenav',
                meanScreenWidth: '991',
                meanMenuOpen: '<i class="ion-android-menu"></i>',
                meanMenuClose: '<i class="ion-android-close"></i>'
            });
        },

        /* Inline Background Image */
        dataBgImage: function () {
            $('[data-bgimage]').each(function () {
                var imageUrl = $(this).data('bgimage');
                $(this).css({
                    'background-image': 'url(' + imageUrl + ')'
                });
            });
        },

        /* Heroslider Height */
        herosliderHeight: function () {
            var headerHeight = $('.tm-header').height();
            $('.tm-heroslider-image').css({
                'min-height': 'calc(100vh - ' + headerHeight + 'px)'
            })
        },

        /* Slider Activations */
        sliderActivations: {

            /* Heroslider Activation */
            heroSliderActivation: function () {
                $('.tm-heroslider-images').slick({
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 10000,
                    speed: 1000,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: true,
                    adaptiveHeight: true,
                    responsive: [{
                        breakpoint: 1201,
                        settings: {
                            adaptiveHeight: true
                        }
                    }]
                });
            },

            /* Faq Image Slider */
            faqImageSliderActivation: function () {
                $('.tm-faq-images').slick({
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    speed: 1000,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: true,
                });
            },

            /* Products Slider */
            productsSliderActivation: function () {
                $('.tm-products-slider').slick({
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    speed: 500,
                    slidesToShow: 4,
                    slidesToScroll: 2,
                    arrows: true,
                    prevArrow: '<button class="tm-slider-arrows-prev"><i class="ion-ios-arrow-thin-left"></i></button>',
                    nextArrow: '<button class="tm-slider-arrows-next"><i class="ion-ios-arrow-thin-right"></i></button>',
                    dots: false,
                    responsive: [{
                            breakpoint: 1201,
                            settings: {
                                slidesToShow: 3,
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            }
                        }
                    ]
                });
            },

            /* Testimonial Slider */
            testimonialSliderActivation: function () {
                $('.tm-testimonial-slider').slick({
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    speed: 500,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    prevArrow: '<button class="tm-slider-arrows-prev"><i class="ion-ios-arrow-thin-left"></i></button>',
                    nextArrow: '<button class="tm-slider-arrows-next"><i class="ion-ios-arrow-thin-right"></i></button>',
                    dots: false,
                });
            },

            /* Team Slider */
            teamSliderActivation: function () {
                $('.tm-member-slider').slick({
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    speed: 500,
                    slidesToShow: 3,
                    slidesToScroll: 2,
                    arrows: false,
                    dots: true,
                    responsive: [{
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            }
                        }
                    ]
                });
            },

            /* Blog Slider */
            blogSliderActivation: function () {
                $('.blog-slider-active').slick({
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    speed: 500,
                    slidesToShow: 3,
                    slidesToScroll: 2,
                    arrows: false,
                    dots: true,
                    responsive: [{
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                dots: false
                            }
                        }
                    ]
                });
            },

            /* Blog Details Slider */
            blogDetailsSliderActivation: function () {
                $('.tm-blog-imageslider').slick({
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    speed: 500,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    prevArrow: '<button class="tm-slider-arrows-prev"><i class="ion-ios-arrow-thin-left"></i></button>',
                    nextArrow: '<button class="tm-slider-arrows-next"><i class="ion-ios-arrow-thin-right"></i></button>',
                    dots: false,
                    responsive: [{
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                dots: false
                            }
                        }
                    ]
                });
            },

            /* Brandlogo Slider */
            brandlogoSliderActivation: function () {
                $('.tm-brandlogo-slider').slick({
                    infinite: true,
                    autoplay: true,
                    speed: 2000,
                    autoplaySpeed: 2000,
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: false,
                    responsive: [{
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 4,
                                slidesToScroll: 1,
                            }
                        }, {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 420,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            },

            /* Product Details Gallery */
            productDetailsGallery: function () {
                $('.tm-prodetails-largeimages').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    loop: false,
                    asNavFor: '.tm-prodetails-thumbnails'
                });
                $('.tm-prodetails-thumbnails').slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    loop: false,
                    asNavFor: '.tm-prodetails-largeimages',
                    dots: false,
                    centerMode: true,
                    arrows: true,
                    prevArrow: '<button class="tm-slider-arrows-prev"><i class="ion-ios-arrow-thin-left"></i></button>',
                    nextArrow: '<button class="tm-slider-arrows-next"><i class="ion-ios-arrow-thin-right"></i></button>',
                    focusOnSelect: true
                });
            },

            /* Similliar Product Slider */
            similliarProductSlider: function () {
                $('.tm-similliar-products-slider').slick({
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: true,
                    prevArrow: '<button class="tm-slider-arrows-prev"><i class="ion-ios-arrow-thin-left"></i></button>',
                    nextArrow: '<button class="tm-slider-arrows-next"><i class="ion-ios-arrow-thin-right"></i></button>',
                    dots: false,
                    responsive: [{
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            },

            /* Slider Activations Initializer ( Just Remove a single line if you want to disable any slider ) */
            init: function () {
                dialia.sliderActivations.heroSliderActivation();
                dialia.sliderActivations.faqImageSliderActivation();
                dialia.sliderActivations.productsSliderActivation();
                dialia.sliderActivations.testimonialSliderActivation();
                dialia.sliderActivations.teamSliderActivation();
                dialia.sliderActivations.blogSliderActivation();
                dialia.sliderActivations.blogDetailsSliderActivation();
                dialia.sliderActivations.brandlogoSliderActivation();
                dialia.sliderActivations.productDetailsGallery();
                dialia.sliderActivations.similliarProductSlider();
            }

        },

        /* CounterUp Activation */
        counterupActivation: function () {
            if ($('.odometer').length) {
                $(window).on('scroll', function () {
                    function winScrollPosition() {
                        var scrollPos = $(window).scrollTop(),
                            winHeight = $(window).height();
                        var scrollPosition = Math.round(scrollPos + (winHeight / 1.2));
                        return scrollPosition;
                    }
                    var elemOffset = $('.odometer').offset().top;
                    if (elemOffset < winScrollPosition()) {

                        $('.odometer').each(function () {
                            $(this).html($(this).data('count-to'));
                        });
                    }
                });
            }
        },

        /* Countdown Activation */
        countdownActivation: function () {
            $('.tm-countdown').each(function () {
                var $this = $(this),
                    finalDate = $(this).data('countdown');
                $this.countdown(finalDate, function (event) {
                    $this.html(event.strftime(
                        '<div class="tm-countdown-pack tm-countdown-day"><h2 class="tm-countdown-count">%-D</h2><h6>Days</h6></div><div class="tm-countdown-pack tm-countdown-hour"><h2 class="tm-countdown-count">%-H</h2><h6>Hour</h6></div><div class="tm-countdown-pack tm-countdown-minutes"><h2 class="tm-countdown-count">%M</h2><h6>Min</h6></div><div class="tm-countdown-pack tm-countdown-seconds"><h2 class="tm-countdown-count">%S</h2><h6>Sec</h6></div>'));
                });
            });
        },

        /* Range Slider Active */
        rangeSlider: function () {
            $('.tm-rangeslider').nstSlider({
                'left_grip_selector': '.tm-rangeslider-leftgrip',
                'right_grip_selector': '.tm-rangeslider-rightgrip',
                'value_bar_selector': '.tm-rangeslider-bar',
                'value_changed_callback': function (cause, leftValue, rightValue) {
                    $(this).parent().find('.tm-rangeslider-leftlabel').text(leftValue);
                    $(this).parent().find('.tm-rangeslider-rightlabel').text(rightValue);
                }
            });
        },

        /* Nice Select Active */
        niceSelectActive: function () {
            $('select').niceSelect();
        },

        /* Product Quantitybox */
        productQuantityBox: function () {
            $('.tm-quantitybox').append('<div class="decrement-button tm-quantitybox-button">-</i></div><div class="increment-button tm-quantitybox-button">+</div>');
            $('.tm-quantitybox-button').on('click', function () {
                var button = $(this);
                var quantityOldValue = button.parent().find('input').val();
                var quantityNewVal;
                if (button.text() == "+") {
                    quantityNewVal = parseFloat(quantityOldValue) + 1;
                } else {
                    if (quantityOldValue > 1) {
                        quantityNewVal = parseFloat(quantityOldValue) - 1;
                    } else {
                        quantityNewVal = 1;
                    }
                }
                button.parent().find('input').val(quantityNewVal);
            });
        },

        /* Product Details Color & Size Active */
        productDetailsColorSize: function () {
            $('.tm-prodetails-color ul li, .tm-prodetails-size ul li').on('click', 'span', function (e) {
                e.preventDefault();
                $(this).parent('li').addClass('is-checked').siblings().removeClass('is-checked');
            });
        },

        /* Different Address Form */
        differentAddressFormToggle: function () {
            $('#billform-dirrentswitch').on('change', function () {
                if ($(this).is(':checked')) {
                    $('.tm-checkout-differentform').slideDown();
                } else {
                    $('.tm-checkout-differentform').slideUp();
                }
            });
        },

        /* Checkout Payment Method */
        checkoutPaymentMethod: function () {
            $('.tm-checkout-payment input[type="radio"]').each(function () {
                if ($(this).is(':checked')) {
                    $(this).siblings('.tm-checkout-payment-content').slideDown();
                }
                $(this).siblings('label').on('click', function () {
                    $('.tm-checkout-payment input[type="radio"]').prop('checked', false).siblings('.tm-checkout-payment-content').slideUp();
                    $(this).prop('checked', true).siblings('.tm-checkout-payment-content').slideDown();
                });
            });
        },

        /* Ajax Mailchimp */
        ajaxMailchimp: function () {
            $('#tm-mailchimp-form').ajaxChimp({
                language: 'en',
                callback: mailChimpResponse,
                // ADD YOUR MAILCHIMP URL BELOW HERE!
                url: 'YOUR_MAILCHIMP_URL_HERE'
            });

            function mailChimpResponse(resp) {
                if (resp.result === 'success') {
                    $('.tm-mailchimp-success').html('' + resp.msg).fadeIn(900);
                    $('.tm-mailchimp-error').fadeOut(400);
                } else if (resp.result === 'error') {
                    $('.tm-mailchimp-error').html('' + resp.msg).fadeIn(900);
                }
            }
        },


        /* Menu Overflow */
        menuOverflow: function () {
            $('.tm-navigation ul li').on('mouseenter mouseleave', function (e) {
                if ($('ul', this).length) {
                    var listElem = $('ul:first', this);
                    var listElemOffset = listElem.offset();
                    var leftValue = listElemOffset.left - $('body').offset().left;
                    var widthValue = listElem.width();

                    if (listElem.find('ul').length) {
                        widthValue = listElem.width() * 2;
                    }

                    var docW = $('body').width();
                    var isEntirelyVisible = (leftValue + widthValue <= docW);

                    if (!isEntirelyVisible) {
                        $(this).addClass('overflow-element');
                    } else {
                        $(this).removeClass('overflow-element');
                    }
                }
            });
        },

        /* Dropdown Children */
        dropdownHasChildren: function () {
            $('.tm-navigation-dropdown ul li').each(function () {
                if ($(this).children('ul').length) {
                    $(this).addClass('has-child');
                }
            });
        },

        /* Datepicker Active */
        datepickerActivation: function () {
            $('[data-toggle="datepicker"]').datepicker();
        },

        /* Login Password Shower */
        loginPassShower: function () {
            $('input[name="register-pass-show"]').on('change', function () {
                var type = $('input[name="register-pass"]').attr('type');
                if (type == 'password') {
                    $('input[name="register-pass"]').attr('type', 'text');
                } else {
                    $('input[name="register-pass"]').attr('type', 'password');
                }
            });
        },

        /* Search Form */
        searchFormToogle: function () {
            $('.tm-header-searchtrigger').on('click', function () {
                $('.tm-searchform').toggleClass('is-opened');
                $('.tm-searchform-close').on('click', function () {
                    $(this).parents('.tm-searchform').removeClass('is-opened');
                });
                $('.tm-searchform').on('click', function (e) {
                    if ($(e.target).hasClass('tm-searchform is-opened')) {
                        $(this).removeClass('is-opened');
                    }
                });
            });
        },

        /* Sticky Header */
        stickyHeader: function () {
            var headerHeight = $('.tm-header').height();
            $('.tm-heroslider, .tm-breadcrumb-area').css({
                marginTop: headerHeight + 'px'
            });

            $(window).on('scroll', function () {
                var scrollPos = $(this).scrollTop();
                if (scrollPos > 250) {
                    $('.tm-header').addClass('is-sticky');
                } else {
                    $('.tm-header').removeClass('is-sticky');
                }
            });
        },

        /* Scrollspy Active */
//        scrollSpyActive: function () {
//            var headerMiddleHeight = $('.tm-header-bottomside').height();
//            $('.tm-header-nav').scrollspy({
//                offset: -1 * (headerMiddleHeight - 1),
//                activeClass: 'current',
//                animate: 'swing',
//            });
//        },


        /* Fancybox Active */
        fancyboxSlick: function () {
            $('[data-fancybox]').fancybox({
                beforeShow: function () {
                    $('.tm-product-quickview .tm-prodetails-largeimages').slick('slickNext');
                }
            });
        },


        /* Scroll To Top */
        scrollToTop: function () {
            var trigger = $('#back-top-top');
            trigger.css({
                'visibility': 'hidden',
                'opacity': 0
            });

            trigger.on('click', function () {
                $('html, body').stop().animate({
                    scrollTop: 0
                }, 1000);
            });

            $(window).on('scroll', function () {
                var scrollPos = $(window).scrollTop();
                var winHeight = $(window).height();
                if (scrollPos > winHeight) {
                    trigger.css({
                        'visibility': 'visible',
                        'opacity': 1
                    })
                } else {
                    trigger.css({
                        'visibility': 'hidden',
                        'opacity': 0
                    });
                }
            });

        },


        /* Product Rating Input */
        productRatingInput: function () {
            $('.tm-ratingbox-input').each(function () {
                $(this).find('span').on('mouseenter', function () {
                    $('.tm-ratingbox-input span').addClass('is-active');
                    $(this).nextAll('span').removeClass('is-active');
                });

            });
        },

        /* Ajax Contact Form */
        ajaxContactForm: function () {
            $(function () {
                // Get the form.
                var form = $('#tm-contactform');

                // Get the messages div.
                var formMessages = $('.form-messages');

                // TODO: The rest of the code will go here...

                // Set up an event listener for the contact form.
                $(form).submit(function (event) {
                    // Stop the browser from submitting the form.
                    event.preventDefault();

                    // Serialize the form data.
                    var formData = $(form).serialize();

                    // Submit the form using AJAX.
                    $.ajax({
                            type: 'POST',
                            url: $(form).attr('action'),
                            data: formData
                        })


                        .done(function (response) {
                            // Make sure that the formMessages div has the 'success' class.
                            $(formMessages).removeClass('error');
                            $(formMessages).addClass('success');

                            // Set the message text.
                            $(formMessages).text(response);

                            // Clear the form.
                            $('#tm-contactform input:not([type="submit"]), #tm-contactform textarea').val('');
                        })

                        .fail(function (data) {
                            // Make sure that the formMessages div has the 'error' class.
                            $(formMessages).removeClass('success');
                            $(formMessages).addClass('error');

                            // Set the message text.
                            if (data.responseText !== '') {
                                $(formMessages).text(data.responseText);
                            } else {
                                $(formMessages).text('Oops! An error occured and your message could not be sent.');
                            }
                        });
                });
            });
        },

        /* Ajax Appointment Form */
        ajaxAppointmentForm: function () {
            $(function () {
                // Get the form.
                var form = $('#tm-appointment-form');

                // Get the messages div.
                var formMessages = $('.appointment-messages');

                // TODO: The rest of the code will go here...

                // Set up an event listener for the contact form.
                $(form).submit(function (event) {
                    // Stop the browser from submitting the form.
                    event.preventDefault();

                    // Serialize the form data.
                    var formData = $(form).serialize();

                    // Submit the form using AJAX.
                    $.ajax({
                            type: 'POST',
                            url: $(form).attr('action'),
                            data: formData
                        })


                        .done(function (response) {
                            // Make sure that the formMessages div has the 'success' class.
                            $(formMessages).removeClass('error');
                            $(formMessages).addClass('success');

                            // Set the message text.
                            $(formMessages).text(response);

                            // Clear the form.
                            $('#tm-appointment-form input:not([type="submit"]),  #tm-appointment-form select, #tm-appointment-form textarea').val('');
                        })

                        .fail(function (data) {
                            // Make sure that the formMessages div has the 'error' class.
                            $(formMessages).removeClass('success');
                            $(formMessages).addClass('error');

                            // Set the message text.
                            if (data.responseText !== '') {
                                $(formMessages).text(data.responseText);
                            } else {
                                $(formMessages).text('Oops! An error occured and your message could not be sent.');
                            }
                        });
                });
            });
        },

        /* Block Revealer */
        blockRevealer: function () {
            var controller = new ScrollMagic.Controller();
            $('.tm-revealblock').each(function (i) {
                new ScrollMagic.Scene({
                    triggerElement: this,
                    triggerHook: 0.8,
                    reverse: false,
                })
                .setClassToggle(this, 'reveal-close')
                .addTo(controller);
            });
        },

        /* Initializer */
        init: function () {
            dialia.scrollToSection();
            dialia.meanmenuActivation();
            dialia.dataBgImage();
            dialia.herosliderHeight();
            dialia.sliderActivations.init();
            dialia.counterupActivation();
            dialia.countdownActivation();
            dialia.rangeSlider();
            dialia.niceSelectActive();
            dialia.productQuantityBox();
            dialia.productDetailsColorSize();
            dialia.productRatingInput();
            dialia.differentAddressFormToggle();
            dialia.checkoutPaymentMethod();
            dialia.ajaxMailchimp();
            dialia.menuOverflow();
            dialia.dropdownHasChildren();
            dialia.datepickerActivation();
            dialia.loginPassShower();
            dialia.searchFormToogle();
            dialia.stickyHeader();
//            dialia.scrollSpyActive();
            dialia.fancyboxSlick();
            dialia.scrollToTop();
            dialia.productRatingInput();
            dialia.ajaxContactForm();
            dialia.ajaxAppointmentForm();
            dialia.blockRevealer();
        }
    };

    dialia.init();


})(jQuery);