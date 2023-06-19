function initSearchPopup(popup, field, clearBtn){
	function open(){
		popup.velocity('fadeIn').data('opened', true);
		$(document).on('click.searchPopup', function(e){
			if ( !$(e.target).closest(popup, field).length ) close();
		})
	}
	function close(){
		popup.velocity('fadeOut').data('opened', false);
		$(document).off('click.searchPopup');
	}
	field.on('focus keyup change', function(e){
		if ( e.keyCode && 27 === e.keyCode ){
			$(this).blur();
			close();
			e.stopPropagation();
			return;
		}
		if ( $(this).val() ){
			clearBtn.show();
			if ( !popup.data('opened') ) open();
		} else {
			clearBtn.hide();
			close();
		}
	}).on('click', function(e){
		e.stopImmediatePropagation(); // for um popup not to close
	});
	clearBtn.on('click', function(){
		field.val('').trigger('change');
	})
}