function initPopups() {
	$(document).on('click', '[data-toggle="um_popup"]', function (e) {
		var $t = $(this),
			data = $t.data(),
			$target = data.target,
			$toggler = data.toggler,
		//^ for setting "toggled" on element other than the one clicked. Why?
		// example: you click on link inside some container, and you want to style
		// the container itself as active element, not link inside it
			options = ( data.options ) ? data.options.split(' ') : null,
			darken = data.darken,
			counter = globalCounter++;

		if ($target == '^') {
			$target = $t.parent();
			if (!$toggler) $toggler = '>';
		}
		else if ($target == '>') {
			$target = $t.find('.um_popup');
			if (!$toggler) $toggler = '^';
		}
		else if ($target.indexOf('^') !== -1) {
			$target = $target.substr($target.indexOf('^') + 1);
			//console.log('full target: ', $target);
			var divideIndex = $target.indexOf('>');
			var finalTarget = $target.substr(divideIndex + 1);
			//console.log('funal target: ', finalTarget);
			var top = $target.substr(0, divideIndex);
			//console.log('closest target: ', top);
			$target = $t.closest(top).find(finalTarget);
			//console.log($target);
		}
		else $target = $($target);
		if (!$toggler && $t.attr('id')) $toggler = $('#' + $t.attr('id'));

		// toggler is string, popup is jQuery object
		function unToggle() {
			switch ($toggler) {
				case undefined:
					break;
				case '^':
					$target.closest('[data-toggle].toggled').removeClass('toggled');
					break;
				case '>':
					$target.find('[data-toggle].toggled').removeClass('toggled');
					break;
				default:
					$toggler.removeClass('toggled');
			}
		}

		function updateBorders() {
			// check for viewport borders
			var offset = $target.offset();
			if (!offset) return;

			var popupLeft = offset.left,
				popupW = $target.outerWidth(),
				windowW = $(window).width(),
				diff = windowW - (popupLeft + popupW);

			if (diff < 0) {
				var currentMargin = parseFloat($target.css('margin-left'));
				$target.css('margin-left', currentMargin + diff);
			}

			var scroller = $target.children('.table-wrap').children('.scroller');
			if (!(scroller.length > 0)) return;

			scroller.css('height', '');
			var popupTop = $target.offset().top - $(window).scrollTop();
			// ^ don't use var offset here, because we
			// just've reset scroller height, => top changed.
			var popupH = $target.outerHeight();
			var scrollerH = scroller.outerHeight();

			if (popupTop < 0) {
				scroller.css('height', scrollerH + popupTop).trigger('sizeChange');
			} else {
				var windowH = $(window).height();
				diff = windowH - (popupTop + popupH);
				if (diff < 0) scroller.css('height', scrollerH + diff).trigger('sizeChange');
			}
		}

		function close() {
			$target.velocity('finish').velocity('fadeOut', {
				begin: function () {
					if (darken !== undefined && !$('body').data('going-to-dark')) $('body').removeClass('darken-popup');
					unToggle();
					$(document).off('click.UmPopup' + counter).off('keydown.UmPopup' + counter);
				},
				complete: function () {
					$target.data('opened', false).removeClass('um_popup-shown');
				}
			});
		}

		function open(event) {
			if ($.inArray('backnav', options) !== -1) {
				if ($t.closest('.breadcrumbs:not(.b2_backnav-enabled)').length) return false;

				var callerOffset = $t.position();
				var targetIndex = parseInt($t.attr('data-backnav-target'));
				var backNavTarget = $target.find('li').eq(targetIndex);
				backNavTarget.addClass('active').children('a').addClass('toggled');
				var offsetTop = backNavTarget.position().top;
				$target.css({
					'top': callerOffset.top,
					'left': callerOffset.left,
					'margin-top': -offsetTop
				});
				backNavTarget.one('click', close);
			}

			$target.data('opened', true).addClass('um_popup-shown').velocity('finish').velocity('fadeIn', {
				duration: 300,
				begin: function () {
					$target.css({
						opacity: 0,
						display: 'block'
					});
					if (darken !== undefined) {
						$('body').addClass('darken-popup').data('going-to-dark', true);
					}
					if ($.inArray('centered', options) !== -1) {
						var callerHalf = $t.outerWidth() / 2,
							popupHalf = $target.outerWidth() / 2,
							callerLeft = $t.offset().left,
							popupLeft = $target.offset().left,
							neededOffset = callerHalf - popupHalf,
							currentOffset = popupLeft - callerLeft,
							diff = neededOffset - currentOffset;

						// checking for already centered
						if (diff > 2 || diff < -2) {
							$target.css('margin-left', diff);
						}
					}
					updateBorders();
				},
				complete: function () {
					$target.find('select').ikSelect('redraw');
					$('body').data('going-to-dark', false);
				}

			});


			if ($toggler) {
				$target.attr('data-toggler', $toggler);

				// now if toggler was clearly defined, toggle class on it.
				// but if toggler was calculated and looks like '^' or '>', toggle el instead
				if (data.toggler) $(data.toggler).addClass('toggled');
				else $t.addClass('toggled');
			}

			// closing on click outside
			$(document).on('click.UmPopup' + counter, function (e) {
				if (!( $(e.target).closest($target.add($t)).length > 0 ||
					$target.hasClass('click-stay'))) {
					close();
				}
			});

			// closing on ESC
			$(document).on('keydown.UmPopup' + counter, function (e) {
				if (27 == e.keyCode) close();
			});
		}

		if ($target.data('opened')) close();
		else open();
		e.preventDefault ? e.preventDefault() : window.event.returnValue = false;
	})
}

function initRatingStars(target) {
	$(target).find('.rating-stars').on({
		click: function (e) {
			var _ = $(this);
			var index = _.index() + 1;
			_.closest('.rating-stars').removeClass('r1 r2 r3 r4 r5').addClass('r' + index);
			return false;
		},
		mouseenter: function (e) {
			$(this).nextAll().removeClass('hovered');
			$(this).prevAll().addBack().addClass('hovered');
		},
		mouseleave: function (e) {
			$(this).siblings().addBack().removeClass('hovered');
		}
	}, 'i');
}
$(function () {
	globalCounter = 0;
	initPopups();
	initRatingStars(document);
	var scrolls_v = $('.scroller_v').baron({
		bar: '.scroller__bar_v',
		barOnCls: 'baron',
		direction: 'v',
	});
	$('[data-tooltip]').tooltip({
		html: true
	});
});