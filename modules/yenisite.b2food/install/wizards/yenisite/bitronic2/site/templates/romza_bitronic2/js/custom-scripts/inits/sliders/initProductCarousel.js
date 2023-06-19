function initProductCarousel(target){
	var h = false, thumbs, timeout;
	if (typeof b2.init == 'undefined'){
        b2.init = {};
	}

    b2.init.galleryCarouselUpdate = function(customTarget) {
		if (Modernizr.mq('(max-width: 991px)')) h = true;
		else h = false;

        target = customTarget ? customTarget : target;

		$(target).find('.gallery-carousel:visible').each(function() {
			var $gallery = $(this),
				$items = $gallery.find('.item'),
				$thumbs = $gallery.find('.thumbnails-frame.active, .thumbnails-wrap.active > .thumbnails-frame'),
				$thumbsNotActive = $gallery.find('.thumbnails-frame:not(.active), .thumbnails-wrap:not(.active) > .thumbnails-frame'),
				$prev = $thumbs.siblings('.prev'),
				$next = $thumbs.siblings('.next'),
				old = ($thumbs.data('sly')) ? $thumbs.data('sly').rel.activeItem : 0;

            $thumbsNotActive.sly('destroy');

            setTimeout(function(){
				$gallery.find('.lazy-sly:visible, .lazy:visible').lazyload({
					effect: "fadeIn",
					threshold: 400,
					failurelimit: 10000
				}).removeClass('lazy-sly');
			},400);

			if ( $thumbs.hasClass('bigimg-thumbs') ) h = true;
			if ( old === undefined ) old = 0;
			$thumbs.sly('destroy').sly({
				horizontal: h, // Switch to horizontal mode.
				scrollBy: 1,
				prev: $prev, // Selector or DOM element for "previous item" button.
				next: $next, // Selector or DOM element for "next item" button.
				startAt: old,
			}, {
				active: function(e, index) {
                    if (typeof $gallery.carousel == 'undefined') return;
					$gallery.carousel(index);
					$body.trigger('get_active_item_index',[index]);
					setTimeout(function(){
						$gallery.find('.item.next .lazy-sly').lazyload().removeClass('lazy-sly');
						$gallery.find('.item.prev .lazy-sly').lazyload().removeClass('lazy-sly');
						$gallery.find('.item.active .lazy-sly').lazyload().removeClass('lazy-sly');
					},600);

				},
				moveEnd: function(eventName) {
                    setTimeout(function(){
                        $gallery.find('.lazy-sly:visible, .lazy').lazyload();
                    }, 600);
				},
                load: function(eventName) {
                    $body.trigger('get_active_item_index',[this.rel.activeItem]);
				}
			}).sly('reload');
		});
	}

	function loadProductCarousel(){
		require([
			'libs/bootstrap/carousel.min',
			'init/initVideoInCarousel'
		], function(){
            b2.init.galleryCarouselUpdate();
		});

		$(window).on('resize', function(){
			clearTimeout(timeout);
			timeout = setTimeout(b2.init.galleryCarouselUpdate, 200);
		});
	}

	if (windowLoaded) loadProductCarousel();
	else $(window).load(loadProductCarousel);
}