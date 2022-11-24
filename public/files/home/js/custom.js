(function($) {
    'use strict';

    // Mean Menu JS
	$('.menu-bar').on('click', function () {
		$(this).siblings('ul.menu-mobile').fadeIn(200);
	});

	$('.close').on('click', function () {
		$(this).parent().fadeOut(400);
	});

	var nav = $('.top-bar');

    $(window).scroll(function () {
        if ($(this).scrollTop() > 125) {
            nav.addClass("fixed");
        } else {
            nav.removeClass("fixed");
        }
    });

	// ========================================


	$('#login').on('click', function (x) {
		x.stopPropagation();
		$('.box-login').fadeIn(200);
	});


	$(document).on('keyup', function (e) {

		if (e.keyCode === 27) {

			if ($('.box-login').css('display') === 'block') {

				$('.box-login').fadeOut(200);
				$('.box-info').fadeOut(200);

			}

		}

	});

	$('html').on('click', function () {

		if ($('.box-login').css('display') === 'block') {

			$('.box-login').fadeOut(200);
		}

		if ($('.box-info').css('display') === 'block') {

			$('.box-info').fadeOut(200);
		}

	});

	$('.box-login form, .box-info form').on('click', function (x) {

		x.stopPropagation();

	});


})(jQuery);
