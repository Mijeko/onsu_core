//create closure for one-time executable
var initCustomColorThemes = (function() {
	var inited = false;
	var compiled = false;

	var $applyMinicolors = $('#btn-custom-theme');
	b2.el.$customTheme = $('#custom-theme');

	if (b2.el.$customTheme.length) {
		compiled = true;
	} else {
		b2.el.$customTheme = $('<style id="custom-theme">');
	}

	$('.minicolors-custom').minicolors({
		position: 'bottom right',
		dataUris: false,
		control: 'wheel',
		defaultValue: $(this).attr('data-default'),
		change: function(value, opacity){
			$(this).closest('.minicolors').find('.minicolors-panel').addClass('apply');
			$('#custom-theme-demos-wrap').find('.custom-theme-repeat').addClass('hidden');
		},
		show: function() {
			$applyMinicolors.removeClass('hide');
		},
		hide: function() {
			$applyMinicolors.addClass('hide');
		}
	})
	.closest('.minicolors').append($applyMinicolors);

	return function () {
		if (inited) return true;

		inited = true;

		if (typeof Sass == 'undefined') {
			console.log('sass is not loaded');
			return false;
		}

		var sass = new Sass(b2.s.sassWorkerUrl),
			base = '../../../scss',
			directory = '',
			filesCombined = [
				'_theme-combined_skew.scss',
				'_theme-combined_flat.scss',
			],
			newStyles = [],
			sassCombinedStyle = '';

		// load styles and init minicolors plugin
		sass.preloadFiles(base, directory, filesCombined, function(){
			sass.readFile(filesCombined, function(result){
				for (var i = 0; i < filesCombined.length; i++)
					newStyles[i] = result[filesCombined[i]];
			});
		});

		var makeThemeFromColor = function(value, callback) {
			$('#btn-custom-theme, .btn-custom-theme-repeat').addClass('compile');

            RZB2.themeColor = value;
			var type = $('[data-styling-type]').attr('data-styling-type');
			var color = tinycolor(value).toHsv();
			var newColor = '\
				$main-color-h:' + color.h + ';\
				$main-color-s:' + (color.s * 100) + ';\
				$main-color-v:' + (color.v * 100) + ';';

			sassCombinedStyle = type;

			sass.compile(newColor + newStyles[type === 'skew' ? 0 : 1], function(result) {
                result.text = typeof result.text == 'string' ? result.text.replace(/select/g,'#NEED_REPLACE#') : result.text;
				b2.el.$customTheme.html(result.text);
				compiled = true;
				// trigger event to save result anywhere else
				$(window).trigger('customColorThemeCompiled', [result]);
				$('#btn-custom-theme, .btn-custom-theme-repeat').removeClass('compile');
				if (typeof callback == "function") callback();
			});
		}

		$applyMinicolors.on('click', function(){
			if ($(this).hasClass('compile')) return;

			makeThemeFromColor($('input.minicolors-custom').val(), function(){
				$applyMinicolors.closest('.minicolors').find('.minicolors-panel').removeClass('apply');
				setTimeout(function() {
					// close minicolors-custom form
					$applyMinicolors
						.closest('.minicolors').removeClass('minicolors-focus')
						.find('.minicolors-panel').hide();
				}, 400);
			});
		});

		$('.btn-custom-theme-repeat').on('click', function(){
			if ($(this).hasClass('compile')) return;

			makeThemeFromColor($('input.minicolors-custom').val(), function(){
				$('#custom-theme-demos-wrap .custom-theme-repeat').addClass('hidden');
			});
		});

		if (!compiled) {
			setTimeout(function(){
				$applyMinicolors.trigger('click')
					.closest('.minicolors').find('.minicolors-panel').addClass('apply');
			}, 1000);
		} else {
			sassCombinedStyle = $('[data-styling-type]').attr('data-styling-type');
		}

		var b2setStylingType = b2.set.stylingType;
		b2.set.stylingType = function(value){
			if (compiled) {
				$('#custom-theme-demos-wrap .custom-theme-repeat').toggleClass('hidden', (value === sassCombinedStyle));
			}
			if (typeof b2setStylingType == "function") b2setStylingType(value);
		};
	}
})();