/**
* Library for Yenisite's location module
*
* @author Pavel Ivanov
* @Skype: hardyjazz
* @email: pavel@yenisite.ru
*/

var YS = YS || {};

/**
* Create new namespace
*
* @param {String} namespace name
*/
YS.namespace = function(ns_string) {
	var parts = ns_string.split('.'),
		parent = YS,
		i;
		
	if (parts[0] === 'YS') {
		parts = parts.slice(1);
	}
	
	for (i = 0; i < parts.length; i+= 1) {
		if (typeof parent[parts[i]] === "undefined") {
			parent[parts[i]] = {};
		}
		parent = parts[i];
	}
	return parent;
};

YS.namespace('YS.GeoIP.Cookie');
YS.namespace('YS.GeoIP.PopUpWindow');
YS.namespace('YS.GeoIP.AutoComplete');

/**
* Create YS.GeoIP.Cookie object
* 
* @return {Object}
*/
YS.GeoIP.Cookie = (function() {
	/**
	* Set cookie for 365 days
	*
	* @private
	* @param {String} name cookie
	* @param {String} value of cookie
	*/
	__setCookie = function(name, value) {
		var date = new Date();
		
		if (__getCookie(name) !== null) {
			__deleteCookie(name);
		}
		
		date.setDate(365 + date.getDate());
		// YS.GeoIP.hiddenDomain  set in \bitrix\components\yenisite\geoip.city\component.php
		var domain = YS.GeoIP.hiddenDomain || window.location.hostname;
		var tmpAr = domain.split('.');
		var isDomainIP = false;
		if (tmpAr.length == 4) {
			isDomainIP = true;
			for (var i = 0; i < tmpAr.length; i++) {
				if (parseInt(tmpAr[i],10) != tmpAr[i]) {
					isDomainIP = false;
					break;
				}
			}
		}

		value = encodeURIComponent(value);

		document.cookie = name + "=" + value + "; path=/; expires="+ date.toGMTString() + "; domain=" + domain;
		//document.cookie = name + "=" + value + "; path=/; expires="+ date.toGMTString() + ";";
	},
	
	/**
	* Set cookie from selects
	*/
	setCookieFromSelects = function() {
		var country,
			region,
			city,
			cId;
			
		cId = $('.ys-loc-choose-country select').val().split('-')[0];
		
		region 		= $('.ys-loc-choose-region select').val();
		city 		= $('.ys-loc-choose-city select').val();
		
		$.ajax({
			url: '/bitrix/components/yenisite/geoip.city/tools.php?cId=' + cId,
			async: false,
			success: function(out)	{
				__setCookie('YS_GEO_IP_CITY', out + '/' + region + '/' + city);
			}
		});
	},
	
	/**
	* Set cookie from click on town
	*
	* @param {String} town name
	*/
	setCookieFromTownClick = function(town) {
		$.ajax({
			url: '/bitrix/components/yenisite/geoip.city/tools.php?townName=' + encodeURI(town),
			async: false,
			success: function(out)	{
				var tmp = out.split('/');
				var locID = tmp.pop();
				var out = tmp.join('/');
				
				if (tmp.length == 1 || tmp.length == 0) {
					__setCookie('YS_GEO_IP_CITY', out + '/empty/' + town);
				} else if (tmp.length == 2) {
					__setCookie('YS_GEO_IP_CITY', out + '/' + town);
				}

				__setCookie('YS_GEO_IP_LOC_ID', locID);
			}
		});
	},
	
	/**
	* Set cookie from drop down list
	*
	* @param {String} loction (country name/{region name | empty}/city name)
	*/
	setCookieFromDropDown = function(location) {
		var locElems = location.split(', ');
		
		if (locElems.length == 2) {
			__setCookie('YS_GEO_IP_CITY', locElems[1] + '/empty/' + locElems[0]);
		} else {
			__setCookie('YS_GEO_IP_CITY', locElems[2] + '/' + locElems[1] + '/' + locElems[0]);
		}
	},
	
	/**
	* Set cookie from button click
	*
	* @param {String} loction (country name/{region name | empty}/city name)
	*/
	setCookieFromButtonClick = function() {
		var town = $('.ys-your-city span').eq(1).text();
		
		setCookieFromTownClick(town);
	},
	
	setCookieFromLocationID = function(locID, callback) {
		var async = false;
		if (typeof callback == "function") async = true;
		$.ajax({
			async: async,
			dataType: 'json',
			url: '/bitrix/components/yenisite/geoip.city/tools.php',
			data: 'locationID='+locID,
			success: function (data) {
				__setCookie('YS_GEO_IP_CITY', data.COUNTRY_NAME + '/' + data.REGION_NAME + '/' + data.CITY_NAME);
				$('a.ys-loc-city').text(data.CITY_NAME);
				if (typeof callback == "function") callback();
			}
		});
	},

	setLocationID = function(locID) {
		__setCookie('YS_GEO_IP_LOC_ID', locID);
	},
	
	/**
	* Get cookie by name
	* 
	* @private
	* @param {String} cookie name
	*/
	__getCookie = function(name) {
		var cookie = " " + document.cookie,
			search = " " + name + "=",
			setStr = null,
			offset = 0,
			end = 0;
			
		if (cookie.length > 0) {
			offset = cookie.indexOf(search);
			
			if (offset != -1) {
				offset += search.length;
				end = cookie.indexOf(";", offset);
				
				if (end == -1) {
					end = cookie.length;
				}
				setStr = decodeURIComponent(cookie.substring(offset, end));
				if (setStr.length < 1) {
					return null;
				}
			}
		}
		return setStr;
	},
	
	/**
	* Get town from cookie
	*
	* @param {String} cookie name
	*/
	getCookieTown = function(name) {
		var cookie = __getCookie(name);
		
		if (cookie !== null) {

			if (cookie.indexOf('/') == -1) {
				return cookie;
			} else {
				return cookie.split('/')[2];
			}
		}
		
		return null;
	},
	
	/**
	* Get town id from cookie
	*
	* @param {String} cookie name
	*/
	getCookieTownId = function(name) {
		var cookie = __getCookie(name),
			id;
		
		if (cookie !== null) {
			$.ajax({
				url: '/bitrix/components/yenisite/geoip.city/tools.php?town=' + encodeURI(cookie.split('/')[2]),
				async: false,
				success: function(out)	{
					id = out;
				}
			});
			
			return id;
		}
	},
	
	/**
	* Get country id from cookie
	*
	* @param {String} cookie name
	*/
	getCookieCountryId = function(name) {
		var cookie = __getCookie(name),
			id;
		
		if (cookie !== null) {
			$.ajax({
				url: '/bitrix/components/yenisite/geoip.city/tools.php?country=' + encodeURI(cookie.split('/')[0]),
				async: false,
				success: function(out)	{
					id = out;
				}
			});
			
			return id;
		}
	},
	
	/**
	* Get region id from cookie
	*
	* @param {String} cookie name
	*/
	getRegionId = function(cookieName) {
		var cookie = __getCookie(cookieName),
			id;
			
		if (cookie !== null && cookie.split('/')[1] != 'empty') {
			$.ajax({
				url: '/bitrix/components/yenisite/geoip.city/tools.php?regionName=' + encodeURI(cookie.split('/')[1]),
				async: false,
				success: function(out)	{
					id = out;
				}
			});
		} else {
			id ='';
		}
		return id;
	},

	getLocationID = function(cookieName) {
		return cookie = __getCookie(cookieName);
	},
	
	/**
	* Delete cookie from name
	*
	* @private
	* @param {String} cookie name
	*/
	__deleteCookie = function(cookieName) {
		var cookieDate = new Date();  
		cookieDate.setTime (cookieDate.getTime() - 1);
		document.cookie = cookieName += "=; path=/; expires=" + cookieDate.toGMTString();
	},
	
	/**
	* Get region from cookie
	*
	* @param {String} cookie name
	*/
	getRegionCookie = function(cookieName) {
		var cookie = __getCookie(cookieName);
		
		if (cookie !== null) {

			return cookie.split('/')[1] != 'empty' ? cookie.split('/')[1] : '';
		}
		
		return null;
	},
	
	/**
	* Get country from cookie
	*
	* @param {String} cookie name
	*/
	getCountryCookie = function(cookieName) {
		var cookie = __getCookie(cookieName);
		
		if (cookie !== null) {

			return cookie.split('/')[0];
		}
		
		return null;
	},

	getFullInfo = function(locID) {
		var info;

		$.ajax({
			url: '/bitrix/components/yenisite/geoip.city/tools.php?locationID=' + locID,
			async: false,
			dataType: "json",
			success: function(out)	{
				info = out;
			}
		});

		return info;
	};
	
	return {
		setCookieFromSelects: 		setCookieFromSelects,
		setCookieFromTownClick: 	setCookieFromTownClick,
		setCookieFromDropDown:		setCookieFromDropDown,
		setCookieFromButtonClick: 	setCookieFromButtonClick,
		setCookieFromLocationID:    setCookieFromLocationID,

		setLocationID: 				setLocationID,
		getLocationID: 				getLocationID,
		
		getCookieTown: 				getCookieTown,
		getCookieTownId:			getCookieTownId,
		
		getCountryCookie:			getCountryCookie,
		getCookieCountryId:			getCookieCountryId,
		
		getRegionCookie:			getRegionCookie,
		getRegionId:				getRegionId,
		getFullInfo: 				getFullInfo
		
	};
}());

/**
* Create YS.GeoIP.PopUpWindow object
* 
* @return {Object}
*/
YS.GeoIP.PopUpWindow = (function() {
	var yscookie = YS.GeoIP.Cookie;
	
	/**
	* Rebuild cities on popup window
	* 
	* @private
	*/
	__rebuildCities = function() {
		var town = yscookie.getCookieTown('YS_GEO_IP_CITY'),
			cities = [],
			locs = [],
			i = 1;

		var locId = parseInt(getLocationID('YS_GEO_IP_LOC_ID'));
		if (isNaN(locId)) locId = 0;
		var info = yscookie.getFullInfo(locId);
		if (info !== null && typeof info != "undefined")
			town = info.CITY_NAME;

		// console.log(info);
			
		if ($('.ys-your-city').length == 0) {
			var $span = $('.ys-loc-cities .ys-loc-first span').eq(1);
			cities.push( $span.text() );
			locs.push( $span.attr('data-location') );
		} else {
			var $span = $('.ys-your-city span').eq(1);
			cities.push( $span.text() );
			locs.push( $span.attr('data-location') );
		}
		
		$('.ys-loc-cities li a span').each(function() {
			cities.push($(this).text());
			locs.push($(this).attr('data-location'));
		});
		
		
		
		if (typeof town !== "undefined" && town !== "" && town !== null) {
			//cities = _.uniq(cities);
			for (var j=0; j<cities.length; j++) {
				if (typeof locs[j] == "undefined") locs[j] = 0;
				if (cities[j] != town) continue;
				cities.splice(j, 1);
				locs.splice(j, 1);
			}
			cities.unshift(town);
			locs.unshift(locId);
			
			if ($('.ys-your-city').length == 0) {
				$('.ys-loc-cities li a span').slice(1).each(function() {
					$(this).text(cities[i]);
					$(this).attr('data-location', locs[i]);
					i++;
				});
			} else {
				$('.ys-loc-cities li a span').each(function() {
					$(this).text(cities[i]);
					$(this).attr('data-location', locs[i]);
					i++;
				});
			}
			
			if ($('.ys-your-city').length == 0) {
				$('.ys-loc-first li').eq(0).addClass('ys-your-city');
				$('.ys-your-city').empty();
				$('.ys-your-city').append('<span class="sym">. </span><span></span>');
				$('.ys-your-city span').eq(1).text(town).attr('data-location', locId);
			} else {
				$('.ys-loc-cities .ys-loc-first span').eq(1).text(town).attr('data-location', locId);
				$('.ys-loc-cities li').eq(0).addClass('ys-your-city');
			}
		}
	},
	
	/**
	* Show popup window
	*/
	showPopUpGeoIP = function() {
		var town = yscookie.getCookieTown('YS_GEO_IP_CITY');

		var info = yscookie.getFullInfo(getLocationID('YS_GEO_IP_LOC_ID'));
		if (info !== null && typeof info !== "undefined")
			town = info.CITY_NAME;
		
		if (town !== null && town !== "" && typeof town !== "undefined") {
			$('.ys-city-header').text(town);
		}
		
		__rebuildCities();
		
		$('#ys-locator').css('display', 'block');
		$('#ys-geoip-mask').css('display', 'block');
	},
	
	/**
	* Hide popup window
	*/
	hidePopUpGeoIP = function() {
		$('#ys-locator').css('display', 'none');
		$('#ys-geoip-mask').css('display', 'none');
		$('.ys-loc-autocomplete').css('display', 'none');
		$('#ys-locator input.txt').val('');
	};
	
	return {
		showPopUpGeoIP: showPopUpGeoIP,
		hidePopUpGeoIP: hidePopUpGeoIP
	};
}());

/**
 * Create YS.GeoIP.AutoComplete object
 *
 * @return {Object}
 */
YS.GeoIP.AutoComplete = function () {
	var yscookie = YS.GeoIP.Cookie,
		yspopup = YS.GeoIP.PopUpWindow,

		/**
		 * Build AJAX location list
		 *
		 * @param {String} query
		 * @param {Function} callback
		 * @param {Object} parentInput
		 */
		buidList = function (query, callback, parentInput) {
			$.ajax({
				url: '/bitrix/components/yenisite/geoip.city/tools.php?query=' + encodeURI(query),
				success: function (out) {
					if (out.length > 0 && query.length == $('input.ys-city-query').val().length) {
						$('.ys-loc-autocomplete').empty().append(out).css('display', 'block');

						// set handlers for location information
						$('.ys-loc-autocomplete div').each(function () {

							$(this).hover(function () {
								$(this).addClass('ys-loc-autoc-selected');
							}, function () {
								$(this).removeClass('ys-loc-autoc-selected');
							});

							$(this).on('click', function () {
								var $this = $(this);
								var span = $this.find('span');
								yscookie.setLocationID(span.attr('id'));
								if (typeof parentInput != 'undefined') {
									if (!(parentInput instanceof jQuery)) {
										parentInput = $(parentInput);
									}
									parentInput.val($this.text());
								}
								if (span.attr("data-language") == 'native') {
									yscookie.setCookieFromDropDown($this.text());
									onNewCityHandler();
								} else {
									yscookie.setCookieFromLocationID(span.attr('id'), onNewCityHandler);
								}
								if (typeof callback == 'function') {
									callback(this);
								}
							});
						});
					}
				}
			});
			var onNewCityHandler = function() {
				// Set new Active Item for yenisite.geoipstore
				if ('GeoIPStore' in YS && 'updateActiveItem' in YS.GeoIPStore.Core) {
					YS.GeoIPStore.Core.updateActiveItem();
				} else if(typeof ysGeoStoreList != "undefined") {
					if(yscookie.getCookieTown('YS_GEO_IP_CITY') in ysGeoStoreList){
						if(ysGeoStoreList[yscookie.getCookieTown('YS_GEO_IP_CITY')] != ysGeoStoreActiveId){
							YS.GeoIPStore.Core.setActiveItem(ysGeoStoreList[yscookie.getCookieTown('YS_GEO_IP_CITY')]) ;
						}
					}
					else if (ysGeoStoreActiveId != ysGeoStoreDefault) {
						YS.GeoIPStore.Core.setActiveItem(ysGeoStoreDefault) ;
					}
				}

				yspopup.hidePopUpGeoIP();

				$('a.ys-loc-city').text(yscookie.getCookieTown('YS_GEO_IP_CITY'));
				$('a.ys-loc-city').css('display', 'inline');
				
				if (typeof callback == "function") {
					callback();
				}
			}
		};

	return {
		buildList: buidList
	};
}();