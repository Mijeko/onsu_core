$(document).ready(function () {
	//BACK-END custom settings panel behavior (START)
	var b2setStylingType = b2.set.stylingType;
	b2.set.stylingType = function (val) {
		$('.theme-demos').each(function () {
			var _ = $(this);
			if (_.hasClass(val)) {
				_.show();
			} else {
				_.hide();
			}
		});

		var activeThemeName = $('.theme-demo.active').data('theme');
		activeThemeName = activeThemeName.replace(/-\w+/i, '-' + val);
		if ($('.' + activeThemeName).length) {
			$('.' + activeThemeName).click();
		}
		else {
			$('.theme-demos.' + val).find('li.theme-demo:first').click();
		}
		if (activeThemeName.match(/\-flat$/)) {
			$('body').removeClass('more_bold');
		} else {
			$('body').addClass('more_bold');
		}
		if (typeof b2setStylingType == "function") b2setStylingType(val);
	};
	b2.rel.stores.push({
		selector: '#settings_show-stock',
		states: {
			true: [{
				action: 'prop',
				arguments: ['disabled', true]
			}],
			false: [{
				action: 'prop',
				arguments: ['disabled', false]
			}]
		}
	});
	b2.rel.stores_mobile = [{
		selector: '#settings_show-stock_MOBILE',
		states: {
			true: [{
				action: 'prop',
				arguments: ['disabled', true]
			}],
			false: [{
				action: 'prop',
				arguments: ['disabled', false]
			}]
		}
	}];
    b2.set.captchaRegistration = function() {
        $('input[name="SETTINGS[captcha-quick-buy]"]').prop('checked',true);
    };

    b2.set.customTheme = function(value){
        var newTheme = $('.theme-demo.active').attr('data-theme');
        if (value) {
            RZB2.themeColor = $('[name="theme-main-color"]').val();
            b2.el.$customTheme.appendTo('body');
        } else {
            newTheme = newTheme.replace('-flat','');
            newTheme = newTheme.replace('-skew','');
            RZB2.themeColor = RZB2.arrDefColors[newTheme];
            b2.el.$customTheme.detach();
        }
        $body.toggleClass('custom-theme', value);
	};
	b2.set.themeButton = function(value){
		$('#settings_theme-button').val(value);
	};
	//BACK-END custom settings panel behavior (END)

	// color-theme switch
	$('.theme-demo').on('click', function () {
		if (!$(this).hasClass('active')) {
			$('.theme-demo.active').removeClass('active');
			$(this).addClass('active');

			var newTheme = $(this).attr('data-theme');

			$('input#theme-demo').val(newTheme);

			if (newTheme.match(/\-flat$/)) {
				$('body').removeClass('more_bold');
			} else {
				$('body').addClass('more_bold');
			}
            newTheme = newTheme.replace('-flat','');
            newTheme = newTheme.replace('-skew','');

            RZB2.themeColor = !$('[data-name="custom-theme"]').prop('checked') ? RZB2.arrDefColors[newTheme] : RZB2.themeColor;
        } else return;
	});

	var $setPanel = $('#settings-panel-cblocks');
	var $setPanelSubmit = $('#settings-panel-submit');

	$.fn.serializeAssoc = function () {
		var obj = {
			result: {},
			add: function (name, value) {
				var tmp = name.match(/^(.*)\[([^\]]*)\]$/);
				if (tmp) {
					var v = {};
					if (tmp[2])
						v[tmp[2]] = value;
					else
						v[$.count(v)] = value;
					this.add(tmp[1], v);
				} else if (typeof value == 'object') {
					if (typeof this.result[name] != 'object') {
						this.result[name] = {};
					}
					this.result[name] = $.extend(this.result[name], value);
				} else {
					this.result[name] = value;
				}
			}
		};
		var ar = this.serializeArray();
		for (var i = 0; i < ar.length; i++) {
			obj.add(ar[i].name, ar[i].value);
		}
		return obj.result;
	};
	var setCookie = function(name, value, prefix) {
		var date = new Date();
		date.setFullYear(date.getFullYear() + 1);
		document.cookie = prefix + name + '=' + value + ';domain=.'+ window.location.hostname + '; path=/; expires=' + date.toUTCString();
	};
	var fillBSSettings = function(object, prefix) {
		for (var key in object) {
			switch (typeof object[key]) {
				case "object":
					fillBSSettings(object[key], prefix + key + '_');
					break;
				case "function":
					break;
				default:
					$(prefix + key).val(object[key]);
					break;
			}
		}
	};
	$setPanelSubmit.on('click', function (e) {
		fillBSSettings(bs.defaults, '#settings_bs_');
		if ("DEMO" in RZB2 && RZB2.DEMO) {
			var arSettings = $setPanel.serializeAssoc();
			$.ajax({
				type: 'POST',
				url: SITE_DIR + 'ajax/composite.php',
				data: arSettings,
				dataType: 'json',
				success: function(obj) {
					$setPanel.submit();
				}
			});
		} else {
			$setPanel.submit();
		}
	});
	$setPanel.on('click', '.set-color li', function (e) {
		var $this = $(this),
			$parent = $this.closest('.set-color'),
			$tar = $parent.data('target'),
            $body = $('body');
        $body.attr('data-site-background', '');
        $body.find('[name="SETTINGS[type_bg_ground]"]').val('pattern');
		if (!$tar) {
			$tar = $('#settings_'+ $parent.data('name'));
			$parent.data('target', $tar);
		}
		$tar.val($this.data('value')).trigger('change');
	});
    $setPanel.on('click', '.site-image li', function (e) {
        var $this = $(this),
          	val = $this.data('value'),
            $parent = $this.closest('.site-image'),
            $tar = $parent.data('target'),
			$img = $('.full-fixed-bg'),
            $body = $('body');
        $body.find('[name="SETTINGS[type_bg_ground]"]').val('image');
        $body.attr('data-site-background', 'image');
        if (!$img.length) {
            $('<img>').addClass('full-fixed-bg').attr('src',val).appendTo('body');
        } else{
        	$img.attr('src',val);
		}

        if (!$tar) {
            $tar = $('#settings_'+ $parent.data('name'));
            $parent.data('target', $tar);
        }
        $tar.val($this.data('value')).trigger('change');
    });
	$setPanel.on('change', '.minicolors', function (e) {
		var $this = $(this),
			$tar = $this.data('target'),
			$body = $('body');
        $body.find('[name="SETTINGS[type_bg_ground]"]').val('color');
        $body.attr('data-site-background', '');
		if (!$tar) {
			$tar = $('#settings_'+ $this.data('name'));
			$this.data('target', $tar);
		}
		$tar.val($this.val()).trigger('change');
	});
	$setPanel.on('change', '.color-setting', function (e) {
		var $this = $(this),
			obj = $this.data('obj');
		if (!obj) {
			obj = $($this.data('selector'));
			$this.data('obj', obj);
		}
		if ($this.data('selector')) {
			obj.css($this.data('property'), $this.val());
		}
	});
	$setPanel.on('change', '.type-choose', function (e) {
		var $this = $(this),
			$parent = $this.closest('.setting-content');
		$parent.find('.data-type').hide();
		var $curElem = $parent.find('.data-type.type-' + $this.val());
		$curElem.show();
		$curElem.find('.set-color').trigger('change');
	});
	$setPanel.on('show.bs.collapse hide.bs.collapse', '.collapse', function(e){
		$(this).closest('fieldset').toggleClass('no-border');
	});
	$('#cancel-settings').hover(
		function(){
			b2.el.$settingsForm.data('reset', true);
		},
		function(){
			b2.el.$settingsForm.data('reset', false);
		}
	);

	/********** PRESETS **********/
	$('#settings_blocks label.checkbox-styled').on('click', function(){
		$('input.statebox[data-name="preset"]').prop('checked', false);
	});
	var presets = {
		// commons
		'currency-switcher':        {easy: false, medium: true,  hard: true},
		'block_main-menu-elem':     {easy: false, medium: true,  hard: true},
		'menu-show-icons':          {easy: false, medium: false, hard: true},
		'block_pricelist':          {easy: false, medium: false, hard: true},
		'quick-view':               {easy: false, medium: true,  hard: true},
		'quick-view-chars':         {easy: false, medium: true,  hard: true},
		'backnav_enabled':          {easy: false, medium: false, hard: true},
		'show_discount_percent':    {easy: false, medium: true,  hard: true},
		'stores':                   {easy: false, medium: false, hard: true},
		'show-stock':               {easy: false, medium: false, hard: true},
		'block_show_stars':         {easy: false, medium: true,  hard: true},
		'block_show_geoip':         {easy: false, medium: true,  hard: true},
		'block_show_compare':       {easy: true,  medium: true,  hard: true},
		'block_show_favorite':      {easy: true,  medium: true,  hard: true},
		'block_show_oneclick':      {easy: false, medium: true,  hard: true},
		'block_show_article':       {easy: false, medium: true,  hard: true},
		'block_show_sort_block':       {easy: true, medium: true,  hard: true},
		'block_show_gallery_thumb': {easy: false, medium: false, hard: true},
		'block_show_ad_banners':    {easy: false, medium: true,  hard: true},
		'block_worktime':           {easy: false, medium: true,  hard: true},
		'block_search_category':        {easy: false, medium: true,  hard: true},
		'block_search_category_MOBILE': {easy: false, medium: false, hard: true},
		'block_menu_count':             {easy: false, medium: true,  hard: true},
		'block_menu_count_MOBILE':      {easy: false, medium: false, hard: true},
		'block-buy_button_MOBILE':      {easy: false, medium: false, hard: true},
		// index
		'block_home-main-slider':    {easy: true,  medium: true,  hard: true},
		'block_home-rubric':         {easy: false, medium: true,  hard: true},
		'block_home-cool-slider':    {easy: false, medium: true,  hard: true},
		'cool_slider_show_names':    {easy: false, medium: true,  hard: true},
		'coolslider_show_stickers':  {easy: false, medium: false, hard: true},
		'block_home-specials':       {easy: true,  medium: true,  hard: true},
		'block_home-specials_icons': {easy: false, medium: true,  hard: true},
		'block_home-specials_count': {easy: false, medium: false, hard: true},
		'block_home-our-adv':        {easy: false, medium: true,  hard: true},
		'block_home-feedback':       {easy: true,  medium: true,  hard: true},
		'catchbuy_color_heading':    {easy: false, medium: true,  hard: true},
		'block_home-catchbuy':       {easy: false, medium: true,  hard: true},
		'block_home-news':           {easy: true,  medium: true,  hard: true},
		'block_home-voting':         {easy: false, medium: true,  hard: true},
		'block_home-brands':         {easy: false, medium: true,  hard: true},
		'block_home-vk':             {easy: false, medium: false,  hard: true},
		'block_home-ok':             {easy: false, medium: false, hard: true},
		'block_home-fb':             {easy: false, medium: false,  hard: true},
		'block_home-tw':             {easy: false, medium: false,  hard: true},
		'block_home-flmp':             {easy: false, medium: false,  hard: true},
		// detail
		'block_detail-addtoorder':    {easy: false, medium: true,  hard: true},
		'block_detail-similar':       {easy: false, medium: false, hard: true},
		'block_detail-similar-view':  {easy: false, medium: false, hard: true},
		'block_detail-similar-price': {easy: false, medium: true,  hard: true},
		'block_detail-recommended':   {easy: false, medium: true,  hard: true},
		'block_detail-viewed':        {easy: false, medium: true,  hard: true},
		'block_detail-delivery':      {easy: false, medium: false, hard: true},
		'block_detail-view3d':        {easy: false, medium: true,  hard: true},
		'block_detail-gift-products': {easy: false, medium: true,  hard: true},
		'block_detail-gift-main-products': {easy: false, medium: true,  hard: true},
		'detail_catchbuy_slider':          {easy: false, medium: true,  hard: true},
		'block_detail_print':              {easy: false, medium: false, hard: true},
		'block_detail_price_updated':      {easy: false, medium: true,  hard: true},
		'block_detail_review':             {easy: false, medium: true,  hard: true},
		'block_detail_short_info_under_image': {easy: false, medium: true,  hard: true},
		'block_detail_feedback':               {easy: false, medium: false, hard: true},
		'block_detail_socials':                {easy: false, medium: true,  hard: true},
		'block_detail_gamification':           {easy: false, medium: true,  hard: true},
		'block_detail_gamification_MOBILE':    {easy: false, medium: false, hard: true},
		// section
		'block_show_comment_count':{easy: false, medium: false, hard: true},
		'catalog_catchbuy_slider': {easy: false, medium: false, hard: true},
		'block_list-view-block':   {easy: true,  medium: true,  hard: true},
		'block_list-view-list':    {easy: false, medium: true,  hard: true},
		'block_list-view-table':   {easy: true,  medium: true,  hard: true},
		'block_list-sub-sections': {easy: false, medium: true,  hard: true},
		'block_list-section-desc': {easy: false, medium: true,  hard: true},
		'block_list-hits':         {easy: false, medium: false, hard: true},
		'table-units-col':         {easy: false, medium: true,  hard: true},
		'table-units-col_MOBILE':  {easy: false, medium: false, hard: true},
		// basket
		'block_basket-gift-products': {easy: false, medium: true, hard: true},
		// search
		'block_search-viewed':     {easy: false, medium: true,  hard: true},
		'block_search-bestseller': {easy: false, medium: false, hard: true},
		'block_search-recommend':  {easy: false, medium: true,  hard: true},
		// 404
		'block_404-viewed':     {easy: false, medium: true,  hard: true},
		'block_404-bestseller': {easy: false, medium: false, hard: true},
		'block_404-recommend':  {easy: false, medium: true,  hard: true},
	};
	b2.rel.preset = [];
	for (var key in presets) {
		var relObj = {
			selector: '#settings_' + key,
			states: {}
		};
		if ($(relObj.selector).is(':disabled')) continue;

		for (var stateKey in presets[key]) {
			relObj.states[stateKey] = [{
				action: 'prop',
				arguments: ['checked', presets[key][stateKey]]
			}];
		}
		b2.rel.preset.push(relObj);

		if (typeof presets[key + '_MOBILE'] != "undefined") continue;

		var relObjMobile = {
			selector: relObj.selector + '_MOBILE',
			states: relObj.states
		};
		var $relObjMobile = $(relObjMobile.selector);
		if ($relObjMobile.length < 1)      continue;
		if ($relObjMobile.is(':disabled')) continue;

		b2.rel.preset.push(relObjMobile);
	}
	/********** PRESETS **********/
});

$(window).on('modalSettingsInited', function(){
	b2.el.$settingsForm.data('reset', false);
	b2.el.$settingsModal.on('shown.bs.modal', function(){
		b2.el.$settingsForm.data('reset', false);
	});
	b2.el.inputSliderWidth = $('input[data-name="big-slider-width"]');

	//need to trigger according b2.set.~ and b2.rel.~ methods
	$('input[data-name="catalog-placement"]:checked').change();
	$('input[data-name="container-width"]:checked').change();
	$('#settings_custom-theme').change();
	$('#settings_stores:checked').change();
	$('#settings_stores_MOBILE:checked').change();
    $('[data-name="header-version"]:checked').change();
});

$(window).on('customColorThemeCompiled', function(e, result){
	$('#theme-custom').val(result.text);
	$('#settings_theme-main-color').val($('#custom-theme-demos-wrap').find('input.minicolors-custom').val());
});
