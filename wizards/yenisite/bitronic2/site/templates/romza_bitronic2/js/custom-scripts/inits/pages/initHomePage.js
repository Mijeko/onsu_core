b2.init.homePage = function(){
	b2.el.$coolSlider = $('#cool-slider');
	b2.el.$specialBlocks = $('#special-blocks');

	if ( b2.s.coolsliderEnabled && b2.el.$coolSlider.length && typeof UmCoolSlider === 'function'){
		b2.el.coolSlider = new UmCoolSlider(b2.el.$coolSlider);
	}
	$('.combo-link').children('a').on('click', function(e){
		e.stopPropagation();
	})
	if(typeof b2.el.$specialBlocks !='undefined' && b2.el.$specialBlocks.length > 0) {
		b2.el.specialBlocks = new UmComboBlocks(b2.el.$specialBlocks, {
			bp: 767,
			mode: b2.el.$specialBlocks.data('sb-mode'),
			defaultExpanded: ( b2.el.$specialBlocks.data('sb-mode-def-expanded') ) ? 'all' : (serverSettings.sbModeDefExpanded || 0),
			onOpen: function(target){
				setTimeout(function(){
					initPhotoThumbs(target);
				}, 10);
				target.trigger('b2SblockTabOpen');
				var slider = target.find('.special-blocks-carousel').data('UmSlider');
				if (typeof slider !== 'undefined' && !!slider){
					slider.updatePages();
				}
			}
		});
	}

    if ( $('[data-categories-enabled]').data('categories-enabled') === true ) {
        // init scroll category
        if (typeof initHCarousel === 'function') $('#categories').find('.scroll-slider-wrap').each(initHCarousel);
    }

    $('#categories .btn-expand').on('click', function(e){
        var section = $(this).closest('.slide');
        e.preventDefault();
        e.stopPropagation();

        if ( section.hasClass('expanded') ) {
            section.removeClass('expanded');
        } else {
            $(this).closest('.slides').find('.slide.expanded').removeClass('expanded');
            section.addClass('expanded');
        }
    });

    $('#categories .scroll-slider').sly('on', 'load moveStart', function(){
        $('#categories .slides').find('.slide.expanded').removeClass('expanded');
    });

    $('.combo-link a.i-number, .combo-header a.i-number').on('click', function(e){
		e.stopPropagation();
	});

	if (b2.s.wowEffect == 'Y') {
		new WOW({
			offset: 100,
			mobile: true
		}).init();
	}

	// b2.init.feedbackCarousel('.feedback'); BACK_END move to /components/yenisite.yandex_market_reviews_store/main_page/script.js
	initBigSlider();
	initSpecialBlocks(document);
	initPhotoThumbs(document);
    if (typeof initWidgetSlider != 'undefined'){
    	initWidgetSlider();
    }

	function loadHomePageChunks(){
		require([
			'libs/bootstrap/carousel.min',
			'init/sliders/initBrandsCarousel',
			'util/basket',
//			'init/initBuyClick',  BACK_END not need in ready project
			'init/initVideo'
		], function(){
//			b2.init.buyClick();   BACK_END not need in ready project
			b2.init.brandsCarousel();
		})
	}

	if (windowLoaded) loadHomePageChunks();
	else $(window).load(loadHomePageChunks);
}