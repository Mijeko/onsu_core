function initModals(target){
	// MODALS NESTING
	$(document).on('hidden.bs.modal', '.modal', function( event ) {
		// never change to hide.bs.modal
		$(this).removeClass('fv-modal-stack');
		var nowModals = $body.data('fv_open_modals') - 1;
		if (nowModals > 0) $body.addClass('modal-open');
		$body.data('fv_open_modals', nowModals);
	}).on('show.bs.modal', '.modal', function(){
		// never change to shown.bs.modal
		var $this = $(this);
		if ( typeof ( $body.data( 'fv_open_modals' ) ) == 'undefined'){
			$body.data( 'fv_open_modals', 0 );
		}
		if ($this.hasClass('fv-modal-stack')) return;

		$this.addClass('fv-modal-stack');
		$body.data('fv_open_modals', $body.data('fv_open_modals')+1);
		$this.css( 'z-index', 1040 + (10 * $body.data('fv_open_modals')) );

		switch ($this.attr('id')){
			case 'modal_custom-collection':
				if (!b2.modalCustomCollectionInited){
					require([
						'libs/TweenLite.min',
						'libs/utils/Draggable.min',
						'libs/plugins/CSSPlugin.min',
						'init/initCollectionHandle',
					], function(){
						b2.modalCustomCollectionInited = true;
						//b2.init.collectionHandle(); BACK_END move to /js/back-end/ajax/catalog_set_constructor.js
					});
				}
			break;
			case 'modal_inform-when-price-drops': break; // BACK_END do not need modalInformPriceDrop
				if (!b2.modalInformWhenPriceDropsInited){
					require([
						'libs/nouislider.min',
						'init/modals/initModalInformPriceDrop'
					], function(){
						b2.modalInformWhenPriceDropsInited = true;
						initModalInformWhenPriceDrops();
					});
				}
			break;
		}
	}).on('shown.bs.modal', '.modal', function(){
		var $this = $(this);

		$this.find('.lazy-sly, .lazy').lazyload({
			effect: "fadeIn",
			threshold: 400,
			failurelimit: 10000
		});

		$('.modal-backdrop').not('.fv-modal-stack')
			.css('z-index', 1039 + (10*$body.data('fv_open_modals')))
			.not('.fv-modal-stack').addClass('fv-modal-stack');

        b2.init.selects && b2.init.selects($this.find('select').parent());
        if (typeof b2.init.scrollbarsTargeted == "function") b2.init.scrollbarsTargeted($this.find('select').parent());

		switch ($this.attr('id')){
			case 'modal_quick-view':
				if (b2.modalQuickViewInited) return true;
				require([
					'libs/jquery.mobile.just-touch.min',
					'init/initMainGallery',
					'init/toggles/initGenInfoToggle',
					'init/sliders/initProductCarousel',
					'async!https://maps.googleapis.com/maps/api/js?v=3.exp',
					'init/initMaps'
				], function(){
					b2.modalQuickViewInited = true;
                    initProductCarousel($this);
					$this.find('.thumbnails-frame').sly('reload');
					if (!b2.quickViewGenInfoInited) initGenInfoToggle($this);
					initProductCarousel($this);
					initMainGallery(this);
					if ($('.map').length) b2.init.maps(document);
				});
			break;
			case 'modal_custom-collection':
				
			break;
			case 'modal_city-select-panel': break; // BACK_END do not need citySwitchHandle
				require([
					'init/toggles/initCitySwitchHandle'
				], function(){
					b2.init.citySwitchHandle();
				});
			break;
		}
	});


	$('.btn-form-switch').click(function(e){
		e.preventDefault();
		$(this).closest('.modal').modal('hide');
	})


	$(target).find('#modal_basket').each(function(){
		var carousel = $('#modal_basket_additionals');
		var arrowPrev = carousel.children('.ctrl-arrow-wrap.prev').children('.ctrl-arrow');
		var arrowNext = carousel.children('.ctrl-arrow-wrap.next').children('.ctrl-arrow');
		var dots = carousel.children('.dots');
		var frame = carousel.children('.sly-frame');
		frame.sly({
			prevPage: arrowPrev,
			nextPage: arrowNext,
			pagesBar: dots,
		});
		
		$(this).on('shown.bs.modal', function(){
		 	frame.sly('reload');
		})
	})

	$('#modal_calc-delivery_city-switch').click(function(){
		$('#modal_calc-delivery').modal('hide');
	})

	b2.el.$settingsModal.on('show.bs.modal', function(){
		if (bs.slider) bs.slider.paused = true;
		if (!b2.modalSettingsInited){
			require([
				'libs/nouislider.min',
				'libs/sass.js_0.9.11/sass',
				'libs/tinycolor-min',
				'libs/jquery.minicolors.min',
				'libs/dragula/dragula.min',
				  'um/UmAccordeon',
				  'um/UmTabs',
				  'um/UmComboBlocks',
				'init/initCustomColorThemes',
				'init/modals/initModalSettings',
				'init/initDragSection',
				'init/modals/initDragSections'
			], function(){
				b2.modalSettingsInited = true;
				initModalSettings();
                initDragSections();
				initCustomColorThemes();
				$(window).trigger('modalSettingsInited');
				$(b2.s).trigger('set', ['bs_cur-settings-for', 'all', true]);
			});	
		} else {
			$(b2.s).trigger('set', ['bs_cur-settings-for', 'all', true]);
		}
	}).on('hide.bs.modal', function(){
		if (bs.slider) bs.slider.paused = false;
	}).one('show.bs.modal', function(){
		popClose($('.settings-info-popup'));
		$('#settings-toggle').removeClass('animated infinite');
	});

	var timeout;
	$(window).on('resize', function(){
		clearTimeout(timeout);
		timeout = setTimeout(function(){
			if ( $('#modal_basket.in').length ) $('#modal_basket_additionals').sly('reload');
		}, 200);
	});

}