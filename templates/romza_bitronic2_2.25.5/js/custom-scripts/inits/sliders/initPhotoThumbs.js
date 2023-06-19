function initPhotoThumbs(target){
	$(target).find('.photo-thumbs:visible').each(function(){
		var _ = $(this);
		var dots = _.find('.carousel-dots');
		var img = _.siblings('.photo').children('a').children('img').eq(0),
			$hoverImg = _.siblings('.photo').children('a').find('.hover-img');
		if (typeof $hoverImg.data('save-src') == 'undefined') {
            $hoverImg.data('save-src', $hoverImg.attr('src'));
        }
        _.find('.lazy-sly:visible').lazyload();
		_.sly({
		    activateOn:     'mouseenter',  // Activate an item on this event. Can be: 'click', 'mouseenter', ...

		    pagesBar:       dots, // Selector or DOM element for pages bar container.
		    activatePageOn: 'click', // Event used to activate page. Can be: click, mouseenter, ...
		    pageBuilder:          // Page item generator.
		        function (index) {
		            return '<i class="carousel-dot"></i>';
		        },
		}).sly('on', 'active', function(e,i){
			var src = $(this.items[i].el).children('img').attr('data-medium-image');
            $hoverImg.length ? $hoverImg.attr('src',src) : img.attr('src', src);
            _.find('.lazy-sly:visible').lazyload();
		}).sly('reload');
		if ($hoverImg.length) {
            _.off('mouseleave').on('mouseleave',function () {
                $hoverImg.attr('src',  $hoverImg.data('save-src'));
            });
        }
	});
}