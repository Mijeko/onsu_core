jQuery(function($){
	$('.sale-order-list-change-payment, .sale-order-detail-payment-options-methods-info-change-link')
		.on('click', function(){
			var $this = $(this);
			if ($this.hasClass('loader')) return;

			var spinner = new RZB2.ajax.spinner($this.addClass('loader'));
			spinner.Start();

			var interval = setInterval(function(){
				if ($.contains(document, $this[0])) return;

				clearInterval(interval);

				spinner.Stop();
				delete spinner;
			}, 1000);
		});
});