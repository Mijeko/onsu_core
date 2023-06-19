b2.set.colorTheme = function(value){
	var curTheme = $('#current-theme');
	curTheme.attr('href', curTheme.data('path')+'theme_'+value+'.css');
	$('.theme-demo.active').removeClass('active');
	var theme = $('.theme-demo[data-theme="' + value + '"]');
	theme.addClass('active');
	// if (theme.closest('.theme-demos').hasClass('flat')) {
	// 	$body.addClass('flat-theme')
	// }
};

b2.set.customTheme = function(value){
	if (value) {
		b2.el.$customTheme.appendTo('body');
	} else {
		b2.el.$customTheme.detach();
	}
	$body.toggleClass('custom-theme', value);
};

b2.set.menuVisibleItems = function(value){
	b2.el.menu.updateVisible(value);
};

b2.set.footmenuVisibleItems = function(value){
	b2.el.footmenu.updateVisible(value);
};

b2.set.topLinePosition = function(value){
	$body.trigger('offsetChange');
};

function checkLimitSlider(){
	// if (b2.s.limitSliders){
	// 	if (b2.s.bigSliderWidth === "full") b2.el.inputSliderWidth.filter('[value="normal"]').attr('checked', true).change();
	// 	$('.footer-middle').css('background', '').children('.container').css('background', b2.s.footerBG);
	// } else {
	// 	$('.footer-middle').css('background', b2.s.footerBG).children('.container').css('background', '');
	// }
}
b2.set.containerWidth = function(value){
	switch (value){
		case 'full_width':
			b2.el.inputSliderWidth.filter('[value="normal"]').attr('disabled', true);
			if (b2.s.bigSliderWidth === "normal"){ b2.el.inputSliderWidth.filter('[value="full"]').attr('checked', true).change();}
		break;
		case 'container':
			b2.el.inputSliderWidth.filter('[value="normal"]').attr('disabled', false);
			// checkLimitSlider();
		break;
		default: ;
	}
	b2.el.specialSliders && b2.el.specialSliders.forEach(function(item, i, arr){
		item.updatePages();
	});
    b2.el.sitenav.updateState();
};
// b2.set.limitSliders = checkLimitSlider;

function checkCatalogAside(){
	if ($('#filter-at-side').children(':not(#flying-results-wrap)').length === 0 &&
		$('#catalog-at-side').children().length === 0 &&
		$('#catalog-aside').children().not('#filter-at-side, #catalog-at-side, .hidden').length === 0){
		$('#catalog-page').addClass('no-aside');
	}
}

b2.set.catalogPlacement = function(value){
	b2.el.$menu.appendTo('#catalog-at-'+value);
	b2.el.menu.updateState();
	switch (value){
		case 'top':
			b2.el.inputSliderWidth.filter('[value="narrow"]').attr('disabled', true);
			b2.el.inputSliderWidth.filter('[value="normal"], [value="full"]').attr('disabled', false);
			if (b2.s.bigSliderWidth !== "narrow" && b2.temp.bigSliderWidth !== 'narrow'){
				b2.el.inputSliderWidth.filter('[value="' + b2.temp.bigSliderWidth + '"]').attr('checked', true).change();
			} else b2.el.inputSliderWidth.filter('[value="full"]').attr('checked', true).change();
			$('.b-list').css('display', 'inline-block');
			checkCatalogAside();
			break;
		case 'side':
			b2.temp.bigSliderWidth = b2.s.bigSliderWidth;
			b2.el.inputSliderWidth.filter('[value="normal"], [value="full"]').attr('disabled', true);
			b2.el.inputSliderWidth.filter('[value="narrow"]').attr('disabled', false);
			if ( b2.s.bigSliderWidth !== "narrow" ){
				b2.el.inputSliderWidth.filter('[value="narrow"]').attr('checked', true).change();
			}
			$('#catalog-page').removeClass('no-aside');
			$('.b-list').css('display', '');
			break;
		default:
			console.log('some problem inside catalogPlacement');
	};
};

b2.set.filterPlacement = function(value, data){
	$('#filter-at-' + data.prevValue).children().appendTo('#filter-at-' + value);
	(value === 'top') ? checkCatalogAside() : $('#catalog-page').removeClass('no-aside');
};

function changeSiteBackground(field){
    var $this = field,
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
}

b2.set.siteBackground = function(value){
    switch (value){
        case 'pattern':
            $('#settings_body-pattern').val(b2.s.bodyPattern);
            changeSiteBackground( $('#settings_body-pattern') );

            // release of the active element
            $('.site-background[data-option="pattern"] li.active').removeClass('active');
            $('.site-background[data-option="pattern"] li[data-value="' + b2.s.bodyPattern + '"]').addClass('active');
            break;
        case 'gradient':

            break;
        case 'color':
            $('#settings_body-color').val(b2.s.bodyColor);
            $('body').css('background', b2.s.bodyColor);
            break;
        case 'image':
            $('#settings_body-image').val(b2.s.bodyImage);
            changeSiteBackground( $('#settings_body-image') );

            // release of the active element
            $('.site-background[data-option="image"] li.active').removeClass('active');
            $('.site-background[data-option="image"] li[data-value="' + b2.s.bodyImage + '"]').addClass('active');
            break;
    }
};


b2.set.headerVersion = function(value, data){
	if ( value === 'v3' ){
		$('#catalog-at-top').removeClass('catalog-at-top').addClass('catalog-at-side minified container');
		$('[data-name="catalog-placement"]').filter('[value="top"]').attr('checked', true).change();
		$('[data-name="catalog-placement"]').filter('[value="side"]').attr('disabled', true);
	} else if ( data.prevValue === 'v3' ) {
		$('#catalog-at-top').removeClass('catalog-at-side minified container').addClass('catalog-at-top');
		$('[data-name="catalog-placement"]').filter('[value="side"]').attr('disabled', false);
	};
    b2.el.menu.updateState();
    b2.el.sitenav.updateState();
};

b2.set.sitenavType = function(value){
    b2.el.sitenav.updateState();
};

b2.set.bigSliderType = function(value){
	switch (value){
		case 'pro':
			$('.slider-images').hide();
			$('.slider-pro_slide-settings').show();
			$('#slider-width-settings').show();
			$('#big-slider-wrap').show();
			bs.dummy.media.add(bs.dummy.text).show();
			b2.el.inputCatalogPlacement.filter('[value="side"]').attr('disabled', false);
		break;
		case 'normal':
			$('.slider-images').show();
			$('.slider-pro_slide-settings').hide();
			$('#slider-width-settings').show();
			$('#big-slider-wrap').show();
			bs.dummy.media.add(bs.dummy.text).hide();
			b2.el.inputCatalogPlacement.filter('[value="side"]').attr('disabled', false);
		break;
		case 'disabled':
			$('.slider-images').hide();
			$('.slider-pro_slide-settings').hide();
			$('#slider-width-settings').hide();
			$('#big-slider-wrap').hide();
			bs.dummy.media.add(bs.dummy.text).hide();

			b2.el.inputCatalogPlacement.filter('[value="side"]').attr('disabled', true);
			if ( b2.s.catalogPlacement === 'side' ){
				b2.el.inputCatalogPlacement.filter('[value="top"]').attr('checked', true).change();
			}
		break;
		default:
			console.log('wrong slider type');
	};
};

b2.set.categoriesView = function(value){
    var $frame = $('#categories .frame');

    switch (value) {
        case 'list':
            $frame.sly('reload');
            break;
        case 'blocks':
            $frame.find('.slides').css({
                'transform': '',
                'width': ''
            });
            break;
    };
};

b2.set.coolsliderEnabled = function(value){
	if ( value && b2.el.$coolSlider &&
	        b2.el.$coolSlider.length && typeof UmCoolSlider === 'function'){
		b2.el.coolSlider = new UmCoolSlider(b2.el.$coolSlider);
	} else if (b2.el.coolSlider) {
		b2.el.coolSlider.destroy();
	};
};

b2.set.sbMode = function(value){
	if ( b2.el.specialBlocks ) b2.el.specialBlocks.switchMode(value);
};

b2.set.sbModeDefExpanded = function(value){
	if ( b2.el.specialBlocks ) b2.el.specialBlocks.defExpand = ( value ) ? 'all' : 0;
};
		
b2.set.productInfoMode = function(value){
	if ( b2.el.productInfoSections ) b2.el.productInfoSections.switchMode(value);

    if (value !== 'full') $('[data-product-info-mode]').removeClass('full').addClass('tabs');
    else $('[data-product-info-mode]').removeClass('tabs').addClass('full');
};
		
b2.set.productInfoModeDefExpanded = function(value){
	if ( b2.el.productInfoSections ) b2.el.productInfoSections.defExpand = ( value ) ? 'all' : 0;
};

b2.set.productAvailability = function(value){
    var status_info = $('.product-page .buy-block-origin .availability-info .when-in-stock .info-tag'),
        tabs = $('.product-page .product-info-sections');

    if ( value !== 'status' ) {
        // destroy popup and tooltip info
        status_info.attr('data-popup-tmp', status_info.attr('data-popup'));
        status_info.removeAttr('data-tooltip').removeAttr('data-popup');
    }

    if ( value !== 'tabs' ) {
        if ( tabs.find('.combo-link.active').attr('href') === '#availability' ) {
            if ( $('[data-product-info-mode="tabs"]') )
                tabs.find('.combo-link').eq(0).click();
        }
    }
};


b2.set.photoViewType = function(value){
	if ( value === "zoom" ){
		$('.product-photos').off('click.photoView');
		if (!b2.el.$productPhotoImg || !b2.el.$productPhotoImg.length) return;
		b2.el.$productPhotoImg.each(function() {
			var $wrap = $(this);

			$wrap.magnify({
				src: $wrap.attr('data-zoom'),
			});
		});
		$('.action.zoom').hide();
	} else if ( value === "modal" ){
		$('.action.zoom').show();
		if (!b2.el.$productPhotoImg || !b2.el.$productPhotoImg.length) return;
		b2.el.$productPhotoImg.each(function() {
			var $wrap = $(this);

			if ( $wrap.parent('.magnify').length > 0 ) {
				$wrap.siblings().remove();
				$wrap.unwrap().css('display', '');
			}
		});
		$('.product-photos').on('click.photoView', '.product-photo, .action.zoom', function(e) {
			if ( Modernizr.mq('(max-width: 767px)') ) return;

			var index = $(e.delegateTarget).find('.thumbnails-wrap.active .thumb.active').index();

			if ( -1 === index ) {
				if (!(typeof b2.el.$bigImgPrev !== 'undefined' && b2.el.$bigImgPrev.length)) {
					// if modal gallery is not inited yet
					b2.el.$bigImgModal.one('modal-gallery-inited', function() {
						b2.el.$bigImgPrev.add(b2.el.$bigImgNext).addClass('disabled'); // if there are no galleries
					});
				} else b2.el.$bigImgPrev.add(b2.el.$bigImgNext).addClass('disabled'); // if there are no galleries
			}
			else b2.el.$bigImgModal.find('.thumbnails-frame.active').sly('activate', index);
			b2.el.$bigImgModal.modal('show');
		});
	};
};

b2.set.bs_height = function(value, data){
	data.$elDOM.css('padding-bottom', value);
};

b2.set.menuHitsPos = function(value){
	if (value === "top") {
		b2.el.$menuHits.each(function(){
			var $hits = $(this);
			$hits.prependTo($hits.parent());
		})
	} else {
		b2.el.$menuHits.each(function(){
			var $hits = $(this);
			$hits.appendTo($hits.parent());
		})
	}
}	

bs.animIn = function(media, text, aMedia, aText, slide){
	// animation IN
	media.velocity('finish', true)
	.velocity('transition.' + aMedia + 'In', { 
		duration: 500,
		complete: function(){
			if ( !text || text.length === 0){
				if (slide) slide.trigger('slid.in');
			};
		},
	});
	text.velocity('finish', true)
	.velocity('transition.' + aText + 'In', { 
		duration: 500,
		complete: function(){
			if (slide) slide.trigger('slid.in');
		}
	});
};

function getBSTargetDOM(){
	if (!bs.slider){
		bs.cur.media = $();
		bs.cur.text = $();
		return false;
	}
	if (typeof bs.i === 'undefined') return;
	if (bs.i === 'all'){
		bs.cur.media = $(bs.slider.items)
			.children('.media').not('[data-locked="true"]');
		bs.cur.text = $(bs.slider.items)
			.children('.text').not('[data-locked="true"]');
	} else {
		bs.cur.media = $(bs.slider.items[bs.i]).find('.media');
		bs.cur.text = $(bs.slider.items[bs.i]).find('.text');
	}
}
b2.set.bs_curSettingsFor = function(value){
	var valueInt = parseInt(value);
	if ( isNaN(valueInt) && value !== 'all')  return;

	if (value === 'all'){
		bs.i = 'all';
		getBSTargetDOM();

		bs.curBlockInputs.text.attr('disabled', false);
		bs.dummy.text && bs.dummy.text.removeClass('hidden');
		bs.curBlockInputs.media.attr('disabled', false);
		bs.dummy.media && bs.dummy.media.removeClass('hidden').find('img').remove();

		syncDummy('text');
		syncDummy('media');

		bs.curBlockInputs[b2.s.bs_curBlock].prop('checked', true).change();
		return;
	} 

	bs.i = valueInt;
	if (bs.slider && bs.slider.activePage !== bs.i) bs.slider.changePage(bs.i);
	getBSTargetDOM();
	bs.noMedia = !(bs.cur.media && bs.cur.media.length);
	bs.noText = !(bs.cur.text && bs.cur.text.length);

	// CHECKING DISABLED
	if (bs.noText){
		bs.curBlockInputs.text.attr('disabled', true);
		bs.dummy.text.addClass('hidden');
	} else {
		bs.curBlockInputs.text.attr('disabled', false);
		bs.dummy.text.removeClass('hidden');

		syncDummy('text');
	}
	if (bs.noMedia){
		bs.curBlockInputs.media.attr('disabled', true);
		bs.dummy.media.addClass('hidden');
	} else {
		bs.curBlockInputs.media.attr('disabled', false);
		bs.dummy.media.find('img').remove();
		bs.dummy.media.append(bs.cur.media.find('img').clone());
		bs.dummy.media.removeClass('hidden');

		syncDummy('media');
	}

	if (bs.curBlock === 'text'){
		if (bs.noText){
			bs.curBlockInputs.media.prop('checked', true).change();
		} else {
			bs.curBlockInputs.text.prop('checked', true);
			b2.set.bs_curBlock('text', { prevValue: 'text'});
		}
	} else { //'media'
		if (bs.noMedia){
			bs.curBlockInputs.text.prop('checked', true).change();
		} else {
			bs.curBlockInputs.media.prop('checked', true);
			b2.set.bs_curBlock('media', { prevValue: 'media'})
		}
	}
};

function getParams(data, blockType){
	if (typeof bs.i === 'undefined') return;
	bs.i === 'all' ?
		$.extend(data, bs.defaults[blockType]) :
		$.extend(data, bs.slides[bs.i][blockType]);
}

function syncDummy(blockType){
	if (!bs.dummy[blockType]) return;
	var data = {};
	getParams(data, blockType);
	bs.dummy[blockType].attr('data-h-align', data['h-align']);
	bs.dummy[blockType].attr('data-v-align', data['v-align']);
	if (blockType === "text") bs.dummy[blockType].attr('data-text-align', data['text-align']);
	bs.dummy[blockType].css({
		left: data.limits.left,
		right: data.limits.right,
		top: data.limits.top ,
		bottom: data.limits.bottom
	})
}
		
b2.set.bs_curBlock = function(value, data){
	// console.log('changing block from', bs.curBlock, 'to', value);
	bs.curBlock = value;
	if (bs.dummy[data.prevValue]) bs.dummy[data.prevValue].removeClass('cur-block');
	if (bs.dummy[value]) bs.dummy[value].addClass('cur-block');

	var params = {};
	getParams(params, bs.curBlock);


	bs.hLimits && bs.hLimits.get(0).noUiSlider.set([params.limits.left, (100 - parseFloat(params.limits.right))]);
	bs.vLimits && bs.vLimits.get(0).noUiSlider.set([params.limits.top, (100 - parseFloat(params.limits.bottom))]);
	bs.hAlignInputs.filter('[value="' + params['h-align'] + '"]').prop('checked', true).change();
	bs.vAlignInputs.filter('[value="' + params['v-align'] + '"]').prop('checked', true).change();
	bs.animInput.val(params.anim).change();

	if (bs.curBlock === 'text'){
		bs.textAlignInput.filter('[value="' + params['text-align'] + '"]').prop('checked', true).change();
	}
	
};

function getBSTarget(arr, blockType){
	if (typeof bs.i === 'undefined') return;
	if (bs.i === 'all'){
		arr.push(bs.defaults[blockType]);
		bs.slides.forEach(function(slide){
			if (!slide.locked && slide[blockType] && !slide[blockType].locked) arr.push(slide[blockType]);
		})
	} else {
		if (bs.slides[bs.i][blockType]) arr.push(bs.slides[bs.i][blockType]);
	}
	// console.log('get bs target returning ', arr);
	return arr.length;
}
function setLimits(dir, value){
	var t = [];
	if (!getBSTarget(t, bs.curBlock)) return;

	if (dir === 'h'){
		// console.log('changing h limits on', bs.curBlock, 'to', value);
		t.forEach(function(slide){
			slide.limits.left = value[0];
			slide.limits.right = (100 - parseFloat(value[1])) + '%';
		})
		bs.cur[bs.curBlock].css({
			'left': value[0],
			'right': (100 - parseFloat(value[1])) + '%',
		})
	} else {
		t.forEach(function(slide){
			slide.limits.top = value[0];
			slide.limits.bottom = (100 - parseFloat(value[1])) + '%';
		})
		bs.cur[bs.curBlock].css({
			'top': value[0],
			'bottom': (100 - parseFloat(value[1])) + '%',
		})
	}
}
b2.set.hLimits = function(value){
	setLimits('h', value);
};
b2.set.vLimits = function(value){
	setLimits('v', value);
};

function setAlign(dir, value){
	var t = [];
	if (!getBSTarget(t, bs.curBlock)) return;
	
	t.forEach(function(slide){
		slide[dir] = value;
	})

	var target = bs.cur[bs.curBlock].add(bs.dummy[bs.curBlock]);
	target.attr('data-' + dir, value);
}
b2.set.bs_hAlign = function(value){
	setAlign('h-align', value);
};

b2.set.bs_vAlign = function(value){
	setAlign('v-align', value);
};

b2.set.bs_textAlign = function(value){
	var t = [];
	if (!getBSTarget(t, 'text')) return;
	
	t.forEach(function(slide){
		slide['text-align'] = value;
	})
	
	// dummy demo
	bs.cur.text.add(bs.dummy.text).attr('data-text-align', value);
};

b2.set.bs_anim = function(value){
	var t = [];
	if (!getBSTarget(t, bs.curBlock)) return;

	t.forEach(function(slide){
		slide.anim = value;
	})

	if (!bs.dummy.media || !bs.dummy.text || typeof bs.i === 'undefined') return;
	var demoSlide;
	if (bs.i === 'all') demoSlide = bs.defaults;
	else demoSlide = (bs.slides[bs.i]) ? bs.slides[bs.i] : bs.defaults;
	// dummy demo
	var aText =  (demoSlide.text) ?
					(demoSlide.text.anim || bs.defaults.text.anim) : bs.defaults.text.anim;
	var aMedia = (demoSlide.media) ? 
					(demoSlide.media.anim || bs.defaults.text.anim) : bs.defaults.text.anim;

	bs.dummy.media.add(bs.dummy.text).velocity('finish');
	bs.animIn(bs.dummy.media, bs.dummy.text, aMedia, aText);
};


b2.disableStores = function(infoTag){
	var $t = $(infoTag) || $(this),
		title = $t.attr('title') || $t.attr('data-original-title'),
		popup = $t.attr('data-popup'),
		tooltip = $t.attr('data-tooltip');
	if (typeof title !== 'undefined'){
		$t.attr('data-orig-title', title)
		if ($t.hasClass('avail-dot')){
			var newTitle = $t.data('text') + (b2.s.showStock ? $t.data('how-much') : '');
            if (isHover) $t.tooltip('hide');
            $t.attr('data-original-title', newTitle);
            if (isHover) $t.tooltip('fixTitle');
        } else {
            if (isHover) $t.tooltip('destroy');
        }
	}
	if (typeof popup !== 'undefined') $t.attr('data-orig-popup', popup).removeAttr('data-popup');
	if (typeof tooltip !== 'undefined') $t.attr('data-was-tooltip', true).removeAttr('data-tooltip');
}
b2.enableStores = function(infoTag){
	var $t = $(infoTag) || $(this),
		title = $t.data('orig-title'),
		popup = $t.data('orig-popup'),
		tooltip = $t.data('was-tooltip');
	if (typeof title !== 'undefined'){
        if (isHover) $t.tooltip('hide');
        $t.attr('data-original-title', title);
        if (isHover) $t.tooltip('fixTitle');
		$t.removeAttr('data-orig-title');
	};
	if (typeof popup !== 'undefined') $t.attr('data-popup', popup).removeAttr('data-orig-popup');
	if (typeof tooltip !== 'undefined') $t.attr('data-tooltip', '').removeAttr('data-was-tooltip');
}
b2.set.showStock = function(value){
	$('.avail-dot.when-in-stock').each(function(){
		var $t = $(this), 
			newTitle = $t.data('text') + (value ? $t.data('how-much') : '');
        if (isHover) $t.tooltip('hide');
        $t.attr('data-original-title', newTitle);
        if (isHover) $t.tooltip('fixTitle');
	});
}
b2.set.stores = function(value){
	switch (value){
		case false:
		case 'disabled':
			$('.when-in-stock .info-tag, .avail-dot.when-in-stock').each(function(){
				b2.disableStores(this);
			});
		break;
		case true:
		case 'enabled':
			$('.when-in-stock .info-tag, .avail-dot.when-in-stock').each(function(){
				b2.enableStores(this);
			});
		break;
		default: console.log('b2.set.stores() switch default');
	}
}
b2.set.additionalPricesEnabled = function (value) {
    updateAdditionalPrices();
    b2.resizeHandlers.push(updateAdditionalPrices);
}
function updateAdditionalPrices() {
    $.fn.baron && $('.additional-prices-wrap').find('.scroller').baron().update();
}

$('#settings_blocks .collapse').on('show.bs.collapse hide.bs.collapse', function(e){
	console.log('32143');
	$(this).closest('fieldset').toggleClass('no-border');
});