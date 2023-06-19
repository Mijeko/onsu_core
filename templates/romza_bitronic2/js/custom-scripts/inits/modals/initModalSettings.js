function initModalSettings(){
	// SETTINGS MODAL PROCESSING
	b2.el.inputSliderWidth = $('input[name="big-slider-width"]');
	b2.el.inputCatalogPlacement = $('input[name="catalog-placement"]');
	
	bs.dummy.$el = $('.big-slider.dummy');
	if ( bs.dummy.$el.length > 0 ){
		bs.dummy.media = bs.dummy.$el.find('.media');
		bs.dummy.text = bs.dummy.$el.find('.text');
	}
	bs.curSettingsFor = 'all';
	bs.curSettingsForInput = $('[name="bs_cur-settings-for"]');
	bs.curBlockInputs = {};
	bs.curBlockInputs.text = $('[name="bs_cur-block"][value="text"]');
	bs.curBlockInputs.media = $('[name="bs_cur-block"][value="media"]');
	bs.hAlignInputs = $('[name="bs_h-align"]');
	bs.vAlignInputs = $('[name="bs_v-align"]');
	bs.textAlignInput = $('[name="bs_text-align"]');
	bs.textAlignWrap = $('#bs_text-align-wrap');
	bs.animInput = $('[name="bs_anim"]');

	var settingsComboBlocks = $('#settings-panel-cblocks'),
		settingsView = (settingsComboBlocks.length) ? 
			new UmComboBlocks(settingsComboBlocks, {
				bp: 767,
			}) : null;
	
	$('.settings-view-link').click(function(){
		var _ = $(this);
		if ( _.hasClass('active')) return;

		_.addClass('active').siblings('.active').removeClass('active');
		var mode = _.attr('data-mode');
		settingsView.switchMode(mode);
	})

	$('.settings-to-defaults').click(function(){
		var form = $(this).closest('.modal-settings').children('.form_settings');
		form.find('input[type="radio"][data-default]').attr('checked', true).change();
		form.find('option[data-default]').attr('selected', true).change();
		form.find('input[type="text"], input[type="hidden"]').each(function(){
			var _ = $(this);
			var defVal = _.attr('data-default');
			if ( defVal ) _.val(defVal).change();
			if ( _.hasClass('minicolors') ) _.keyup(); // to trigger color change
		});
		//from backend with love ^_^
		form.find('input[type="checkbox"]').each(function (e) {
			var $this = $(this);
			var defVal = $this.attr('data-default') | 0;
			$this.prop('checked', (defVal == 1)).change();
		});
        b2.init.selects(form);
		//form.find('.theme-demo[data-default]').trigger('click');
		//form.submit();
	});
// ATTENTION BACK-END HAS BEEN THERE
	$('.simple-slider').each(function(){
		var $t = $(this),
			dataName = $t.data('name'),
			postfix = $t.data('postfix');
		if (typeof postfix == 'undefined') {
			postfix = '';
		}
/*	var $bigHeightSlider = $('#big-height-slider');
	noUiSlider.create($bigHeightSlider.get(0), {*/ noUiSlider.create(this, {
/*		start: b2.s.bs_height,*/   start: $t.data('start'),
		connect: "lower",
/*		step: 0.01,*/              step: $t.data('step'),
		range: {
/*			'min': 20,*/             'min': $t.data('min'),
/*			'max': 50*/              'max': $t.data('max')
		},
		format: wNumb({
			decimals: 2,
/*			postfix: '%'*/           postfix: postfix
		})
	}).on('update', function(values, handle){
/*		$('#bs_height-input').val(values[handle]);*/ $('#settings_' + dataName).val(values[handle]); if($t.data('set')) {
/*		$('[data-bs_height]').css('padding-bottom', values[handle]);*/ $('[data-' + dataName + ']').css('padding-bottom', values[handle]);
		}
	});
/*	$('#bs_height-input').on('change', function(){*/      $('#settings_' + dataName).on('change', function(){
/*		$bigHeightSlider.get(0).noUiSlider.set(this.value);*/ $t.get(0).noUiSlider.set([this.value, null]);
	});
	});
// END OF BACK-END INTRUSION
	$('.range-slider.percents').each(function(){
		var $t = $(this),
			$inputLower = $t.siblings('.textinput.limit-start'),
			$inputUpper = $t.siblings('.textinput.limit-end');
		var orient =  ($t.parent().hasClass('vertical')) ? 'vertical' : 'horizontal';
		noUiSlider.create(this, {
			start: [0, 100],
			connect: true,
			behaviour: 'snap',
			step: 1,
			orientation: orient,
			tooltips: true,
			range: {
				'min': 0,
				'max': 100
			},
			format: wNumb({
				postfix: '%'
			})
		}).on('update', function(values, handle){
			$t.trigger('change');
			if (handle){
				// handle is 1 == upper
				//$inputUpper.val(values[handle]);
			} else {
				// handle is 0 == lower
				$inputLower.val(values[handle]);
			}
		});

		$inputLower.on('change', function(e){
			$t.get(0).noUiSlider.set([this.value, null]);
		});
		$inputUpper.on('change', function(e){
			$t.get(0).noUiSlider.set([null, (100 - this.value)]);
		});

		$t.find('.noUi-handle').append('<div class="noUi-arrow"></div>');

		if ( $t.hasClass('h-limits') ){
			bs.hLimits = $t;
			$t.get(0).noUiSlider.on('update', function(values, handle, unformatted){
				var newVal = (100 - unformatted[1]) + '%';
				$inputUpper.val(newVal);
				bs.dummy[b2.s.bs_curBlock] && bs.dummy[b2.s.bs_curBlock].css({
					'left': this.get()[0],
					'right': newVal
				});
			});
		} else if ( $t.hasClass('v-limits') ){
			bs.vLimits = $t;
			$t.get(0).noUiSlider.on('update', function(values, handle, unformatted){
				var newVal = (100 - unformatted[1]) + '%';
				$inputUpper.val(newVal);
				bs.dummy[b2.s.bs_curBlock] && bs.dummy[b2.s.bs_curBlock].css({
					'top': this.get()[0],
					'bottom': newVal
				});
			});
		}
	});

	$('input.minicolors').each(function(){
		var defValue = $(this).attr('data-default');
		$(this).minicolors({
			position: 'bottom right',
			dataUris: false,
			control: 'wheel',
			defaultValue: defValue,
		});
	});
}