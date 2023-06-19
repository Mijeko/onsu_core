b2.init.quantityChange = function(target){
	var timerQuantity;
	$(target).find('.quantity-counter').on({
		mousedown: function(){
			var _ = $(this);
			var quanInput = _.parent().find('.quantity-input');
			if ( quanInput.hasClass('disabled') || quanInput.is(':disabled') || _.hasClass('disabled') ) return;
			var curValue = parseInt(quanInput.val(), 10);
			
			var changeStep = 1;
			if ( _.hasClass('decrease') ) changeStep = -1;
			if (isNaN(curValue) || curValue<1) curValue = 1;
			

			curValue += changeStep;
			if ( curValue < 1 ) curValue = 1;
			quanInput.val(curValue);
			timerQuantity = setInterval(function(){
				curValue += changeStep;
				if ( curValue < 1 ) curValue = 1;
				quanInput.val(curValue);
			}, 100);
		},
		'mouseup mouseleave': function(){
			clearInterval(timerQuantity);
		}
	}, '.quantity-change').on('change', '.quantity-input', function(){
		var _ = $(this);
		var newValue = _.val();
		if (isNaN(newValue) || newValue < 1) _.val(1);
	})
}