(function(window) {
    'use strict';
    if (typeof BX != 'undefined') {
        BX.addCustomEvent("onCatalogStoreProductChange", BX.delegate(function (offerid) {
            $('.rz_oneclick-buy .do-order').data('offer-id', offerid);
        }, this));
    }
})(window);

window.onload = function (e) {
	if (typeof rzSingleOne == 'undefined') {
		rzSingleOne = {};
	}
	if (!('scriptLoaded' in rzSingleOne)) {
		var $modal = $('#rz_modal-oneclick'),
			$modalContent = $modal.find('.modal-body'),
			$body = $('body');
		$modal.appendTo($body);

		$body.on('click', '.rz_oneclick-buy .do-order', function (e) {
			var $this = $(this),
				offerId = $this.data('offer-id');

			var data = [
				{name: 'arparams', value: $this.data('arparams')},
				{name: 'template', value: $this.data('template').toString()},
				{name: 'URL', value: rzSingleOne.URL }
			];

			typeof offerId != 'undefined' ? data.push({name: 'ELEMENT_ID', value: offerId}) : offerId = offerId;
			return $.ajax({
				type: "POST",
				url: rzSingleOne.AJAX_URL,
				data: data,
				success: function (msg) {
					$modalContent.html(msg);
					$modalContent.data('arparams', $this.data('arparams'));
					$modalContent.data('template', $this.data('template').toString());
					$modal.css('opacity', '1'); // fix
					$modal.modal('show');
				}
			});
		});
		$modalContent.on('submit', 'form', function (e) {
			e.preventDefault();
			var data = $(this).serializeArray();
			data.push({name: 'arparams', value: $modalContent.data('arparams')});
			data.push({name: 'template', value: $modalContent.data('template').toString()});
			data.push({name: 'URL', value: rzSingleOne.URL });
			data.push({name: 'BUY_SUBMIT', value: 'Y'});
			return $.ajax({
				type: "POST",
				url: rzSingleOne.AJAX_URL,
				data: data,
				success: function (msg) {
					$modalContent.html(msg);
				}
			});
		});
		rzSingleOne.scriptLoaded = true;
	}
};