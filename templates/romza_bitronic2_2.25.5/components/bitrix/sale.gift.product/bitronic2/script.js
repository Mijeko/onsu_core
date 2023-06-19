function bx_sale_gift_product_load(injectId, localAjaxData, additionalData)
{
	localAjaxData = localAjaxData || {};
	additionalData = additionalData || {};

	var $container = $('#' + injectId);
	var spinner = RZB2.ajax.spinner($container);
	spinner.Start({color: RZB2.themeColor});

	$('#photo-block div.sticker.plus-gift').fadeOut();

	if(!!RZB2.ajax.params['REQUEST_URI']) additionalData["REQUEST_URI"] = RZB2.ajax.params['REQUEST_URI'];
	if(!!RZB2.ajax.params['SCRIPT_NAME']) additionalData["SCRIPT_NAME"] = RZB2.ajax.params['SCRIPT_NAME'];

	BX.ajax({
		url: SITE_DIR + 'ajax/gifts.php',
		method: 'POST',
		data: BX.merge(localAjaxData, additionalData),
		dataType: 'html',
		processData: false,
		start: true,
		onsuccess: function (html) {
			if (typeof spinner == 'object') {
				spinner.Stop();
				delete spinner;
			}

			var ob = BX.processHTML(html);
			// inject
			BX(injectId).innerHTML = ob.HTML;
			BX.ajax.processScripts(ob.SCRIPT);

			// trigger event for picturefill
			var event = document.createEvent("HTMLEvents");
			event.initEvent("DOMContentLoaded",true,false);
			window.dispatchEvent(event);

			if (typeof initHorizontalCarousels == "function") initHorizontalCarousels($container);
			if (typeof b2.init.ratingStars     == "function") b2.init.ratingStars($container);
			if (typeof b2.init.tooltips        == "function") b2.init.tooltips($container);
			if (typeof initToggles             == "function") initToggles($container);
			RZB2.ajax.BasketSmall.RefreshButtons($container);
			RZB2.ajax.Favorite.RefreshButtons($container);
			RZB2.ajax.Compare.RefreshButtons($container);
		}
	});
}