b2.init.btnActionToggles = function(){
	$( document ).on('click', 'button.action.toggleable', function(){
		var _ = $(this);
		
		_.toggleClass('toggled');
		if ( _.hasClass('toggled') ){
			if ( _.hasClass('favorite') ){
				_.attr('title', 'Убрать из избранного');
				if (isHover) _.tooltip();
			} else if ( _.hasClass('compare') ){
				_.attr('title', 'Убрать из списка сравнения');
				if (isHover) _.tooltip();
			} else if ( _.hasClass('to-waitlist') ){
				_.attr('title', 'Убрать из отложенных');
				if (isHover) _.tooltip();
			}
		} else {
			_.attr('title', '');
			if (isHover) _.tooltip('destroy');
		}
	})

	$('.btn-action.compare').on('click', function(){
		var _ = $(this);
		_.toggleClass('toggled');
		if ( _.hasClass('toggled') ){
			_.attr('title', 'Убрать из списка сравнения');
		} else {
			_.attr('title', 'Добавить в <strong>список сравнения</strong>');
		}
		if (isHover) _.tooltip('fixTitle').tooltip('show');
	})
	$('.btn-action.favorite').on('click', function(){
		var _ = $(this);
		_.toggleClass('toggled');
		if ( _.hasClass('toggled') ){
			_.attr('title', 'Убрать из избранного');
		} else {
			_.attr('title', 'Добавить в <strong>избранное</strong>');
		}
		if (isHover) _.tooltip('fixTitle').tooltip('show');
	})
}