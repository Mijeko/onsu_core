function initRangeSlider(slider, options){
	var $slider = $(slider),
		minValue = options.minValue,
		maxValue = options.maxValue,
		step = ( options.step === undefined ) ? 1 : options.step,
		$inputLower = $slider.parent().find('.range-input-lower'),
		$inputUpper = $slider.parent().find('.range-input-upper'),
		startLower = ( options.startLower === undefined ) ? minValue : options.startLower,
		startUpper = ( options.startUpper === undefined ) ? maxValue : options.startUpper;
	function filterPips(value){
		if ( value === minValue || value === maxValue ) return 1;
		return 2;
	}

	noUiSlider.create(slider, {
		start: [startLower, startUpper],
		connect: true,
		behaviour: 'snap',
		step: step,
		range: {
			'min': minValue,
			'max': maxValue
		},
		format: wNumb(options.format),
		tooltips: true,
		pips: {
			mode: options.pips.mode,
			values: options.pips.values,
			density: options.pips.density,
			filter: filterPips
		}
	}).on('update', function(values, handle){
		if (handle){
			// handle is 1 == upper
			$inputUpper.val(values[handle]);
		} else {
			// handle is 0 == lower
			$inputLower.val(values[handle]);
		}
	});

	$inputLower.on('change', function(e){
		slider.noUiSlider.set([this.value, null]);
	});
	$inputUpper.on('change', function(e){
		slider.noUiSlider.set([null, this.value]);
	});

	$slider.find('.noUi-handle').append('<div class="noUi-arrow"></div>');
	
	$slider.data('range', { min: minValue, max: maxValue })
		.data('format', options.format)
		.find('.noUi-base').append('<div class="noUi-limiter base hidden"></div><div class="noUi-limiter top hidden"></div>');
	
	if (options.limits){
		setRangeSliderLimits($slider, options.limits);
		var $limiter = $slider.find(".noUi-limiter.top"),
			format = wNumb(options.format),
			delta = maxValue - minValue;
			
		function updateTopLimiter(val){
			var limits = $slider.data('noUi-limits'),
				curLeft = format.from(val[0]),
				curRight = format.from(val[1]),
				leftLimitPercent = limits.leftPercent,
				rightLimitPercent = limits.rightPercent,
				newLeft = 100 * ((curLeft - minValue) / delta),
				newRight = 100 * ((maxValue - curRight) / delta);

			// console.log('current',curLeft, leftPercent, curRight, rightPercent);
			if (newLeft < leftLimitPercent) newLeft = leftLimitPercent;
			if (newRight < rightLimitPercent) newRight = rightLimitPercent;
			if (newLeft >= (99.5 - rightLimitPercent)){
				return $limiter.addClass('hidden');
			}
			if (newRight >= (100 - leftLimitPercent)){
				return $limiter.addClass('hidden');
			}

			$limiter.removeClass('hidden').css({
				left: newLeft + '%',
				right: newRight + '%',
			})
		}
		updateTopLimiter(slider.noUiSlider.get());

		slider.noUiSlider.on('update', function(values, handle){
			updateTopLimiter(values);
		});
	}
}

function setRangeSliderLimits($slider, limits){
	// limits is object with left and right
	var min = $slider.data('range').min,
		max = $slider.data('range').max,
		left = parseFloat(limits.left) || min,
		right = parseFloat(limits.right) || max;

	// edge cases
	if (left > right) right = left;
	if (left < min) left = min;
	if (right > max) right = max;
	if (left >= max){
		left = max;
		right = max;
	} else if (right <= min){
		right = min;
		left = min;
	}

	var leftPercent = 100 * ((left - min) / (max - min)),
		rightPercent = 100 * ((max - right) / (max - min)),
		$limiter = $slider.find('.noUi-limiter.base');

	(leftPercent === 0 && rightPercent === 0) ?
		$limiter.addClass('hidden') : $limiter.removeClass('hidden');
	// console.log(leftPercent, rightPercent);
	$slider.data('noUi-limits', { left: left, right: right, leftPercent: leftPercent, rightPercent: rightPercent });
	$limiter.css({
		left: leftPercent + '%',
		right: rightPercent + '%'
	});

	var s = $slider.get(0).noUiSlider, v = s.get();

	s.set([v[0], v[1]]);
	// ^ don't use [null, null] here because we NEED 'update' event to be fired.
}


function initRangeSliders(target){

	$(target).find('.range-slider.price-slider').each(function(){
		initRangeSlider(this, {
			minValue: 0,
			maxValue: 210000,
			step: 1,
			startLower: 50000,
			startUpper: 150000,
			limits: {
				left: 20000,
				right: 120000
			},
			format: { 
				decimals: 0,
				thousand: ' '
			},
			pips: {
				mode: 'values',
				values: [0, 70000, 140000, 210000],
				density: 50
			}
		});
	})
	$(target).find('.range-slider.screen').each(function(){
		initRangeSlider(this, {
			minValue: 4,
			maxValue: 12,
			step: 0.1,
			startLower: 6,
			startUpper: 9,
			format: {
				decimals: 1,
				postfix: '″',
				mark: ','
			},
			pips: {
				mode: 'values',
				values: [4, 6, 8, 10, 12],
				density: 50
			}
		})
	});
}
initRangeSliders(document);