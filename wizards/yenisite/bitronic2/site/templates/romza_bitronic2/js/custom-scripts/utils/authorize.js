function authorize(){
	var authorized = localStorage.getItem('b2_auth');
	if (authorized === 'false') {
		$('body').removeClass('authorized');
	} else {
		$('body').addClass('authorized');
	}
	$('#btn-logout').mousedown(function(e){
		e.stopPropagation();
	})
	$('#btn-logout').click(function(e){
		$(this).siblings('[data-state="shown"]').each(function(){
			$(this).attr('data-state', 'hidden');
			$($(this).attr('data-toggler')).removeClass('toggled');
		});
		$('body').removeClass('authorized');
		localStorage.setItem('b2_auth', 'false');
		return false;
	})
}