$(document).ready(function(){
	$('#currency-switch').on('change', function(e, currencyId){
		if (typeof currencyId == "undefined") return;

		var $form = $(this).find('form');
		$form.find('input').val(currencyId);
		$form.submit();
	});
});