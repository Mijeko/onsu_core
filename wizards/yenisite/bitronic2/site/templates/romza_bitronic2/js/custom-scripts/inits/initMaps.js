b2.init.maps = function(target){
	function initializeMap(m, coords, zoom) {
		var mapOptions = {
			zoom: zoom || 15,
			center: coords
		};
		var newMap = new google.maps.Map(m, mapOptions);
		var marker = new google.maps.Marker({
			position: coords,
			map: newMap
		})
	};
	
	$('body').on('click', '.store', function(){
		var _ = $(this);
		var map = _.find('.map');
		if ( !map.length || map.hasClass('inited') ) return;
		var coords = map.attr('data-latlng').split(' ');
		var lat = parseFloat(coords[0]);
		var lng = parseFloat(coords[1]);
		coords = new google.maps.LatLng(lat, lng);

		setTimeout(function(){
			initializeMap(map[0], coords, 17);
			map.addClass('inited');
		}, 0);
	});
}