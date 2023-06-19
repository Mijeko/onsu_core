b2.init.servicesPage = function(){
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

	// filter functions
	var filterFns = {
		// show if number is greater than 50
		numberGreaterThan50: function() {
			var number = $(this).find('.number').text();
			return parseInt( number, 10 ) > 50;
		},
		// show if name ends with -ium
		ium: function() {
			var name = $(this).find('.name').text();
			return name.match( /ium$/ );
		}
	};

	// bind filter button click
	$('#services__filters').on( 'click', 'button', function() {
		$(this).closest('.services').find('.element-item').removeClass('gigante');
		var filterValue = $( this ).attr('data-filter');
		// use filterFn if matches value
		filterValue = filterFns[ filterValue ] || filterValue;
		$grid.isotope({ filter: filterValue });
	});

	// change is-checked class on buttons
	$('.isotope__button-group').each( function( i, buttonGroup ) {
		var $buttonGroup = $( buttonGroup );
		$buttonGroup.on( 'click', 'button', function() {
			$buttonGroup.find('.is-checked').removeClass('is-checked');
			$( this ).addClass('is-checked');
		});
	});

	$grid.on( 'click', '.element-item', function() {
		if ($(this).hasClass('gigante')) {
			if (!$(this).find('.more-info').find('.text').is(event.target)) {$(this).removeClass('gigante'); }
		} else {
			$(this).closest('.isotope__grid').find('.element-item').removeClass('gigante');
			$(this).addClass('gigante');
		}
		// trigger layout after item size changes
		$grid.isotope('layout');
	});

}