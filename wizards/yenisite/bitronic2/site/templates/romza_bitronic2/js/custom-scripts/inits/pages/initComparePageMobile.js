b2.init.comparePageMobile = function(){
	var $wrap = $('#m-compare-table'),
		$body = $wrap.find('.m-compare-table__body'),
		$primary = $wrap.find('#m-items__primary'),
		$secondary = $wrap.find('#m-items__secondary'),
		headCompare = $wrap.find('.m-compare-table__head').height(),
		settings = {
			horizontal: true,

			// Item based navigation
			itemNav: 'forceCentered',	//'basic', 'centered', 'forceCentered'.
			activateMiddle: true,
			smart: true,			// Repositions the activated item to help with further navigation.

			// Dragging
			mouseDragging: true,	// Enable navigation by dragging the SLIDEE with mouse events.
			touchDragging: true,	// Enable navigation by dragging the SLIDEE with touch events.
			releaseSwing: true,		// Ease out on dragging swing release.
			swingSpeed: 0.2,		// Swing synchronization speed, where: 1 = instant, 0 = infinite.
			elasticBounds: true,	// Stretch SLIDEE position limits when dragging past FRAME boundaries.
			interactive: null,		// Selector for special interactive elements.

			activatePageOn: 'click',
			pageBuilder: function (index) { return '<i></i>'; },

			// Mixed options
			speed: 350,				// Animations speed in milliseconds. 0 to disable animations.
			keyboardNavBy: 'items', // Enable keyboard navigation by 'items' or 'pages'.
		},
		primarySly = new Sly($primary, settings, {
			load: function(eventName) {
				$primary.addClass('init');
			},
			active: function(eventName, itemIndex) {
				var $section = $primary.parent();

				// change the active item number when changing an active slide
				$section.find('.m-compare__item-number .cur').html(itemIndex + 1);
				$section.find('.m-compare__item-number .btn-delete').attr('data-delete', itemIndex);
				$body.attr('data-active-primary', itemIndex);

				// change values of all properties an active slide
				$body.find('.m-compare__item.m-compare__item-primary').each(function(){
					var $prop = $(this),
						prop_cur = $prop.find('.item_' + itemIndex).html();

					$prop.find('.m-compare__prop-cur').html(prop_cur);
				});
			}
		});
		secondarySly = new Sly($secondary, settings, {
			load: function(eventName) {
				$secondary.addClass('init');
			},
			active: function(eventName, itemIndex) {
				var $section = $secondary.parent();

				// change the active item number when changing an active slide
				$section.find('.m-compare__item-number .cur').html(itemIndex + 1);
				$section.find('.m-compare__item-number .btn-delete').attr('data-delete', itemIndex);
				$body.attr('data-active-secondary', itemIndex);

				// change values of all properties an active slide
				$body.find('.m-compare__item.m-compare__item-secondary').each(function(){
					var $prop = $(this),
						prop_cur = $prop.find('.item_' + itemIndex).html();

					$prop.find('.m-compare__prop-cur').html(prop_cur);
				});
			}
		}),
		headerBlockH = $wrap.find('.m-compare__header-info').outerHeight();

		if ( $('.top-line').length > 0 && b2.s.topLinePosition === 'fixed-top' ) {
			var topLineH = $('.top-line').height();
		}

	function checkTop(original, headerHeight, headH, topLineH, action) {
		var head = original.find('.m-compare-table__head'),
			topLine = (typeof topLineH !== 'undefined') ? topLineH : 0,
			stickyHeight = head.height(),
			origHeight = original.height(),
			curTop = $(window).scrollTop(),
			tableTop = original.offset().top,
			switchTop = tableTop + headerHeight - topLine - 88,
			fixedLeft = original.offset().left;

		if ( curTop > switchTop ) {
			if ( typeof action !== 'undefined' && action === 'resize' ) {
				if ( head.hasClass('fixed-top') ) {
					head.css({
						'width': original.width(),
						'top': topLine,
						'left': fixedLeft
					});
				}
			}

			if ( !head.hasClass('fixed') ) {
				original.css('padding-top', headH);
				head.addClass('fixed').addClass('fixed-top').css({
					'width': original.width(),
					'top': topLine,
					'left': fixedLeft
				});
			}

			if ( (curTop + stickyHeight + topLine + 164 - headH) >= (tableTop + origHeight) ) {
				if ( head.hasClass('fixed-top') ) {
					original.css('padding-top', headH);
					head.removeClass('fixed-top').addClass('no-fixed')
						.offset({ top: tableTop + origHeight + headH - stickyHeight - 164 }).css({
							'width': '',
							'left': '',
						});
				}
			} else {
				if ( head.hasClass('no-fixed') ) {
					original.css('padding-top', headH);
					head.addClass('fixed-top').removeClass('no-fixed').css({
						'width': original.width(),
						'top': topLine,
						'left': fixedLeft
					});
				}
			}
		} else {
			if ( head.hasClass('fixed') && head.hasClass('fixed-top') ) {
				original.css('padding-top', '');
				head.removeClass('fixed').removeClass('fixed-top').css({
					'width': '',
					'top': '',
					'left': ''
				});
			} else if ( head.hasClass('fixed') ) {
				original.css('padding-top', '');
				head.removeClass('fixed').css('top', '');
			}
		}
	}

	function compareUpdate() {
		if ( $primary.length ) {
			$primary.find('.item').each(function() {
				$(this).css('width', $primary.width());
			});
			if (primarySly.initialized) primarySly.reload();
			else primarySly.init().activate(0);
		}

		if ( $secondary.length ) {
			$secondary.find('.item').each(function() {
				$(this).css('width', $secondary.width());
			});
			if (secondarySly.initialized) secondarySly.reload();
			else secondarySly.init().activate(1);
		}
	}

	function updateTop(action) {
		if ( typeof action.type !== 'undefined' ) action = action.type;
		checkTop($wrap, headerBlockH, headCompare, topLineH, action);
	}

	$(window).on('scroll.b2comparepage', updateTop);
	$(window).on('load.b2comparepage', function(){
		setTimeout(function(){
			compareUpdate();
			updateTop('load');
		}, 350);
	});
	var compareResizeTimeout;
	$(window).on('resize.b2comparepage', function(){
		if ( $('.top-line').length > 0 && b2.s.topLinePosition === 'fixed-top' ) {
			topLineH = $('.top-line').height();
		}
		updateTop('resize');
		clearTimeout(compareResizeTimeout);
		compareReizeTimeout = setTimeout(function(){
			compareUpdate();
		}, 350);
	});
}