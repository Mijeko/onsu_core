<?
/**
* Yenisite GeoIP module class
*
* @author Pavel Ivanov
* @Skype: hardyjazz
* @email: pavel@yenisite.ru
*/

class CYSGeoIP
{
	/**
	 * Module ID
	 */
	const moduleID = 'yenisite.geoip';

	/**
	 * Handler for OnBeforeUserAdd event
	 * @param &$arFields
	 */
	public static function OnBeforeUserAddHandler(&$arFields)
	{
		$cookie = $_COOKIE['GeoIP-city'];
		if (LANG_CHARSET != 'UTF-8') {
			$cookie = iconv('utf-8', 'windows-1251', $cookie);
		}
		
		$arItems = explode('/', $cookie);
		
		$arCountries = GetCountryArray();
		
		foreach ($arCountries['reference'] as $id => $country) {
			if ($arItems[0] == $country) {
				$countryId = $arCountries['reference_id'][$id];
			}
		}

		if (!empty($countryId)) {
			$arFields['PERSONAL_COUNTRY'] = $countryId;
		}

		if ($arItems[1] != 'empty' && strlen($arItems[1]) > 0) {
			$arFields['PERSONAL_STATE'] = $arItems[1];
		}

		if (!empty($arItems[2])) {
			$arFields['PERSONAL_CITY'] 	= $arItems[2];
		}
	}

	public static function OnBeforeComponentNewProfileGetOrderValueHandler(&$arProps, &$arValues)
	{
		$cityId = self::getLocationId();
		$cityName = self::getCityName();
		if ($cityId <= 0) return;

		foreach ($arProps as $arPropGroup) {
			foreach ($arPropGroup['PROPS'] as $arProp) {
				if ($arProp['TYPE'] != 'LOCATION') continue;
				if (!empty($arValues['ORDER_PROP_'.$arProp['ID']])) continue;

				$arValues['ORDER_PROP_'.$arProp['ID']] = $cityId;

				if (empty($cityName) || intval($arProp['INPUT_FIELD_LOCATION']) <= 0) continue;
				if (!empty($arValues['ORDER_PROP_'.$arProp['INPUT_FIELD_LOCATION']])) continue;

				$arValues['ORDER_PROP_'.$arProp['INPUT_FIELD_LOCATION']] = $cityName;
			}
		}
	}
	
	/**
	 * Handler for OnSaleComponentOrderProperties event
	 * @param &$arUserResult
	 * @param $request
	 * @param &$arParams
	 * @param &$arResult
	 */
	public static function OnSaleComponentOrderProperties(&$arUserResult, $request, &$arParams, &$arResult) {
		self::OnSaleComponentOrderOneStepOrderPropsHandler($arResult, $arUserResult, $arParams);

		$arUserResult["PROFILE_CHANGE"] = 
			intval($arUserResult["PROFILE_ID"]) <= 0 && $request->get('is_ajax_post') != 'Y' && $_REQUEST['via_ajax'] != 'Y' && $arUserResult['PERSON_TYPE_ID'] != $arUserResult['PERSON_TYPE_OLD']
			? 'Y' 
			: $arUserResult["PROFILE_CHANGE"];
		
		self::OnSaleComponentOrderOneStepPersonTypeHandler($arResult, $arUserResult, $arParams);
	}
	
	/**
	 * Handler for OnSaleComponentOrderOneStepPersonType event
	 * @param &$arResult
	 * @param &$arUserResult
	 * @param &$arParams
	 */
	public static function OnSaleComponentOrderOneStepPersonTypeHandler(&$arResult, &$arUserResult, &$arParams) {
		global $APPLICATION;
		$cityId = self::getLocationId();
		$cityName = self::getCityName();
		if ($cityId <= 0) return;

		if($arUserResult["PROFILE_CHANGE"] == "Y" && intval($arUserResult["PROFILE_ID"]) <= 0 && !isset($arUserResult['GEOIP_WAS_HERE'])) {
			$arUserResult['ORDER_PROP'] = array();
			$arUserResult["PROFILE_CHANGE_ORIGIN"] = $arUserResult["PROFILE_CHANGE"];
			$arUserResult['PROFILE_CHANGE'] = "N";
		}
		$arFilter = array("TYPE" => "LOCATION", 'PERSON_TYPE_ID' => $arUserResult["PERSON_TYPE_ID"], "ACTIVE" => "Y");
		$dbProperties = CSaleOrderProps::GetList(
			array(),
			$arFilter,
			false,
			false,
			array("ID", 'INPUT_FIELD_LOCATION', 'USER_PROPS')
		);
		while ($arProperties = $dbProperties->GetNext()) {
			$cityCode = (method_exists('CSaleLocation','getLocationCODEbyID')) ? CSaleLocation::getLocationCODEbyID($cityId) : $cityId;
			if(empty($arUserResult['ORDER_PROP'][$arProperties['ID']])) {
				$arUserResult['ORDER_PROP'][$arProperties['ID']] = $cityCode;
			}

			if ($arUserResult["PROFILE_CHANGE"] == "Y" && intval($arUserResult["PROFILE_ID"]) <= 0 && $arUserResult['GEOIP_WAS_HERE'] === 'Y') {
				if(!empty($cityName)
					&& intval($arProperties['INPUT_FIELD_LOCATION'])>0 
					&& $cityCode == '' 
					&& empty($arUserResult['ORDER_PROP'][$arProperties['INPUT_FIELD_LOCATION']])
				) {
					$arUserResult['ORDER_PROP'][$arProperties['INPUT_FIELD_LOCATION']] = $cityName;
				}
				$keySelected = false;
				$bFound = false;
				$flag = $arProperties["USER_PROPS"]=="Y" ? 'Y' : 'N';
				foreach ($arResult["ORDER_PROP"]["USER_PROPS_".$flag][$arProperties["ID"]]["VARIANTS"] as $key => &$arVariant) {
					if ($arVariant["SELECTED"] === "Y") {
						unset($arVariant["SELECTED"]);
						$keySelected = $key;
					}
					if ($arVariant["ID"] == $cityId) {
						$arVariant["SELECTED"] = "Y";
						$bFound = true;
					}
					if ($keySelected !== false && $bFound) break;
				}
				if (isset($arVariant)) {
					unset($arVariant);
				}
				
				if(!isset($arResult["ORDER_PROP"]["USER_PROPS_".$flag][$arProperties["ID"]]["VARIANTS"]) || empty($arResult["ORDER_PROP"]["USER_PROPS_".$flag][$arProperties["ID"]]["VARIANTS"])) $bFound = true;
				if (!$bFound) {
					$arResult["ORDER_PROP"]["USER_PROPS_".$flag][$arProperties["ID"]]["VARIANTS"][$keySelected]["SELECTED"] = "Y";
				} else {
					$arResult["ORDER_PROP"]["USER_PROPS_".$flag][$arProperties["ID"]]['VALUE'] = $cityCode;
					$arUserResult['ORDER_PROP'][$arProperties['ID']] = $cityCode;
					$arUserResult["DELIVERY_LOCATION"] = $cityCode;
					if ($arResult["ORDER_PROP"]["USER_PROPS_".$flag][$arProperties["ID"]]["IS_LOCATION4TAX"] == "Y") {
						$arUserResult["TAX_LOCATION"] = $arVariants['ID'];
					}
				}
			}
		}
	}
	/**
	 * Handler for OnSaleComponentOrderOneStepOrderProps event
	 * @param &$arResult
	 * @param &$arUserResult
	 * @param &$arParams
	 */
	public static function OnSaleComponentOrderOneStepOrderPropsHandler(&$arResult, &$arUserResult, &$arParams) {
		if (isset($arUserResult['PROFILE_CHANGE_ORIGIN'])) {
			$arUserResult['PROFILE_CHANGE'] = $arUserResult['PROFILE_CHANGE_ORIGIN'];
			unset($arUserResult['PROFILE_CHANGE_ORIGIN']);
		}
		$arUserResult['GEOIP_WAS_HERE'] = 'Y';
	}

	/**
	 * Init locator.js
	 * @param $jquery (bool) - include jquery from core?
	 */
	public static function InitLocator($jquery)
	{
		CJSCore::RegisterExt(
			'underscore',
			array(
				'js'  => '/bitrix/js/yenisite.geoip/underscore-min.js',
			)
		);

		$arRel = array();
		if ($jquery) $arRel[0] = 'jquery';
		$arRel[] = 'underscore';

		CJSCore::RegisterExt(
			'yslocator',
			array(
				'js'   => '/bitrix/js/' . self::moduleID . '/locator.js',
				'rel'  => $arRel,
			)
		);

		CJSCore::Init('yslocator');
	}

	/**
	 * Get ID of user's sale database location stored in cookies
	 * that was auto-recognized or manually set
	 */
	public static function getLocationId()
	{
		global $APPLICATION;
		return intval($APPLICATION->get_cookie('GEO_IP_LOC_ID', 'YS'));
	}

	/**
	 * Get russian name of user's city stored in cookies
	 * that was auto-recognized or manually set
	 */
	public static function getCityName()
	{
		global $APPLICATION;
		$cityName = $APPLICATION->get_cookie('GEO_IP_CITY', 'YS');
		$cityName = substr($cityName, strrpos($cityName, '/') + 1);
		if (!defined('BX_UTF') && !empty($cityName)) {
			$cityName = $APPLICATION->ConvertCharset($cityName, "utf-8", "windows-1251");
		}
		return $cityName;
	}

	/**
	 * Set user city and send cookies
	 *
	 * @param array $arLocation
	 * 'ID' - mandatory field with sale location identifier
	 * 'COUNTRY_NAME' - optional
	 * 'REGION_NAME' - optional
	 * 'CITY_NAME' - optional
	 *
	 * @return bool
	 */
	public static function setCityBySaleLocation(array $arLocation)
	{
		global $APPLICATION;

		if (empty($arLocation['COUNTRY_NAME']) || empty($arLocation['CITY_NAME'])) {
			if (empty($arLocation['ID']))        return false;
			if (!CModule::IncludeModule('sale')) return false;
			$arLocation = CSaleLocation::GetByID($arLocation['ID'], LANGUAGE_ID);
		}
		if (empty($arLocation)) return false;

		// convert to UTF-8 if needed
		if (!defined('BX_UTF')) {
			$arLocation = $APPLICATION->ConvertCharsetArray($arLocation, 'windows-1251', 'utf-8');
		}
		// fill cookie values
		$locName = $arLocation['COUNTRY_NAME']
			. (empty($arLocation['REGION_NAME']) ? '/empty/' : "/{$arLocation['REGION_NAME']}/")
			. $arLocation['CITY_NAME'];
		$locId = $arLocation['ID'];

		// add cookie headers
		/** @see bitrix/components/yenisite/geoip.city/component.php for rz_getCookieDomain() function declaration */
		$APPLICATION->set_cookie('GEO_IP_CITY', $locName, false, "/", rz_getCookieDomain(), false, false, 'YS');
		$APPLICATION->set_cookie('GEO_IP_LOC_ID', $locId, false, "/", rz_getCookieDomain(), false, false, 'YS');

		// change cookie for current session
		$_COOKIE['YS_GEO_IP_CITY'] = $locName;
		$_COOKIE['YS_GEO_IP_LOC_ID'] = $locId;

		return true;
	}

	/**
	 * Clear all cookies from this module
	 */
	public static function clearCookies()
	{
		global $APPLICATION;

		/** @see bitrix/components/yenisite/geoip.city/component.php for rz_getCookieDomain() function declaration */
		$APPLICATION->set_cookie('GEO_IP_CITY', '', 0, "/", rz_getCookieDomain(), false, false, 'YS');
		$APPLICATION->set_cookie('GEO_IP_LOC_ID', '', 0, "/", rz_getCookieDomain(), false, false, 'YS');

		unset($_COOKIE['YS_GEO_IP_CITY']);
		unset($_COOKIE['YS_GEO_IP_LOC_ID']);
	}
}
?>