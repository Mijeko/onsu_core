b2.init.siteMapPage = function(){
	$('#minify-site-map').click(function(){
		var expanded = $('.expanded');
		expanded.children('.expand-content').velocity('slideUp', 300, function(){
			expanded.removeClass('expanded');
		});
	})
	$('#expand-site-map').click(function(){
		var expandable = $('.expandable').not('.expanded');
		expandable.addClass('expanded').children('.expand-content').velocity('slideDown', 300);
	})
}