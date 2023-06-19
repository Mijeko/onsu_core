b2.init.catalogLvl0Page = function(){
	$('#minify-lvl0-categories').click(function(){
		var expanded = $('.expanded');
		expanded.next('.expand-content').velocity('slideUp', 300, function(){
			expanded.removeClass('expanded');
		});
	})
	$('#expand-lvl0-categories').click(function(){
		var expandable = $('.catalog-category-header').not('.expanded');
		expandable.addClass('expanded').next('.expand-content').velocity('slideDown', 300);
	})
	$('.catalog-lvl0-page').on('click', '.btn-expand', function(e){
		var section = $(this).closest('.catalog-category-header');
		e.preventDefault();
		e.stopPropagation();
				
		if ( section.hasClass('expanded') ){
			section.next('.catalog-category-content').velocity('slideUp', 300, function(){
				section.removeClass('expanded');
			});
		} else {
			section.addClass('expanded').next('.catalog-category-content').velocity('slideDown', 300);
		}
	});
}