(function($){
"use strict";

$.fn.initHeightCollapse = function(){
	return this.each(function init(){
		var $t = $(this),
			target = $t.data('target') || $t.attr('href'),
			$target = $(target);

		function update(){
			// +5 is just an overhead for shadows and such
			if ($target.data('start-height') + 5 <= $target.get(0).scrollHeight) {
				$t.removeClass('hide');
			} else {
				$t.addClass('hide');
			}
			if (!$t.hasClass('collapsed')) {
				$target.css('max-height', $target.get(0).scrollHeight);
			}
		}

		if (!$target.length) return;
		
		$t.off('click.initHeightCollapse')
		.on('click.initHeightCollapse', function(e){
			if ($t.hasClass('collapsed')){
				$t.removeClass('collapsed');
				$target.css('max-height', $target.get(0).scrollHeight);
			} else {
				$t.addClass('collapsed');
				$target.css('max-height', '');
			}

			return false;
		});
		$target.data('start-height', parseInt($target.css('max-height')));

		update();
		b2.resizeHandlers.push(update);
	});
};

RZB2.utils.addFuncToReady('initHeightCollapse','[data-toggle="height-collapse"]');
})(jQuery);