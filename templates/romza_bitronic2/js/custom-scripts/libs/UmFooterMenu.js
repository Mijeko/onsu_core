function FooterMenu(menu, visibleItemsNumber) {
	var self = this,
		container = menu.children('.container-wrap'),
		main = container.children('.main'),
		mainItems = main.children(),
		btnShow = container.find('.catalog-show'),
		btnHide = container.find('.catalog-hide'),
		adds = $('<div class="catalog-menu-footer additional" />'),
		visible = ( $.isNumeric(visibleItemsNumber) ) ? visibleItemsNumber : 'all',
		addsItems, timeout;

	this.resetFull = function() {
		// reset everything - WARNING! DOM HANDLING HERE!
		container.removeClass('ready btn-shown');
		main.css('display', '');
		if ( adds ) adds.detach().css('display','');
		if ( addsItems && addsItems.length > 0) {
			mainItems = mainItems.add(addsItems);
			main.append(addsItems);
		}
	}

	this.makeSideFull = function() {
		// here we have vertical menu with some visible items and some hidden.
		if (visible !== 'all' && mainItems.length > visible) {
			addsItems = mainItems.slice(visible);
			mainItems = mainItems.slice(0, visible);
			adds.prepend(addsItems).appendTo(container);

			container.addClass('ready btn-shown');
		} else { container.addClass('ready'); }
	}

	this.updateState = function() {
		// this function should be called only on init or on state change from outside
		self.rebuild(visible);
	}

	this.updateVisible = function(number) {
		// check if visible has changed
		if ( visible !== number && $.isNumeric(number) ) {
			visible = number;
			self.rebuild();
		}
	}

	var rebuildTimeout;
	this.rebuild = function() {
		clearTimeout(rebuildTimeout);
		rebuildTimeout = setTimeout(function() {
			// timeouts here for browser to calc dimensions after reset
			self.resetFull();
			clearTimeout(timeout);
			timeout = setTimeout(function() {
				self.makeSideFull();
				menu.on('click.mainMenu', '.btn-catalog-footer-wrap .catalog-show', function(e) {
					e.stopPropagation();
					adds.velocity('slideDown', 0, 'linear');
					menu.addClass('opened');
				}).on('click.mainMenu', '.btn-catalog-footer-wrap .catalog-hide', function(e) {
					e.stopPropagation();
					adds.velocity('slideUp', 0, 'linear');
					menu.removeClass('opened');
				});
			}, 10);
		}, 10);
	}
}