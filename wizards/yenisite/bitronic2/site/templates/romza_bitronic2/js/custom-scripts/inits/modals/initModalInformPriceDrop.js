function initModalInformWhenPriceDrops(){
	$('#modal_inform-when-price-drops').each(function(){
		var moneyFormat = wNumb({
			mark: '.',
			thousand: ' ',
			decimals: 2
		});

		var thisModal = $('#modal_inform-when-price-drops');
		var currentPriceField = $('#price-current').children('.value');
		var currentPrice = moneyFormat.from(currentPriceField.html());
		thisModal.on('show.bs.modal', function(){
			currentPrice = moneyFormat.from(currentPriceField.html());
		})

		var currentPriceField = $('#price-current').children('.value');
		var currentPrice = moneyFormat.from(currentPriceField.html());

		var $slider = $('.desired-price-slider'),
			$inputs = $('#desired-price>.value, #modal_inform-when-price-drops_price');

		var desiredPriceField = $('#desired-price').children('.value');
		var priceDifferenceField = $('#price-difference').children('.value');
		var priceDifferencePercentField = $('#price-difference').children('.percent-value');
		
		var desiredPrice = moneyFormat.from(desiredPriceField.html());
		var priceDifference = currentPrice - desiredPrice;
		var priceDifferencePercent = Number((priceDifference/currentPrice)*100).toFixed(2);

		function setDifference(){
			priceDifferenceField.html(moneyFormat.to(priceDifference));
			priceDifferencePercentField.html('('+priceDifferencePercent+'%)');
		}

		noUiSlider.create($slider.get(0), {
			start: currentPrice*0.9,
			connect: "lower",
			step: 1,
			range: {
				'min': 1,
				'max': currentPrice
			},
			format: moneyFormat
		}).on('update', function(values, handle){
			$inputs.val(values[0]);

			desiredPrice = moneyFormat.from(values[0]);
			priceDifference = currentPrice - desiredPrice;
			priceDifferencePercent = Number((priceDifference/currentPrice)*100).toFixed(2);
			setDifference();
		});

		$inputs.on('change', function(){
			$slider.get(0).noUiSlider.set(this.value);
		});

		$('#modal_inform-when-price-drops_price').on('keypress', function(e){
			if ( e.which !== 13 ) return true;
			$(this).change();
			return false;
		})
		$('#modal_inform-when-price-drops_email').on('keypress', function(e){
			if ( e.which !== 13 ) return true;
			$('#modal_inform-when-price-drops_price').focus();
			return false;
		})

		$('.form_inform-when-price-drops').submit(function(e){
			if ( !formCheck($(this)) ) {
				return false;
			} else {
				// thisModal.modal('hide');
				return true;
			}
		})
	})
}