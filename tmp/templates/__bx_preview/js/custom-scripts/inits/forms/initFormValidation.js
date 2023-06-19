b2.init.formValidation = function(selector, hardMode){
	var modalForms = $(selector).find('form').andSelf().filter('form');
	modalForms.attr('novalidate', true);

	modalForms.on('focus', '.textinput', function(e){
		$(this).removeClass('valid not-valid').parent().find('.v_error').remove();
	});
	modalForms.on('blur change', '.textinput', function(e){
		if ( e.keyCode !== 9 )
			textFieldCheck($(this));
	});
	if (typeof hardMode != "undefined" && !!hardMode) {
		modalForms.on('submit', function(e){
			if (formCheck($(this))) return true;
			e.preventDefault();
			return false;
		});
	}

	// focus first textinput
	modalForms.on('shown.bs.modal', function(){
		$(this).find('.textinput').first().focus();
	});

	modalForms.find('.phone-masked').mask("8 (999) 999-99-99", {});
	/* BACK_END NOT_NEED in ready project
	// SUBJECT TO CHANGE DURING SERVER-SIDE PROGRAMMING
	var formLogin = $('.form_login');
	var formReg = $('.form_registration');
	formLogin.submit(function(e){
		e.preventDefault();
		if ( !formCheck($(this)) ) {
			return false;
		} else {
			localStorage.setItem('b2_auth', 'true');
			if ( $('.order-step1-page').length > 0){
				window.location.href = "order-details.php";
			} else {
				window.location.href = "account.php";
			}
		}
	})

	formReg.submit(function(e){
		e.preventDefault();
		if ( !formCheck($(this)) ) {
			return false;
		} else {
			localStorage.setItem('b2_auth', 'true');
			if ( $('.order-step1-page').length > 0){
				window.location.href = "order-details.php";
			} else {
				window.location.href = "account.php";
			}
		}
	})*/
}