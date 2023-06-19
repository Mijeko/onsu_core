<?
define('STOP_STATISTICS', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (CModule::IncludeModule('statistic') && CModule::IncludeModule('sale')) {
	$countryId = $_REQUEST['countryId'];
	$cacheLifeTime = 604800;
	$obCache = new CPHPCache();
	
	if (!empty($_REQUEST['countryId']) && !empty($_REQUEST['region'])) {
		$regionName = $_REQUEST['region'];
		
		if ($obCache->InitCache($cacheLifeTime, $regionName, '/locator')) {
			$vars = $obCache->GetVars();
			$cities  = $vars['REGION_NAME'];
		}
		
		if (empty($cities)) {
			$dbCities = CSaleLocation::GetList(array(), array('COUNTRY_ID' => $countryId, 'REGION_NAME' => $regionName,
				'CITY_LID' => LANGUAGE_ID), false, false, array());
			while ($arCity = $dbCities->Fetch()) {
				if(!in_array($arCity['CITY_NAME'], $cities))
				{
					$cities[] = $arCity['CITY_NAME'];
				}
			}
			asort($cities, SORT_STRING);
		}
		
		foreach ($cities as $city) {
			if (!empty($city)) {
				$res .= '<option value="'.$city.'">'.$city.'</option>';
			}
		}
		
		$vars = $cities;
		
		if ($obCache->StartDataCache()) {
			$obCache->EndDataCache(Array('REGION_NAME' => $cities));
		}
		
		echo $res;
	}
	
	if (!empty($_REQUEST['cId'])) {
		$arRes = CSaleLocation::GetCountryList(array(), array('ID' => $_REQUEST['cId']))->Fetch();
		
		echo $arRes['NAME'];
	}
	
	if (!empty($_REQUEST['townName'])) {
		$townName = $_REQUEST['townName'];

		if (!defined('BX_UTF')) {
			$townName = $APPLICATION->ConvertCharset($townName, 'utf-8', 'windows-1251');
		}
			
		$arRes = CSaleLocation::GetList(array(), array('CITY_NAME' => $townName, 'COUNTRY_LID' => LANGUAGE_ID),
			false, false, array('ID', 'COUNTRY_NAME', 'REGION_NAME_ORIG'))->Fetch();
		
		echo $arRes['COUNTRY_NAME'], '/';

		if (!empty($arRes['REGION_NAME_ORIG'])) {
			$arRes2 = CSaleLocation::GetList(array(), array('CITY_NAME' => $townName,
				'COUNTRY_LID' => LANGUAGE_ID, 'REGION_LID' => LANGUAGE_ID), false, false, array('ID', 'REGION_NAME'))->Fetch();
				
			echo empty($arRes2['REGION_NAME']) ? $arRes['REGION_NAME_ORIG'] : $arRes2['REGION_NAME'], '/';
		}
		
		echo $arRes['ID'];
	}
	
	if (!empty($_REQUEST['town'])) {
		$town = $_REQUEST['town'];

		if (!defined('BX_UTF')) {
			$town = $APPLICATION->ConvertCharset($town, 'utf-8', 'windows-1251');
		}
			
		$arRes = CSaleLocation::GetList(array(), array('CITY_NAME' => $town, 'COUNTRY_LID' => LANGUAGE_ID),
			false, false, array('CITY_ID'))->Fetch();
		
		echo $arRes['CITY_ID'];
	}
	
	if (!empty($_REQUEST['country'])) {
		$country = $_REQUEST['country'];

		if (!defined('BX_UTF')) {
			$country = $APPLICATION->ConvertCharset($country, 'utf-8', 'windows-1251');
		}
			
		$arRes = CSaleLocation::GetList(array(), array('COUNTRY_NAME' => $country, 'COUNTRY_LID' => LANGUAGE_ID),
			false, false, array('COUNTRY_ID'))->Fetch();
		
		echo $arRes['COUNTRY_ID'];
	}
	
	if (!empty($_REQUEST['query'])) {	
		//$charsNum = strlen($query);

		if (!defined('BX_UTF')) {
			$query = $APPLICATION->ConvertCharset($query, 'UTF-8', 'WINDOWS-1251');
			//$charsNum /= 2;
		}
		$charsNum = strlen($query);
		
		// $strSql = 'SELECT NAME, LID, CITY_ID FROM b_sale_location_city_lang WHERE NAME LIKE "' . $DB->ForSql($query) . '%"';
		// $dbRes = $DB->Query($strSql, false, $err_mess.__LINE__);
		$dbRes = CSaleLocation::GetList(
				array(
					"SORT" => "ASC",
					"COUNTRY_NAME_LANG" => "ASC",
					"CITY_NAME_LANG" => "ASC"
				),
				array("~CITY_NAME" => $query.'%')
    );
		
		$arCityID = array();
		while ($ar = $dbRes->Fetch()) {
			if (in_array($ar['CITY_ID'], $arCityID)) continue;
			$arCityID[] = $ar['CITY_ID'];
			$sNative = $ar['CITY_LID'] == LANGUAGE_ID ? 'native' : 'foreign';

			$arRes = CSaleLocation::GetList(array(), array('CITY_ID' => $ar['CITY_ID'], 'COUNTRY_LID' => LANGUAGE_ID),
				false, false, array('ID', 'COUNTRY_NAME', 'REGION_ID'))->Fetch();
			$country = $arRes['COUNTRY_NAME'];

			if (intval($arRes['REGION_ID'])) {
				$arRes = CSaleLocation::GetList(array(), array('CITY_ID' => $ar['CITY_ID'], 'REGION_LID' => LANGUAGE_ID),
					false, false, array('ID', 'REGION_NAME'))->Fetch();

				if(!empty($arRes['REGION_NAME']))
				{
					$tmpStr = $arRes['REGION_NAME'] . ', ' . $country;

					$res .= '<div><strong>'.substr($ar['CITY_NAME'], 0, $charsNum).'</strong>'.
					substr($ar['CITY_NAME'], $charsNum) . ", " . $tmpStr . '<span id="'.$arRes['ID'].'" style="display: none" data-language="'.$sNative.'"></span></div>';

					continue;
				}
			}

			$res .= '<div><strong>'.substr($ar['CITY_NAME'], 0, $charsNum).'</strong>'.
				substr($ar['CITY_NAME'], $charsNum).", {$arRes['COUNTRY_NAME']}" . '<span id="'.$arRes['ID'].'" style="display: none" data-language="'.$sNative.'"></span></div>';
		}
		echo $res;
	}
	
	if (!empty($_REQUEST['countryId']) && empty($_REQUEST['region'])) {
		$dbRes = CSaleLocation::GetList(array(), array('COUNTRY_ID' => $countryId, 'CITY_LID' => LANGUAGE_ID),
			false, false, array());
			
		while ($arCity = $dbRes->Fetch()) {
			if(!in_array($arCity['CITY_NAME'], $cities))
			{
				$cities[$arCity['CITY_ID']] = $arCity['CITY_NAME'];
			}
		}
		
		asort($cities, SORT_STRING);
		foreach ($cities as $id=>$city) {
			if (!empty($city))
			{
				$res .= '<option value="'.$id.'">'.$city.'</option>';
			}
		}

		echo $res;
	}
	
	if ($_REQUEST['countries'] == 'Y') {
		$res = '';
		
		$dbRes = CSaleLocation::GetCountryList(array(), array());
		while ($arRes = $dbRes->Fetch()) {
			if (!in_array($arRes['NAME'], $countries)) {
				$countries[$arRes['ID']] = $arRes['NAME'];
			}
		}
		
		asort($countries, SORT_STRING);
		foreach ($countries as $id=>$country) {
			if (!empty($country)) {
				$country = htmlentities(strip_tags($country));
				$res .= '<option value="'.$id.'">'.$country.'</option>';
			}
		}

		echo $res;
	}
	
	if (!empty($_REQUEST['regionName'])) {
		if (!defined('BX_UTF')) {
			$_REQUEST['regionName'] = $APPLICATION->ConvertCharset($_REQUEST['regionName'], 'utf-8', 'windows-1251');
		}
		$arRes = CSaleLocation::GetList(array(), array('REGION_NAME' => $_REQUEST['regionName'], 'REGION_LID' => LANGUAGE_ID),
			false, false, array('REGION_ID'))->Fetch();
			
		echo $arRes['REGION_ID'];
	}

	if (!empty($_REQUEST['locationID'])) {
		$id = htmlspecialchars($_REQUEST['locationID']);
		
		$arLocation = CSaleLocation::GetById($id);
		
		$arRes = array();
		
		if (!empty($arLocation['COUNTRY_NAME'])) $arRes['COUNTRY_NAME'] = $arLocation['COUNTRY_NAME'];
		if (!empty($arLocation['REGION_NAME']))  $arRes['REGION_NAME']  = $arLocation['REGION_NAME'];
		if (!empty($arLocation['CITY_NAME']))    $arRes['CITY_NAME']    = $arLocation['CITY_NAME'];
		
		if (!defined('BX_UTF') && is_array($arRes)) {
			$arRes = $APPLICATION->ConvertCharsetArray($arRes, 'windows-1251', 'utf-8');
		}
		if (empty($arRes['REGION_NAME'])) $arRes['REGION_NAME'] = 'empty';

		die(json_encode($arRes));
	}

	unset($obCache);
}
?>