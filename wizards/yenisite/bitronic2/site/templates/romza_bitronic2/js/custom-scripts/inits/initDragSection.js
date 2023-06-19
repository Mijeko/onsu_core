function MoveDragSection(page, value) {
	if ( $('[data-page="' + page + '"]').length ) {
		var keys = Object.keys(value);

		for (var i = 0; i < keys.length; i++) {
			$('.drag-section.' + keys[i]).attr('data-order', value[keys[i]]);
		}
	}
}