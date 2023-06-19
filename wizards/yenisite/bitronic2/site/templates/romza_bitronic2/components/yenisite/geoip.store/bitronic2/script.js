$(function () {
	var ysGeoCore = YS.GeoIPStore.Core;
	$('#modal_store-select-panel').on('click', '.itemlink', function () {
		var $this = $(this);
		ysGeoCore.setActiveItem($this.data('ys-item-id'));
		//window.location.reload();
		$('.geoipstore_store_text').text($this.text());
		$this.closest('.items').find('.item').removeClass('active');
		$this.closest('.item').addClass('active');
	});
});