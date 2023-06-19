<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!isset($arParams['CACHE_TIME'])) {
	$arParams['CACHE_TIME'] = 3600;
}

if ($arParams['INCLUDE_JQUERY'] != 'Y') $arParams['INCLUDE_JQUERY'] = 'N';

if (!function_exists('rz_getCookieDomain')) {
	function rz_getCookieDomain()
	{
		$arSite = CSite::GetByID(SITE_ID)->Fetch();		
		if (!empty($arSite['SERVER_NAME'])) {
			$arDomains[] = $arSite['SERVER_NAME'];
		}
		else
		{
			$arDomains = preg_split("/\n|\r/", $arSite['DOMAINS'], -1, PREG_SPLIT_NO_EMPTY);
		}		
		foreach($arDomains as $domain)
		{
			if (strpos($_SERVER['HTTP_HOST'], $domain) !== false) return $domain;
		}
		return '';
	}
}

// -----
// old code exists for compatibility with empty sale location database
$arRUCitiesXMLIDOnMain = Array(
	0 => 2097, // Moscow
	1 => 2287, // St.Pererburg
	2 => 2012, // Novosibirsk
	3 => 1283, // Kazan
	4 => 2732, // Ekaterinburg
	5 => 1427, // Krasnodar
	6 => 794,  // Vladivostok
	7 => 1235, // Rostov-on-Don,
	8 => 1428, // Krasnoyarsk
	9 => 2910, // Chelyabinsk
);

$arUACitiesXMLIDOnMain = Array(
	0 => 187, // Kiev
	1 => 313, // Harkiv
	2 => 458, // Odessa
	3 => 370, // Dnepropetrovsk
	4 => 394, // Donetsk
	5 => 223, // Lviv
	6 => 490, // Sevastopol
	7 => 491, // Simferopol,
	8 => 414, // Zaporozhie
	9 => 371, // Krivoy Rog
);
// -----

for ($i = 0; $i < count($arRUCitiesXMLIDOnMain); $i++) {
	$xmlIDRUs .= $arRUCitiesXMLIDOnMain[$i].',';
}
$xmlIDs = substr($xmlIDRUs, 0, -1);

for ($i = 0; $i < count($arUACitiesXMLIDOnMain); $i++) {
	$xmlIDUAs .= $arUACitiesXMLIDOnMain[$i].',';
}
$arParams['RELOAD_PAGE'] = $arParams['RELOAD_PAGE'] ?: 'Y';
if($arParams['RELOAD_PAGE'] != 'Y') {
	$Asset = \Bitrix\Main\Page\Asset::getInstance();
	$Asset->addString('<script type="text/javascript">
	if (typeof RZB2 == "undefined") {
		RZB2 = {};
	}
	RZB2.GEOIP_NO_RELOAD = "Y";
	YS_LOCATOR_NO_RELOAD = "Y";
</script>');
}

if (
	!empty($_COOKIE['YS_GEO_IP_LOC_ID']) &&
	$arParams['UNITE_WITH_STORE'] === 'Y' &&
	CModule::IncludeModule('yenisite.geoipstore') &&
	method_exists('CYSGeoIPStore', 'getItemFromHttpHost')
) {
	$newStoreItem = CYSGeoIPStore::getItemFromHttpHost($bNew = true);
	if ($newStoreItem) {
		CYSGeoIP::clearCookies();
		$arYourLoc = array('ID' => $newStoreItem['LOCATION_ID_DELIVERY']);
		//CYSGeoIP::setCityBySaleLocation($arYourLoc);
	}
}

$this->setResultCacheKeys(array('DOMAIN'));

if (CModule::IncludeModule("statistic") && CModule::IncludeModule('sale') && CModule::IncludeModule('yenisite.geoip')) {
	$bCityFromRequest = false;

	if (!empty($_REQUEST['cityId'])) {
		$obCity = new CCity();
		$arCity = $obCity->GetList(array(), array("=CITY_ID" => (int)$_REQUEST['cityId']))->Fetch();
		if (!empty($arCity['CITY_NAME'])) {
			CYSGeoIP::clearCookies();
			$arResult['CITY_IP'] = $arCity['CITY_NAME'];
			$bCityFromRequest = true;
		}
	}

	$cacheId = $_COOKIE['YS_GEO_IP_LOC_ID'] . $_COOKIE['YS_GEO_IP_CITY'] . LANGUAGE_ID . '1.5.1';

	if ($this->StartResultCache(false, $cacheId)) {
		$cookieCityID = intval($_COOKIE['YS_GEO_IP_LOC_ID']);
		$arResult['CITY_ID'] = $cookieCityID;

		if (empty($cookieCityID) && empty($_COOKIE['YS_GEO_IP_CITY'])) {
			//first time visitor
			$this->AbortResultCache();

			if (empty($arResult['CITY_IP']) && empty($arYourLoc)) {
				$obCity = new CCity();
				$arCityInfo = $obCity->GetFullInfo();
				$arResult['CITY_IP'] = $arCityInfo['CITY_NAME']['VALUE'];
			}
			if (!empty($arResult['CITY_IP'])) {
				$arYourLoc = CSaleLocation::GetList(array('sort'=>'asc'),
					array('CITY_NAME' => $arResult['CITY_IP']),
					false, false, array('ID'))->Fetch();
			}
			if (!empty($arYourLoc['ID'])) {
				$arYourLoc = CSaleLocation::GetByID($arYourLoc['ID'], LANGUAGE_ID);
				$arResult['CITY_IP'] = $arYourLoc['CITY_NAME'];
				$arResult['CITY_ID'] = $arYourLoc['ID'];
				//set up cookie if confirmation disabled
				if ($arParams['DISABLE_CONFIRM_POPUP'] == 'Y' || $bCityFromRequest) {
					CYSGeoIP::setCityBySaleLocation($arYourLoc);
					$arResult['CITY_INLINE'] = $arYourLoc['CITY_NAME'];
				}
			}
		} else if (!empty($cookieCityID)) {
			// if we have this city in location database we need to get its name in current language
			$arRes = CSaleLocation::GetByID($cookieCityID, LANGUAGE_ID);
			$arResult['CITY_INLINE'] = $arRes['CITY_NAME'];
			$arResult['CITY_IP'] = $arRes['CITY_NAME'];
		} else {
			//for cities not in sale location database
			$cityName = urldecode($_COOKIE['YS_GEO_IP_CITY']);
			$cityName = substr(strrchr($cityName, '/'), 1);
			$arResult['CITY_IP'] = ($arResult['CITY_INLINE'] = htmlspecialcharsBx($cityName));
		}

		//new code to create cities list from sale location database
		$arResult['CITY'] = array();
		$arResult['LOCATION'] = array();
		for ($i = 1; $i < 10; $i++) {
			$locId = $arParams['CITY_'.$i];
			if (empty($locId)) continue;
			
			$arRes = CSaleLocation::GetList(array(),
				array('ID' => $locId, 'CITY_LID' => LANGUAGE_ID),
				false, false, array('CITY_NAME'))->Fetch();
			
			if (empty($arRes['CITY_NAME'])) continue;
			if (in_array($arRes['CITY_NAME'], $arResult['CITY'])) continue;
			if ($arResult['CITY_IP'] === $arRes['CITY_NAME']) continue;
			
			$arResult['CITY'][] = $arRes['CITY_NAME'];
			$arResult['LOCATION'][] = $locId;
		}
		//old code exists for compatibility with empty sale location database
		if (empty($arResult['CITY'])) {
			if ($arCityInfo['COUNTRY_CODE']['VALUE'] == 'UA') {
				$xmlIDs = substr($xmlIDUAs, 0, -1);
			}
			
			$strSql = "SELECT NAME FROM b_stat_city WHERE XML_ID IN ({$xmlIDs})";
			$res = $DB->Query($strSql, false, $err_mess.__LINE__);
			
			while ($arCity = $res->Fetch()) {
				if ( !in_array($arCity['NAME'], $arResult['CITY']) && ($arResult['CITY_IP'] !== $arCity['NAME']) ) {
					$arResult['CITY'][] = $arCity['NAME'];
					$arResult['LOCATION'][] = 0;
				}
			}
		}
		
		//array_unique($arResult['CITY']);
		
		if (!empty($arResult['CITY_IP'])) {
			array_unshift($arResult['CITY'], $arResult['CITY_IP']);
			array_unshift($arResult['LOCATION'], 0);
		}

		$arResult['DOMAIN'] = rz_getCookieDomain();

		$this->IncludeComponentTemplate();
	} //if ($this->StartResultCache())
} //if (CModule::IncludeModule("statistic" .. 'sale' .. 'yenisite.geoip'))
else {
	if ($this->StartResultCache()) {
		$arResult['DOMAIN'] = rz_getCookieDomain();
	}
	//CYSGeoIP::InitLocator();
	$this->IncludeComponentTemplate();
}
CYSGeoIP::InitLocator($arParams['INCLUDE_JQUERY'] == 'Y');
?>
<script type="text/javascript">
if (typeof YS == "object" && typeof YS.GeoIP == "object") {
	YS.GeoIP.hiddenDomain = "<?=$arResult['DOMAIN']?>";
}
</script>