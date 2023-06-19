function initToggles(target){
	var $target = $(target);

	$target.find('[data-switch]').each(function(){
		makeSwitch(this);
	})

	// expandable menus functionality
	$target.find('.expandable li, .expand-content').on('click', function(e){
		e.stopPropagation();
	});
	$target.find('.expandable').on('click', function(e){
		var _ = $(this);
		if (!Modernizr.mq('(max-width: 767px)') && _.hasClass('xs-only'))
			return;
		e.preventDefault();
		e.stopPropagation();
		if (!_.hasClass('allow-multiple-expanded')){
			_.parent().find('.expanded')
				.not(_)
				.removeClass('expanded')
				.find('.expand-content').velocity('slideUp', 400);
		}
		
		if ( _.hasClass('expanded') ){
			_.children('.expand-content').velocity('slideUp', 300, function(){
				_.removeClass('expanded');
			});
		} else {
			_.addClass('expanded').children('.expand-content').velocity('slideDown', 300);
		}
	});
	// END OF expandable menus functionality

	$target.find('.show-hide-hits').off('click').on('click', function(e){
		var _ = $(this);
		var parent = _.parent();
		if ( parent.hasClass('submenu-wrap') ){
			if ( b2.el.$menu.hasClass('hits-hidden') ){
				var wrap = _.siblings('.scroll-slider-wrap');
				b2.el.$menu.removeClass('hits-hidden')
					.trigger('hitstoggle', {type: 'show', wrap: wrap});
			} else {
				var wrap = _.siblings('.scroll-slider-wrap');
				b2.el.$menu.addClass('hits-hidden')
					.trigger('hitstoggle', {type: 'hide', wrap: wrap});
			}
		} else if ( parent.hasClass('catalog-hits') ){
			if ( parent.hasClass('hits-shown') ){
				parent.removeClass('hits-shown').addClass('hits-hidden')
					.trigger('hitstoggle', 'hide');
			} else if ( parent.hasClass('hits-hidden') ){
				parent.removeClass('hits-hidden').addClass('hits-shown')
					.trigger('hitstoggle', 'show');
			}
		}
	});

	$target.find('.basket-waitlist-toggle').on('click', function(){
		$(this).closest('.popup_basket').toggleClass('waitlist')
			.find('.scroller').trigger('sizeChange').find('select');
        b2.init.selects && b2.init.selects($(this).find('select').parent());
        if (typeof b2.init.scrollbarsTargeted == "function") b2.init.scrollbarsTargeted($(this).find('select').parent());
	});

	$target.find('#hurry-carousel .xs-switch, .catalog .xs-switch').on('click', function(){
		$(this).closest('.catalog-item').toggleClass('opened');
	});

	
	$target.find('.btn-toggle').on('click', function(){
		$(this).toggleClass('toggled');
	});
}