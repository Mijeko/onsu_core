// just simple check for email!
// have @ - not first!
// have . - after @, but not right after
// have some symbols after .
function isEmail(str){
	// checking for presence of @
	var atPos = str.indexOf('@');
	if ( atPos < 1 ){
		return false;
	}
	
	// @ is here and is not a first symbol! Good.
	// now check if string after it has . somewhere inside
	// - not first, not last
	var afterAt = str.substring(atPos+1);
	var dotPos = afterAt.indexOf('.');
	if ( dotPos < 1 || dotPos >= afterAt.length-1){
		return false;
	}

	return true;
}

function textFieldCheck(field){
	var val = field.val();
	field.siblings('.v_error').remove();
	// first check: if required and empty - not valid.
	if (field.attr('required') && !val){
		field.removeClass('valid').addClass('not-valid');
		field.parent().append('<div class="v_error">' + BX.message('BITRONIC2_VALIDATION_FILL_FIELD') + '</div>');
		return false;
	}

	// second check: 
	// if field type is email and val is not like email - not valid.
	if (field.is('[type="email"]') && !isEmail(val)) {
		field.removeClass('valid').addClass('not-valid');
		field.parent().append('<div class="v_error">' + BX.message('BITRONIC2_VALIDATION_NOT_EMAIL') + '</div>');
		return false;
	}

	// we got here?! Sooo... let's assume the field is valid then.
	field.removeClass('not-valid').addClass('valid');
	return true;
}
function formCheck(form){
	var isValid = true;
	form.find('.textinput').each(function(){
		if (!textFieldCheck($(this))) {
			isValid = false;
			return false;
		}
	})
	return isValid;
}