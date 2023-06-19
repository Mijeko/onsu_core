function initHCarousel(){
	var $t = $(this);
	var $frame = $t.find('.frame');
	var $wrap = $frame.closest('.scroll-slider-wrap');
	if ( $t.data('sly-inited') ){
		$frame.sly('reload');
		return;
	}
	var scroll = $t.find('.sly-scroll');
	var prev = $t.find('.prev');
	var next = $t.find('.next');

	$frame.sly({
	   scrollBar:     scroll.get(0), // Selector or DOM element for scrollbar container.
	   prevPage: prev, // Selector or DOM element for "previous page" button.
	   nextPage: next, // Selector or DOM element for "next page" button.

	}).sly('on', 'load', function(){
		// check if there is nowhere to scroll
		$t.data('sly-inited', true);
		if (this.pos.start === this.pos.end) {
			$wrap.addClass('no-scroll');
		} else {
			$wrap.removeClass('no-scroll');
		}
	}).sly('reload');
}

function initHorizontalCarousels(target){
	var wraps = $(target).find('.scroll-slider-wrap');
	wraps.filter(':visible').each(initHCarousel);
	wraps.find('a, button:not(.ctrl-arrow)').on('click', function(e){
		e.stopPropagation();
	})
}