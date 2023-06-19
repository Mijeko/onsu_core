/* === check if $el is in viewport. If not, move it via margins === */
function checkViewport($el, $view){
	$view = $view ? $view : $(window);
	// calc el dimensions and coords
	var width = $el.outerWidth(),
		height = $el.outerHeight(),
		elTop = $el.offset().top,
		elLeft = $el.offset().left;

	// calc viewport limits
	var vWidth = $view.outerWidth(),
		vHeight = $view.outerHeight(),
		vLeft = $.isWindow($view.get(0)) ? $view.scrollLeft() : $view.offset().left,
		vTop = $.isWindow($view.get(0)) ? $view.scrollTop() : $view.offset().top,
		vRight = vLeft + vWidth,
		vBottom = vTop + vHeight;

	var curMarginLeft = parseFloat($el.css('margin-left'));
	var curMarginTop = parseFloat($el.css('margin-top'));
	
	var diff = elLeft - vLeft;
	if ( diff < 0 ) $el.css('margin-left', curMarginLeft - diff);
	diff = vRight - (elLeft + width);
	if ( diff < 0 ) $el.css('margin-left', curMarginLeft + diff);
	diff = elTop - vTop;
	if ( diff < 0 ) $el.css('margin-top', curMarginTop - diff);
	diff = vBottom - (elTop + height);
	if ( diff < 0 ) $el.css('margin-top', curMarginTop + diff);
}

/* === positioning popup relative to base ===
position options:
horizontal - left, right, leftborder, rightborder, centered (default)
vertical - top, bottom (default), topborder, bottomborder, centered
*/
function posPopup($popup, options){
	var $base = options.base ? options.base : false;
	var $view = options.viewport ? options.viewport : $(window);

	var position = (options.position !== undefined) ? options.position.split(' ') : false;
	if ( !$base || position === false){
		// if no base or position - just check against viewport
		checkViewport($popup, $view);
		return false;
	}
	
	position = { left: position[0] || 'centered', top: position[1] || 'bottom' }
	$popup.attr('data-h-align', position.left)
			.attr('data-v-align', position.top);

	// calc base dimensions and coords
	var bWidth = $base.outerWidth(),
		bHeight = $base.outerHeight(),
		bTop = $base.offset().top,
		bLeft = $base.offset().left,
		bRight = bLeft + bWidth,
		bBottom = bTop + bHeight;

	// calc popup dimensions
	var pWidth = $popup.outerWidth(),
		pHeight = $popup.outerHeight();

	// calc viewport limits
	var vWidth = $view.outerWidth(),
		vHeight = $view.outerHeight(),
		vLeft = $.isWindow($view.get(0)) ? $view.scrollLeft() : $view.offset().left,
		vTop = $.isWindow($view.get(0)) ? $view.scrollTop() : $view.offset().top,
		vRight = vLeft + vWidth,
		vBottom = vTop + vHeight;

	// calc new coords for popup
	var posParentOffset = $popup.offsetParent().offset(),
		newLeft, newTop;
	
	// horizontal
	switch (position.left){
		case 'left':
			newLeft = bLeft - pWidth;
			// if outside viewport - switch to right
			if (newLeft < vLeft){
				newLeft = bRight;
				position.left = 'right';
			}
			break;
		case 'leftborder':
			newLeft = bLeft;
			// if outside viewport - switch to rightborder
			if ((newLeft + pWidth) > vRight){
				newLeft = bRight - pWidth;
				position.left = 'rightborder';
			}
			break;
		case 'right':
			newLeft = bRight;
			// if outside viewport - switch to left
			if ((newLeft + pWidth) > vRight){
				newLeft = bLeft - pWidth;
				position.left = 'left';
			}
			break;
		case 'rightborder':
			newLeft = bRight - pWidth;
			// if outside viewport - switch to leftborder
			if (newLeft < vLeft){
				newLeft = bLeft;
				position.left = 'leftborder';
			}
			break;
		case 'centered':
		default:
			// by default we center!
			newLeft = bLeft + (bWidth / 2) - (pWidth / 2);
			if (newLeft < vLeft) newLeft = vLeft;
			if ((newLeft + pWidth) > vRight) newLeft = vRight - pWidth;
	}

	// vertical
	switch (position.top){
		case 'top':
			newTop = bTop - pHeight;
			// if outside viewport - switch to bottom
			if (newTop < vTop){
				newTop = bBottom;
				position.top = 'bottom';
			}
			break;
		case 'topborder':
			newTop = bTop;
			// if outside viewport - switch to bottomborder
			if ((newTop + pHeight) > vBottom){
				newTop = bBottom - pHeight;
				position.top = 'bottomborder';
			}
			break;
		case 'centered':
			newTop = bTop + (bHeight / 2) - (pHeight / 2);
			if (newTop < vTop) newTop = vTop;
			if ((newTop + pHeight) > vBottom) newTop = vBottom - pHeight;
			break;
		case 'bottomborder':
			newTop = bBottom - pHeight;
			// if outside viewport - switch to topborder
			if (newTop < vTop){
				newTop = bTop;
				position.top = 'topborder';
			}
			break;
		case 'bottom':
		default:
			// by default - bottom!
			newTop = bBottom;
			// if outside viewport - switch to top
			if ((newTop + pHeight) > vBottom){
				newTop = bTop - pHeight;
				position.top = 'top';
			}
	}

	$popup.attr('data-h-align', position.left)
		.attr('data-v-align', position.top)
		.css({ 
			left: newLeft - posParentOffset.left,
			top: newTop - posParentOffset.top
		});
}