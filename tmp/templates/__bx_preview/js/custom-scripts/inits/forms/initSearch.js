function initSearch(target){
	$('.search-example').on('click', function(e){
		var text = $(this).html();
		searchField.val(text).trigger('change');
	})

	var ajaxSearch = $('#popup_ajax-search');
	var searchField = $('#search-field');
	var searchClear = searchField.siblings('.search-controls').find('.search-clear');
	initSearchPopup(ajaxSearch, searchField, searchClear);

	var ajaxCitySearch = $('#popup_ajax-city-search');
	var citySearch = $('#city-search');
	var citySearchClear = citySearch.siblings('.input-controls').find('.input-clear');
	initSearchPopup(ajaxCitySearch, citySearch, citySearchClear);
}