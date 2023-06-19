$(document).ready(function () {
	var $modal = $('#modal_city-select-panel');
	var selectorCurCity = 'ul.city-list li.active span';

	var ysLocCookie = YS.GeoIP.Cookie;
	var town = ysLocCookie.getCookieTown('YS_GEO_IP_CITY');
	var ysLocAutoC = YS.GeoIP.AutoComplete;

	if (town == null) {
		$('.btn-city-toggle').click();
		ysLocCookie.setCookieFromTownClick($modal.find(selectorCurCity).text());
	}
	// city text input handler
	var textchangeInterval;
	var YSGEOtemp = true;
	var $thisForm = null;
	$modal
		.on('keypress', '.form_city-select #city-search', function (e) {
			if (e.which == 13) // press ENTER
			{
				$('.ys-loc-autocomplete div').eq(0).click();
				return false;
			}
		})
		.on('input', '.form_city-select #city-search', function (e) {
			var txtField = $(this);
			if (txtField.val().length > 1) {
				if (textchangeInterval) {
					clearInterval(textchangeInterval);
				}
				textchangeInterval = setInterval(function () {
					ysLocAutoC.buildList(txtField.val(), function () {
						if (!$thisForm) {
							$thisForm = $modal.find('.form_city-select');
						}
						$thisForm.find('ul.city-list li').removeClass('active');
						$thisForm.submit();
						$modal.modal('hide');
					}, txtField);
					clearInterval(textchangeInterval);
				}, 500);

			} else if (txtField.val().length <= 1) {
				clearInterval(textchangeInterval);
				$('.ys-loc-autocomplete').css('display', 'none').empty();
			}
		})
		.on('click', 'ul.city-list li > span', function () {
			if ($(this).parent().hasClass('active')) return;

			$(this).parent().addClass('active').siblings().removeClass('active');
			
			YSGEOtemp = true;
			$(this).closest('form').submit();
		})
		.on('submit', '.form_city-select', function (e) {
			if (typeof RZB2 == 'undefined') {
				RZB2 = {};
			}
			if ('GEOIP_NO_RELOAD' in RZB2 && RZB2.GEOIP_NO_RELOAD) {
				e.preventDefault();
			}
			if (YSGEOtemp) {
				changeCity(ysLocCookie, $modal, selectorCurCity, e);
				YSGEOtemp = false;
			} else {
				e.preventDefault();
				YSGEOtemp = true;
			}
		})
		.on('hide.bs.modal', function (e) {
			if (YS.GeoIP.AutoConfirm == false) return;
			if (YSGEOtemp) {
				changeCity(ysLocCookie, $modal, selectorCurCity, e);
				YSGEOtemp = false;
			} else {
				YSGEOtemp = true;
			}
		});

	function changeCity(ysLocCookie, $modal, selectorCurCity, event) {
		var $city = $modal.find(selectorCurCity);
		var $curCityLink = $('a.btn-city-toggle span');
		if ($city.length) {
			if ($.trim($curCityLink.text()) == $.trim($city.text())) return;

			ysLocCookie.setCookieFromTownClick($city.text());
			$curCityLink.text($city.text());
			$modal.find('.current-city').text($city.text());
		} else {
			var curCity = ysLocCookie.getCookieTown('YS_GEO_IP_CITY');
			if (curCity.length > 0) {
				$curCityLink.text(curCity);
			} else {
				return;
			}
		}
		if ('GeoIPStore' in YS && 'updateActiveItem' in YS.GeoIPStore.Core) {
			event.preventDefault();
			YS.GeoIPStore.Core.updateActiveItem();
		} else if (typeof ysGeoStoreList != "undefined") {
			var curName = YS.GeoIP.Cookie.getCookieTown('YS_GEO_IP_CITY');
			if (curName in ysGeoStoreList) {
				var curID = ysGeoStoreList[curName];
				if (curID != ysGeoStoreActiveId) {
					event.preventDefault();
					YS.GeoIPStore.Core.setActiveItem(curID);
					return false;
				}
			} else if (ysGeoStoreActiveId != ysGeoStoreDefault) {
				YS.GeoIPStore.Core.setActiveItem(ysGeoStoreDefault);
				event.preventDefault();
				return false;
			}
		} else {
			if (!('GEOIP_NO_RELOAD' in RZB2 && RZB2.GEOIP_NO_RELOAD)) {
				location.reload();
			}
		}
	}
});