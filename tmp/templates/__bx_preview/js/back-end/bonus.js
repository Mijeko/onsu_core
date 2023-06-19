(function (window, $) {

if (!!window.ITROrderBonus && !!window.ITRElementBonus) {
	return;
}
window.ITROrderBonus = function (arParams)
{
	this.Update = {
		path: '/bitrix/components/vbcherepanov/vbcherepanov.bonusfororder/ajax.php',
		params: {
			AJAX: 'Y',
			SITE_ID: arParams.siteid,
			TYPE:arParams.TYPE,
		}
	};
};
window.ITROrderBonus.prototype.UpdateBonus = function(counts)
{
	this.Update.params.COUNTS=counts;
	BX.ajax({
		url: this.Update.path,
		data: this.Update.params,
		method: 'POST',
		dataType: 'html',
		timeout: 30,
		async: true,
		processData: true,
		scriptsRunFirst: true,
		emulateOnload: true,
		start: true,
		cache: false,
		onsuccess: function(data) {
			var obSelect = BX.findChild(BX("bns"), {"class" : "value"}, true);
			obSelect.innerHTML = data;
		},
		onfailure: function(){}
	});
};

window.ITRElementBonus = function (arParams)
{
	this.Update = {
		path: '/bitrix/components/vbcherepanov/vbcherepanov.bonuselement/ajax.php',
		params: {
			AJAX: 'Y',
			SITE_ID: arParams.siteid,
			PRODUCT_ID:arParams.productID,
			IBLOCK_ID:arParams.IBLOCKID,
			MIN_PRICE:arParams.MIN_PRICE,
			TYPE:arParams.TYPE,
			COUNTS :0,
		}
	};
};
window.ITRElementBonus.prototype.UpdateBonus = function(counts)
{
	var $bnsEl = $('#bnsEl');
	if (typeof this.spinner == "undefined") {
		this.spinner = RZB2.ajax.spinner($bnsEl.closest('.bonus'));
		this.spinner.Start({color: RZB2.themeColor});
		this.spinnerCount = 1;
	} else {
		this.spinnerCount++;
	}
	this.Update.params.COUNTS = counts;

	var _ = this;
	var $value = $bnsEl.find(".value");
	$value.css('color', '#ccc');

	BX.ajax({
		url: this.Update.path,
		data: this.Update.params,
		method: 'POST',
		dataType: 'html',
		timeout: 30,
		async: true,
		processData: true,
		scriptsRunFirst: true,
		emulateOnload: true,
		start: true,
		cache: false,
		onsuccess: function(data) {
			$value.html(data).removeAttr('style');
			_.spinnerCount--;
			if (_.spinnerCount < 1) {
				_.spinner.Stop();
				delete _.spinner;
			}
			var bHide = (data.length < 1 || data == 'INF' || data == '0');
			$bnsEl.closest('.element_bonus').toggleClass('hide', bHide);
		},
		onfailure: function(){}
	});
};

})(window, jQuery);
