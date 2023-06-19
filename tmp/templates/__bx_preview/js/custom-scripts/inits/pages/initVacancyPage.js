b2.init.vacancyPage = function(){

	var $vacId = document.location.hash;
	$($vacId).addClass('gigante');

	if (typeof initHorizontalCarousels === 'function') initHorizontalCarousels(document);

	// external js: isotope.pkgd.js

	// init Isotope
	var $grid = $('.isotope__grid').isotope({
		itemSelector: '.element-item',
		layoutMode: 'fitRows',
		layoutMode: 'masonry',
		getSortData: {
			name: '.name',
			symbol: '.symbol',
			number: '.number parseInt',
			category: '[data-category]',
			weight: function( itemElem ) {
				var weight = $( itemElem ).find('.weight').text();
				return parseFloat( weight.replace( /[\(\)]/g, '') );
			}
		}
	});


	$grid.on( 'click', '.element-item', function() {
		if ($(this).hasClass('gigante')) {
			$(this).removeClass('gigante');
			history.pushState('', document.title, window.location.pathname);
		} else {
			$(this).closest('.isotope__grid').find('.element-item').removeClass('gigante');
			$(this).addClass('gigante');
			document.location.hash = $(this).attr('id');
		}
		// trigger layout after item size changes
		$grid.isotope('layout');
	});
}