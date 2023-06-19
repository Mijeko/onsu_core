function initCommons(){
	b2.el.$menu = $('#mainmenu');
	b2.el.$sitenav = $('#sitenav');
    b2.el.$sitenavInFooter = $('#sitenav-footer');
	b2.el.$footmenu = $('.catalog-menu-footer-wrap');
	b2.el.$menuHits = $('.submenu-wrap .scroll-slider-wrap');
	b2.el.$settingsModal = $('#settings-panel');

	b2.el.menu = new MainMenu(b2.el.$menu, b2.s.menuVisibleItems);
	b2.el.menu.updateState();
	
	b2.el.sitenav = new SitenavMenu(b2.el.$sitenav);
	b2.el.sitenav.updateState();

    b2.el.sitenavInFooter = new SitenavMenu(b2.el.$sitenavInFooter);
    b2.el.sitenavInFooter.updateState();

	b2.el.footmenu = new FooterMenu(b2.el.$footmenu, b2.s.footmenuVisibleItems);
	b2.el.footmenu.updateState();

	initSettings();
	initPopups();
	initSearch(document);
	initModals(document);
	initToggles(document);

	// LazyLoad for all images with .lazy
	$("img.lazy").lazyload({
		effect: "fadeIn",
		threshold: 400,
		failurelimit: 10000
	});

	//authorize();

	$('.theme-demo').on('click', function(){
		var $t = $(this),
			newTheme = $t.data('theme');

		$(b2.s).trigger('set', ['color-theme', newTheme, true]);
	});

	var setPanel = $('#settings-panel-cblocks');

	setPanel.on('click', '.site-background li', function(e) {
		var $this = $(this),
			$parent = $this.closest('.site-background'),
			$tar = $parent.data('target');

		if ( typeof $tar === 'undefined' ) {
			$tar = $('#settings_' + $parent.data('name'));
			$parent.data('target', $tar);
		}

		$tar.val($this.data('value')).trigger('change');
	});

	setPanel.on('change', '.bg-setting', function(e) {
		var $this = $(this),
			obj = $this.data('obj');

		if ( typeof obj === 'undefined' ) {
			obj = $($this.data('selector'));
			$this.data('obj', obj);
		}

		if ( $this.data('selector') ) {
			if ( $this.data('attr') === 'style' )
				obj.css( $this.data('property'), $this.val() );
			else if ( $this.data('attr') === 'attr' )
				obj.attr( $this.data('property'), $this.val() );
		}
	});

	$('.phone-masked').mask("8 (999) 999-99-99", {});
	$('.masked-date').mask('99.99.9999');

	// stopping page scroll on mousewheel / touchmove when over popup
	$('.top-line-popup').on('mousewheel touchmove', function(e){
		if ( $(e.target).closest('.table-wrap').length === 0 ){
			e.stopPropagation();
			e.preventDefault();
		}
	})

	$('.slider-arrow, .btn-city-toggle').click(function(e){
		e.preventDefault();
	})

	$('.currency-switch, .lang-switch').on('click', 'li', function(e){
		var $t = $(this),
			$etarget = $(e.delegateTarget);
			current = $etarget.children('.value'),
			newVal = $t.data('value');
		if ( current.data('value') !== newVal ){
			current.html($t.html()).attr('data-value', newVal).data('value', newVal);
			$t.addClass('active').siblings('.active').removeClass('active');
			$etarget.trigger('change', newVal);
		} else return false;
	})

	$('.settings-info-popup').on('close', function(){
		$('#settings-toggle').removeClass('animated infinite');
	});

	var sitenavItems = $('#sitenav').find('.sitenav-menu-item');
	var sitenavItemWidth = 100 / sitenavItems.length;
	sitenavItems.each(function(){
		$(this).css('min-width', sitenavItemWidth);
	})

	var sitenav = $('.sitenav');
	$('.btn-sitenav-toggle').click(function(){
		$('#sitenav').children('.sitenav-menu').slideToggle('fast');
	})

	sitenav.on('click', '.sitenav-menu-item > a, .sitenav-header > a:not(.sitenav-additional-link)', function(e){
		var $t = $(this);
		var item = $(this).closest('.sitenav-menu-item');
		if ( item.hasClass('with-sub') ){
			if ( !Modernizr.mq('(max-width: 767px)') ) return;
			var submenu = item.children('.submenu');
			if (item.hasClass('opened')){
				submenu.velocity('stop', true).velocity('slideUp', 300);
				item.removeClass('opened');
			} else {
				submenu.velocity('stop', true).velocity('slideDown', 300);
				item.addClass('opened');
			}
			return false;
		}
		
		// in header v2 this menu is actually a table with height: auto
		// if menu items text is too large, then height of cells can become
		// two or three rows. But small links in other cells DON'T occupy full
		// height (and height: 100% won't work because parent has height: auto)
		// so, if somebody clicks on space of li's but not a's in such case,
		// we need to emulate link click (while hover effects and such are already
		// emulated by me in CSS)
		if ( !$t.is(e.target) ){
			$t.get(0).click();
		}
	})
	/* BACK_END NOT_NEED in ready project - move to ajax_handlers.js
	$('.contacts-content').on('click', '.phone-link', function(e){
		if ( !Modernizr.mq('(max-width: 767px)') ){
			e.preventDefault();
			$('#modal_callme').modal('show');
		}
	});
	 */
	$('.to-top').click(function(){
		$(window).scrollTop(0);
	})
	// hide/show "to top" button
	var toTopShown = false;
	var toTopBtn = $('.to-top').hide();
	function updateToTop(){
		if ( winScrollTop > 200 && toTopShown === false ){
			toTopBtn.show();
			toTopShown = true;
		} else if ( winScrollTop < 200 && toTopShown === true ){
			toTopBtn.hide();
			toTopShown = false;
		}
	}
	updateToTop();

	// we will always know current scrollTop! Useful.
	$(window).on('scroll',function(){
	    winScrollTop = $(this).scrollTop();
	    updateToTop();
	})

	var timeout;
	$(window).on('resize', function(){
		clearTimeout(timeout);
		timeout = setTimeout(function(){
            b2.init.selects && b2.init.selects($('select:visible').parent());
			$('.scroll-slider:visible').sly('reload');
		}, 300);
	});
}