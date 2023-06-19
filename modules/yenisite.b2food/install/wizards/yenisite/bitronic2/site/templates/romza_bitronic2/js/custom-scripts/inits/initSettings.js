function initSettings(){
//	SETTINGS SET HANDLER
	$(b2.s).on('set', function(e, name, value, byInput){
		// console.log(e, name, value);
		var nameCC = $.camelCase(name);
		//if ( b2.s[nameCC] === value ) return false;

		// saving previous, setting new value
		var prevValue = b2.s[nameCC];
		b2.s[nameCC] = value;

		// saving to local storage if we're on frontend demo
		b2.s.isFrontend && localStorage.setItem('b2.s.' + nameCC, b2.s[nameCC]);

		// ensuring that b2.changedS contains only changed values
		if ( value === b2.initialS[nameCC]) delete b2.changedS[name];
		else if (typeof b2.initialS[nameCC] !== "undefined") b2.changedS[name] = value;

		// looking for associated DOM element and updating data-<name>
		var $elDOM = $('[data-' + name + ']');
		if ( $elDOM.length > 0 ) $elDOM.attr('data-' + name, value);
		// setting associated input if set was triggered not by input
		if ( !byInput ){
			var $input = $('[name=' + name + '], [data-name=' + name + ']');
			var inputType = ( $input.is('select') ) ? 'select' : $input.attr('type');
			switch (inputType){
				case 'select':
					$input.val(value).change(); // .change for styling plugins
					break;
				case 'text':
					$input.val(value);
					break;
				case 'radio':
					$input.filter('[value=' + value +']').prop('checked', true);
					break;
				case 'checkbox':
					$input.prop('checked', value);
					break;
				default:
					console.log('unknown input type in settings set: ', inputType);
			}
		}

		// switching states of related elements/inputs
		b2.rel[nameCC] && b2.rel[nameCC].forEach(function(element){
			setRelated(value, element);
		});
		// property-specific actions and DOM manipulations via helper functions
		if (typeof b2.set[nameCC] === 'function'){
			b2.set[nameCC](value, {prevValue: prevValue, $elDOM: $elDOM});
		};

	});
//	END OF SETTINGS SET HANDLER

//	SETTINGS CHANGES DISPATCHER FROM FORM
	b2.el.$settingsForm = $('#settings-panel-cblocks').on('change', function(e){
		var $t = $(e.target),
			name = $t.data('name') || $t.attr('name'),
			value = $t.val();

		if (value === undefined || name === undefined) return;
		if (name === 'v-limits' || name === 'h-limits') value = $t.get(0).noUiSlider.get();
		
		if (value === "false") value = false;
		else if (value === "true") value = true;

		if ($t.is('input:checkbox')) value = $t.is(':checked');

		//console.log('form changed by', name, 'with value ', value);

		$(b2.s).trigger('set', [name, value, true]);
	});
//	END OF CHANGES DISPATCHER FROM FORM

//	MODAL HANDLING
	var submit = true;
	b2.el.$settingsModal.on('show.bs.modal', function(){
		// saving copy of settings on moment of opening settings panel
		if (Object.keys(b2.initialS).length < 1) {
			b2.initialS = $.extend({}, b2.s);
		}
		b2.el.$settingsForm.data('reset', true);
	}).on('hide.bs.modal', function(){
		if (!!b2.el.$settingsForm.data('reset')) {
			for (var name in b2.changedS){
				// we know for sure that b2.changedS contains _changed_
				// values, so just reset them
				$(b2.s).trigger('set', [name, b2.initialS[$.camelCase(name)]]);
			}
			b2.changedS = {};
		}
	});
	b2.el.$settingsForm.on('submit', function(){
		b2.el.$settingsForm.data('reset', false);
		b2.el.$settingsModal.modal('hide');

		return submit; // prevent (or not) default submitting
	});
	$('#submit-settings').on('click', function(){
		submit = false; //prevent form from being actually submitted, BACK_END uses another button
		b2.el.$settingsForm.submit();
	});
//	END OF MODAL HANDLING

//	FRONTEND processing via localStorage
    if (b2.s.isFrontend){
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

        for (var nameCC in b2.s){
            var storedVal = localStorage.getItem('b2.s.' + nameCC),
                curVal = b2.s[nameCC];

            if (storedVal === 'true') storedVal = true;
            if (storedVal === 'false') storedVal = false;

            if (storedVal && (storedVal !== curVal)){
                var dashedName = nameCC.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
                $(b2.s).trigger('set', [dashedName, storedVal]);
            }
        }
    }
// END OF FRONTEND demo processing
}

$('#settings_blocks .collapse').on('show.bs.collapse hide.bs.collapse', function(e){
	$(this).closest('fieldset').toggleClass('no-border');
});