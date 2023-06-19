$.fn.RMZinfScroll = function (callback, options) {
	if (!this.data('is_scrolling')) {
		options.paddingTop = options.paddingTop || 0;
		var $this = this,
			padding_top = options.paddingTop,
			offset_top = $this.offset().top + padding_top,
			block = false;
		$this.data('is_scrolling', true);
		$(window).off('scroll').on('scroll',function () {
			var isVisible = ($(window).scrollTop() + $(window).height() >= offset_top);
			if (isVisible && !block) {
				block = true;
				$.when(callback()).then(function () {
					offset_top = $this.offset().top + padding_top;
					block = false;
				});
			}
		});
	}
};