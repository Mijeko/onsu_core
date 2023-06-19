b2.init.productPage = function(){
	b2.el.$bigImgModal = $('#modal_big-img');
	b2.el.$bigImgWrap = b2.el.$bigImgModal.find('.bigimg-wrap');
	b2.el.$productPhotoImg = $('.product-page .product-photo img');
	var buyBlock = $('.product-page .product-main .buy-block-wrap');
	// .product-page to filter out modal
	// .product-main to filter out collection buy-block

	$('.usefulness').on('click', '.positive, .negative', function(e){
		var nextClass = $(this).hasClass('positive') ? 'positive' : 'negative';
		var valueSpan = $(this).next('.' + nextClass + '-value');
		var cur = parseInt(valueSpan.html());
		valueSpan.html(++cur);
		return false;
	});

	var genInfo = initGenInfoToggle(document);

	b2.el.$productInfoSections = $('#product-info-sections');
	if (b2.el.$productInfoSections.length > 0) {
		b2.el.productInfoSections = new UmComboBlocks(b2.el.$productInfoSections, {
			bp: 991,
			hasSpy: true,
			mode: b2.el.$productInfoSections.data('product-info-mode'),
			defaultExpanded: ( b2.el.$productInfoSections.data('product-info-mode-def-expanded') ) ? 'all' : 0,
			onOpen: function(target){
				if ( target.parent().hasClass('.characteristics') ){
					genInfo.update && genInfo.update();
				}
			}
		});
	}

	$('.write-review_top').click(function(e){
		e.preventDefault();
		var target = $('#comments .form_comment');
		var form = target.closest('.form-wrap');
	
		$('[href="#comments"]').trigger('click');
		if ( b2.el.$productInfoSections.hasClass('tabs') ){
			b2.el.$productInfoSections.velocity('scroll', {
				offset: ( b2.s.topLinePosition === 'fixed-top' ) ? -1 * $body.find('.top-line').outerHeight() : 0
			});
		}

		// opening form if it's not opened yet
		if ( !form.hasClass('toggled') ){
			form.addClass('toggled');
			target.slideDown(200);
		}

		return true;
	})

	if (typeof initHorizontalCarousels === 'function') initHorizontalCarousels(document);
	initProductCarousel(document);
	initMainGallery('.product-page');
	b2.el.$bigImgModal.on('shown.bs.modal', function(){
		if (typeof initModalGallery === 'function') initModalGallery();
		initProductCarousel(this);
	});

	// changing price based on additionals
	var changePriceByAdditional = function(target,_){
        var value = formatRub.from(_.siblings('.checkbox-content').find('.value').html());
        var curPrice = formatRub.from(target.html());
        if ( _.is(':checked') ){
            curPrice += value;
        } else {
            curPrice -= value;
        }
        curPrice = parseFloat(curPrice.toFixed(2));
        target.html(formatRub.to(curPrice));
	}

	$('.buy-block-additional').on('change', 'input[type="checkbox"]', function(e){
		var _ = $(this);
		var target = $(e.delegateTarget).closest('.buy-block-wrap').find('.buy-block-main .additionals-price .value');
        changePriceByAdditional(target,_);
	});

	var countPriceOfItemWithService = function() {
		if (!$('.buy-block-additional').length) return;

		var $container = $('.buy-block-additional').closest('.buy-block-wrap').find('.buy-block-main .additionals-price .value');

		$('.buy-block-additional input[type="checkbox"]:checked').each(function(){
            var _ = $(this);
            changePriceByAdditional($container,_);
		});
    }();

	$('.comments').on('click', '.comment-wrap>footer>.link', function(){
		$(this).toggleClass('toggled');
		$(this).closest('footer').siblings('.content').find('.hidden-block').slideToggle(200);
	})
	$('.combo-target.comments').on('click', '.form-wrap>header', function(){
		$(this).closest('.form-wrap').toggleClass('toggled');
		$(this).siblings('.form_comment').slideToggle(200);
	})
	$('.table_product-modifications').on('click', 'th>.text:not(.no-sort)', function(){
		$(this).toggleClass('sort-up');
	})
	$('.switch-stock').click(function(){
		buyBlock.toggleClass('out-of-stock');
	})

	// function to launch after window resize
	function onResizeComplete(){
		genInfo.update && genInfo.update();
	}
	// timer for window resize
	var resizeTimeout,
		$windowWidth = $(window).width();

	$(window).resize(function(){
		if ($windowWidth !== $(window).width()) {
			$windowWidth = $(window).width();
			clearTimeout(resizeTimeout);
			resizeTimeout = setTimeout(onResizeComplete, 300);
		}
	});

	if (b2.s.wowEffect == 'Y') {
		new WOW({
			offset: 100,
			mobile: true
		}).init();
	}

	function loadProductPageChunks(){
		require([
			'init/pages/initFloatingBuyBlock',
			'util/basket',
//			'init/initBuyClick',  BACK_END not need in ready project
            'async!https://maps.googleapis.com/maps/api/js?v=3.exp&key='+GOOGLE_KEY,
			'init/initMaps',
            'init/initScrollTo'
		], function(){
			b2.init.floatingBuyBlock();
//			b2.init.buyClick();  BACK_END not need in ready project
			if ($('.map').length) b2.init.maps(document);
            b2.init.initScrollTo('.similar-products.gift-slider', '.sticker-wrapper');
		});
	}

	if (windowLoaded) loadProductPageChunks();
	else $(window).load(loadProductPageChunks);

	if ($('.thumbnails-wrap').find('.thumb').length < 1) {
		$('.product-photos').addClass('no-thumbs');
	}
}