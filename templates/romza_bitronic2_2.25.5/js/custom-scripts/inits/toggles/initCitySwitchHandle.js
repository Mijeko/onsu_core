b2.init.citySwitchHandle = function(){
	$('#modal_city-select-panel').on('click', '.city-list > li > span', function(e){
		if ($(this).parent().hasClass('active')) return false;
		$(this).parent().addClass('active').siblings().removeClass('active');
		var city = $(this).html();
		$(e.delegateTarget).find('.current-city').html(city);
		$('#city-search').val('').trigger('change');
		return false;
	});
	$('#modal_city-select-panel').on('click', '#popup_ajax-city-search li', function(e){
		var newVal = $(this).html().split(',', 1)[0];
		var root = $(e.delegateTarget);
		root.find('.current-city').html(newVal);

		// searching in currently displayed city list for selected city
		var cityList = root.find('.city-list');
		if ( cityList.find('.active span').html() !== newVal ){
			cityList.find('.active').removeClass('active');
			cityList.find('li').each(function(){
				if ( $(this).find('>span').html() === newVal ){
					$(this).addClass('active');
				}
			})
		}

		$('#city-search').val(newVal);
		$(this).closest('#popup_ajax-city-search').attr('data-state', 'hidden');
		return false;
	});
	$('#btn-save-city').click(function(){
		var newCity = $('#modal_city-select-panel').find('.current-city').html();
		$('.btn-city-toggle .link-text').html(newCity);
		return true;
	});
}