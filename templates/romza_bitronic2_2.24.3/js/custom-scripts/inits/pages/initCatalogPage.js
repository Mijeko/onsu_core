b2.init.catalogPage = b2.init.searchResultsPage = function(){
	// fix tablet determination as mobile: filter is always open,
	// if width > 768px
	// we need to check at the beginning of scripts so nobody would suffer from
	// "why does it collapsed first but expands later??!!11 It should be expanded from the start!!!"
	// This should do the trick :)
	
	$('.toggle-filter').click(function(){
		var filterContent = $(this).siblings('.filter-content');
		var filter = filterContent.closest('.form_filter');
		if ( filter.hasClass('closed') ){
			filter.removeClass('closed');
			filterContent.velocity('slideDown', 'fast');
		} else {
			filter.addClass('closed');
			filterContent.velocity('slideUp', 'fast');
		}
	})
	$('.form_filter').on('click', '.btn-expand, .filter-section>header>.text', function(e){
		var section = $(this).closest('.filter-section');
		if (!Modernizr.mq('(max-width: 767px)') && section.hasClass('xs-only'))
			return;
		e.preventDefault();
		e.stopPropagation();
				
		if ( section.hasClass('expanded') ){
			section.children('.expand-content').velocity('slideUp', 300, function(){
				section.removeClass('expanded');
			});
		} else {
			section.addClass('expanded').children('.expand-content').velocity('slideDown', 300, function(){
                b2.init.selects && b2.init.selects($(this).find('select').parent());
                if (typeof b2.init.scrollbarsTargeted == "function") b2.init.scrollbarsTargeted($(this).find('select').parent());
			});
		}
	});

	var brandsCatalog = $('.brands-catalog');
	brandsCatalog.on('click', '.brand', function(e){
		var $t = $(this);
		if ( $t.hasClass('disabled') ) return;

		$t.toggleClass('active');
		brandsCatalog.trigger('togglebrand', {
			item: $t,
			state: $t.hasClass('active')
		});
	}).on('mousedown', '.brand', function(e){
		return false; // to prevent text selection
	});
	/* BACK_END NOT_NEED in ready project - move to catalog.smart.filter/filter/script.js
	// flying results
	var isFlying = false;
	var flying = $('#flying-results-wrap');
	var sideFilterWrap = $('#filter-at-side');
	if (sideFilterWrap.length > 0) { 
		function setFlyingResults(el){
			var thisTop = el.offset().top;
			var sideFilterWrapTop = sideFilterWrap.offset().top;
			flying.css('top', thisTop - sideFilterWrapTop - 10);
			if ( !isFlying ){
				flying.velocity('fadeIn');
			} 

			clearTimeout(isFlying);
			isFlying = setTimeout(function(){
				flying.velocity('fadeOut');
				isFlying = false;
			}, 4000);
		}
		sideFilterWrap.on('change set', function(e){
			setFlyingResults($(e.target).parent());
		});
	}
	// end of flying results
	*/
	$('.sort-n-view').on('click', '.sort-list-item', function(){
		var _ = $(this);
		if ( _.hasClass('active') ){
			_.toggleClass('sort-up');
		} else {
			_.addClass('active').siblings('.active').removeClass('active');
		}
	});
	$('#info4admin-switch').click(function(){
		$('.info4admin').toggle();
	})

	var thScrollFix;	/* BACK_END - moved to RZB2.ajax.CatalogSection
	new UmTabs('.view-type>a', {
		onChange: function(target){
			if ( target.is('#catalog-table') ){
				thScrollFix && thScrollFix.getDims();
				thScrollFix && thScrollFix.update();
			} else {
				initPhotoThumbs(target);
				$.ikSelect && target.find('select').ikSelect('redraw');
				$.baron && target.find('.scroller_v').baron().update();
			}
		}
	});
	BACK_END - moved to catalog.smart.filter/filter/script.js
	$('.btn-toggle-full-filter').click(function(e){
		var _ = $(this);
		_.toggleClass('toggled');
		var filterFull = _.closest('.form_filter').find('.filter-full');
		var filterShort = _.closest('.form_filter').find('.filter-short');
		if ( filterFull.hasClass('filter-opened') ){
			var selects = filterFull.find('select');
			selects.ikSelect('hideDropdown');

			filterFull.removeClass('filter-opened');
			filterShort.removeClass('filter-opened');
			filterFull.velocity('slideUp');
		} else {
			filterFull.velocity('slideDown', {
				complete: function(){
					filterFull.addClass('filter-opened');
					filterShort.addClass('filter-opened');
					var selects = filterFull.find('select').ikSelect('redraw');
				}
			});
		}

		return false;
	})

	if ( $('.search-results-page').length ){
		new UmTabs('#search-in-category>.sort-list-item', {
			targetSelector: '>a',
		});
	}
	*/

	if (b2.s.wowEffect == 'Y') {
		new WOW({
			offset: 100,
			mobile: true
		}).init();
	}

	if (typeof initHorizontalCarousels === 'function') initHorizontalCarousels(document);
	if (typeof initGenInfoToggle === 'function') initGenInfoToggle(document, b2.s.catalogTextDefault); /*
	initPagination(document); BACK_END NOT_NEED in ready project */
	initPhotoThumbs(document);

    if (!isMobile && $('#catalog_section').hasClass('blocks')) {
        $('#catalog_section').flexGreedSort('create', 'catalog-item-wrap', 'big-item', 'last-item', 'banner-catalog', $('[data-catalog-banner-pos]').data('catalog-banner-pos'));
    }

	function loadCatalogPageChunks(){
		require([
			'um/UmScrollFix',
			'util/basket',
//			'init/initBuyClick'  BACK_END not need in ready project
		], function(){
//			b2.init.buyClick();  BACK_END not need in ready project

			var tableHeader = $('.table-header');
			if ( tableHeader.length ){
				var el = $('.table-header');
				var wrap = el.closest('.catalog');
				var offBot = 50;
				thScrollFix = new UmScrollFix(el, wrap, 0, offBot);
			}
		});
	}

	if (windowLoaded) loadCatalogPageChunks();
	else $(window).load(loadCatalogPageChunks);
}