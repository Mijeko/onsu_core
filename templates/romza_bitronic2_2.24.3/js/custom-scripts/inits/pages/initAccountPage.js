b2.init.accountPage = function(){
	var accountMenu = $('#account-menu');
	if ( $('.account-subscribe-settings').length > 0 ){
		accountMenu.find('a[href^="account-subscribe"]')
			.parent().addClass('active')
			.siblings('.active').removeClass('active');
	} else if ( $('.account-settings').length > 0 ) {
		accountMenu.find('a[href^="account-settings"]')
			.parent().addClass('active')
			.siblings('.active').removeClass('active');
	} else if ( $('.account-order-history').length > 0 ) {
		accountMenu.find('a[href^="account-order"]')
			.parent().addClass('active')
			.siblings('.active').removeClass('active');
	} else if ( $('.account-profiles').length > 0 ) {
		accountMenu.find('a[href^="account-profiles"]')
			.parent().addClass('active')
			.siblings('.active').removeClass('active');
	}

	// $('.switch-order-content').click(function(){
	// 	var tr = $(this).closest('tr');
	// 	var content = tr.children('.order-content, .order-payment-n-delivery-types');
	// 	if ( tr.hasClass('shown') ){
	// 		content.velocity('slideUp', 'fast', function(){
	// 			tr.removeClass('shown');
	// 		});
	// 	} else {
	// 		content.velocity('slideDown', {
	// 			duration: 'fast',
	// 			display: 'block',
	// 			complete: function(){
	// 				tr.addClass('shown');
	// 			}
	// 		})
	// 	}
	// 	tr.find('').slideToggle('fast', function(){
	// 		tr.toggleClass('shown');
	// 	});
	// })

	$('#open-subscribe-settings').click(function(){
		var _ = $(this);
		if ( _.hasClass('toggled') ){
			_.removeClass('toggled');
			$('#subscribe-edit').velocity('transition.slideUpOut', 400);
		} else {
			_.addClass('toggled');
			$('#subscribe-edit').velocity('transition.slideDownIn', 400);
		}
	})
	$('#submit-subscribe-settings').click(function(){
		$('#open-subscribe-settings').removeClass('toggled');
		$('#subscribe-edit').velocity('transition.slideUpOut', 400);
	})

	 $('#goodsTab a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	})
}