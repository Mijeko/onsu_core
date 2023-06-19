$(document).ready(function() {
	var ysLocCookie = YS.GeoIP.Cookie,
		ysLocPopUp  = YS.GeoIP.PopUpWindow,
		ysLocAutoC	= YS.GeoIP.AutoComplete,
		town = ysLocCookie.getCookieTown('YS_GEO_IP_CITY'),
		region = ysLocCookie.getRegionCookie('YS_GEO_IP_CITY'),
		regionId = ysLocCookie.getRegionId('YS_GEO_IP_CITY'),
		country = ysLocCookie.getCountryCookie('YS_GEO_IP_CITY'),
		countryId = ysLocCookie.getCookieCountryId('YS_GEO_IP_CITY'),
		siteId = $('#ys-SITE_ID').val(),
		townId_ = ysLocCookie.getLocationID('YS_GEO_IP_LOC_ID'),
		townId,
		dataLoc,
		tmpVal,
		orderProps = {	
			// default values
			'PERSON_TYPE_1' : {'locationID' : 6,	'cityID' : 5},
			'PERSON_TYPE_2' : {'locationID' : 18,	'cityID' : 17}
		};
			
		if(YS.GeoIP.OrderProps != undefined)
		{
			// YS.GeoIP.OrderProps sets in bitrix\components\yenisite\geoip.city\templates\.default\component_epilog.php
			orderProps = YS.GeoIP.OrderProps;
		}

	$(document).keydown(function (eventObject) {
		if (eventObject.which == 27) {
			$('#ys-geoip-mask, .popup').hide();
		}
	});
	
	if (town === null) {
		ysLocPopUp.showPopUpGeoIP();
	} else {
		// ====================== Templatef visual / .default ======================
		// Default location template
		if (
			$('#LOCATION_ORDER_PROP_' + orderProps['PERSON_TYPE_1']['locationID']).length < 1 &&
			$('#ORDER_PROP_' + orderProps['PERSON_TYPE_1']['locationID'] +'_val').length < 1
		) {
			// client profile add (simple mode)
			var select1 = $('select[name="ORDER_PROP_' + orderProps['PERSON_TYPE_1']['locationID'] +'"]');
			var select2 = $('select[name="ORDER_PROP_' + orderProps['PERSON_TYPE_2']['locationID'] +'"]');

			var locID = 0;
			var cityID = 0;
			if (select1.length > 0) {
				locID = orderProps['PERSON_TYPE_1']['locationID'];
				cityID = orderProps['PERSON_TYPE_1']['cityID'];
			}
			else if (select2.length > 0) {
				locID = orderProps['PERSON_TYPE_2']['locationID'];
				cityID = orderProps['PERSON_TYPE_2']['cityID'];
			}

			if (locID > 0) {
				if ($('form input[name="action"]').val() == 'create'
				&&  $('form input[name="ID"]').length == 0) {

					var tmpVal;
					$('select[name="ORDER_PROP_' + locID +'"] option').each(function() {
						if ( $(this).text() == country + ' - ' + town  ) {
							tmpVal = $(this).val();
						}
		
						if ($(this).attr('selected') == 'selected') {
							$(this).removeAttr('selected');
						}
					});

					$('select[name="ORDER_PROP_' + locID +'"]').val(tmpVal);
					$('input[name="ORDER_PROP_' + cityID +'"]').val(town);
				} else {
					var optionContent = $('select[name="ORDER_PROP_' + locID +'"] option')
						.filter(':selected').text().split(' - ');

					if (Array.isArray(optionContent) && optionContent.length > 1) {
						if (optionContent[1].length > 0) {
							var cityField = $('input[name="ORDER_PROP_' + cityID +'"]');

							if (cityField.val() != optionContent[1]) {
								cityField.val(optionContent[1]);
							}
						}
					}
				}
			}
		}
		// ============================================================
	
		$('a.ys-loc-city').text(town);
		$('a.ys-loc-city').css('display', 'inline');
	} // if (town === null) else
	

	var confirmCity = function() {
		ysLocCookie.setCookieFromButtonClick();
		ysLocPopUp.hidePopUpGeoIP();
		
		$('a.ys-loc-city').text(ysLocCookie.getCookieTown('YS_GEO_IP_CITY'));
		$('a.ys-loc-city').css('display', 'inline');
		if(typeof YS_LOCATOR_NO_RELOAD == 'undefined') {
			window.location.reload();
		}
	}
	var textchangeInterval;

	function geoIpComponentInitHandlers ()
	{
		if (town !== null) {
			$('a.ys-loc-city').text(town);
			$('a.ys-loc-city').css('display', 'inline');
		}
		
		// ------------ click handlers -------------
		$('.ys-loc-cities a').off().on('click', function(e) {
			e.preventDefault();
			var locId = $(this).find('span').attr('data-location');
			if (typeof locId != "undefined" && locId != '0') {
				ysLocCookie.setLocationID(locId);
				ysLocCookie.setCookieFromLocationID(locId, function(){
					if(typeof YS_LOCATOR_NO_RELOAD == 'undefined') {
						window.location.reload();
					}
				});
			} else {
				ysLocCookie.setCookieFromTownClick($(this).text());
				if(typeof YS_LOCATOR_NO_RELOAD == 'undefined') {
					window.location.reload();
				}
			}
			ysLocPopUp.hidePopUpGeoIP();
			
			var town = ysLocCookie.getCookieTown('YS_GEO_IP_CITY');

			$('a.ys-loc-city').text(town);
			$('a.ys-loc-city').css('display', 'inline');
		});
		
		$('a.ys-loc-city').off().on('click', function(e) {
			e.preventDefault();
			ysLocPopUp.showPopUpGeoIP();
		});
		
		$('.ys-my-city .button').off().on('click', confirmCity);
		
		$('#ys-geoip-mask, a.close').off().on('click', function() {
			// YS.GeoIP.AutoConfirm sets in bitrix\components\yenisite\geoip.city\templates\.default\component_epilog.php
			if (YS.GeoIP.AutoConfirm && town === null) {
				confirmCity();
			} else {
				ysLocPopUp.hidePopUpGeoIP();
			}
		});
		// ------------- end click handlers ---------------

		// city text input handler
		$('.ys-popup .txt').off().on('textchange', function() {
			var txtField = $(this);
			if (txtField.val().length > 1)
			{
				if (textchangeInterval) {
					clearInterval(textchangeInterval);
				}
				textchangeInterval = setInterval(function () {
					ysLocAutoC.buildList(txtField.val(), function () {
						if (typeof YS_LOCATOR_NO_RELOAD == 'undefined') {
							window.location.reload();
						}
					}, txtField);
					clearInterval(textchangeInterval);
				}, 500);
				
			} else if(txtField.val().length <= 1) {
				clearInterval(textchangeInterval);
				$('.ys-loc-autocomplete').css('display', 'none').empty();
			}
		}).on('keypress', function(e) {
			if (e.which != 13) return;
			$('.ys-loc-autocomplete div').eq(0).click();
		});
	}

	geoIpComponentInitHandlers();
	BX.addCustomEvent("onFrameDataReceived", geoIpComponentInitHandlers);
});