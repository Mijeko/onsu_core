function SitenavMenu(menu){
	var self = this;
	var $wrap = $('.sitenav-wrap');
	var $sitenav = $wrap.find('.sitenav-menu');
	var bullet = '<span class="bullet">•</span>';
	var item = 'sitenav-menu-item';
	var $items = $sitenav.find('.' + item);
	var collapse = $('<div class="' + item + ' with-sub addsItems" />'),
		bullets = $('<div class="bullets">' + bullet + bullet + bullet + '</div>'),
		list = $('<ul class="submenu" />'),
		updating = false;

	var addsItems;

	var timeout;

	menu.on('click', '.sitenav-menu-item.with-sub > a, .sitenav-menu-item.with-sub > .sitenav-header a, .addsItems .submenu .with-sub > a, .addsItems .submenu .with-sub > .sitenav-header a', function(e) {
		if ( !(!isMobile && isTouch) ) return;

		var $link = $(this);

		e.preventDefault();

		var $submenu = $link.closest('.with-sub');

		if ( menu.find('.with-sub.opened').not($submenu).length ) {
			menu.find('.with-sub.opened').not($submenu).each(function() {
				var $t = $(this);

				if ( !$.contains(this, $submenu[0]) ) $t.removeClass('opened');
			});
		}

		$submenu.toggleClass('opened');
		return false;
	});

	menu.on('click', '.sitenav-menu-item.addsItems .bullets', function(e) {
		if ( !(!isMobile && isTouch) ) return;

		var $submenu = $(this).siblings('.submenu');

		if ( menu.find('.with-sub.opened').not($submenu).length ) {
			menu.find('.with-sub.opened').not($submenu).each(function() {
				var $t = $(this);

				if ( !$.contains(this, $submenu[0]) ) $t.removeClass('opened');
			});
		}

		$submenu.parent().toggleClass('opened');
		return false;
	});

	$(document).mouseup(function(e) {
		if ( !(!isMobile && isTouch) ) return;

		if (!menu.is(e.target) && menu.has(e.target).length === 0) {
			if ( menu.find('.with-sub.opened').length ) {
				menu.find('.with-sub.opened').each(function() {
					var $t = $(this);
					$t.removeClass('opened');
				});
			}
		}
	});

	var resetTimeout;
	this.resetHandlers = function(){
		clearTimeout(resetTimeout);
		resetTimeout = setTimeout(function() {
			self.updateState();
		}, 10);
	}

	this.resetItems = function(){
		// reset everything
		if (addsItems) {
			$items = $items.add(addsItems.addClass(item));
			addsItems.appendTo($sitenav);
			collapse.detach();
			addsItems = null;
		}

		$sitenav.closest('.sitenav').removeClass('menu-collapse').removeClass('shown');
		// end of reset
	}

	this.updateState = function(){
		self.resetItems();

		clearTimeout(timeout);
		timeout = setTimeout(function() {
			menu.removeClass('touch-menu');

			if ( !isMobile && isTouch ) menu.addClass('touch-menu');

			if ( Modernizr.mq('(max-width: 767px)') ) return;

			if (b2.s.sitenavType === 'collapse') {
				$sitenav.closest('.sitenav').addClass('menu-collapse');
				$items = $sitenav.find('.' + item);

				if ( $sitenav.width() > $wrap.width() ) {
					for (var i = $items.length - 1; i >= 0; i--) {
						if ( ($items.eq(i).position().left + $items.eq(i).width()) <= $wrap.width() - 40 ) {
							$sitenav.closest('.sitenav').addClass('shown');

							addsItems = $items.slice(i);
							addsItems.each(function(){
								$(this).removeClass(item);
							});

							bullets.appendTo(collapse);
							addsItems.appendTo(list);
							list.appendTo(collapse);
							collapse.appendTo($sitenav.closest('.sitenav'));

							break;
						}
					}
				}
			}
		}, 10);
	}

	function resizeHandler(){
		if (b2.s.sitenavType === 'collapse') {
			if (updating) return;
				updating = true;

			self.resetHandlers();
			updating = false;
		}
	}
	var resizeTimeout;
	$(window).on('resize.sitenav',function(){
		clearTimeout(resizeTimeout);
		resizeTimeout = setTimeout(resizeHandler, 150);
	});
}