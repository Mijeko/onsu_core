jQuery(function($){
	$('div.sale-acountpay-fixedpay-item')
		.on('click', function(){
			$('input.sale-acountpay-input[name="buyMoney"]').focus();
		});
});