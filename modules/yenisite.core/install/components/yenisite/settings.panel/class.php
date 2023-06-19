<?php
use Bitrix\Main;
use Bitrix\Main\Loader;

Loader::includeModule('yenisite.core');

class CYSSettingsPanel extends CBitrixComponent
{
	const CACHE_DIR = 'romza_settings';
	const CACHE_TIME = 31536000;

	static private $_module = FALSE;
	static private $_method = FALSE;
	static private $_asDefault = FALSE;

	public function onPrepareComponentParams($arParams)
	{
		$arParams["CACHE_TIME"] = isset($arParams["CACHE_TIME"]) ? $arParams["CACHE_TIME"] : 36000000;
		if (Loader::includeModule($arParams['SOLUTION'])) {
			self::$_module = $arParams['SOLUTION'];
		}
		if (!$arParams['GLOBAL_VAR']) {
			$arParams['GLOBAL_VAR'] = "ys_options";
		}

		if (!isset($arParams['EDIT_SETTINGS']) || !is_array($arParams['EDIT_SETTINGS'])) {
			$arParams['EDIT_SETTINGS'] = array();
		}
		return $arParams;
	}

	static function getSettings($arSettings, $is_mobile = false, $SITE_ID = SITE_ID)
	{
		if (!is_array($arSettings)) {
			return false;
		}

		$options = self::loadFromCache($SITE_ID);

		/* DELETE AFTER 12.11.2016 */
		/**/$bEmpty = false;
		/**/if (empty($options)) {
		/**/	$bEmpty = true;
		/**/	$con = Main\Application::getConnection();
		/**/	$sqlHelper = $con->getSqlHelper();

		/**/	$res = $con->query(
		/**/		"SELECT SITE_ID, NAME ".
		/**/		"FROM b_option ".
		/**/		"WHERE (SITE_ID = '".$sqlHelper->forSql($SITE_ID)."' OR SITE_ID IS NULL) ".
		/**/		"    AND MODULE_ID = '". $sqlHelper->forSql(self::$_module)."' ".
		/**/		"    AND NAME LIKE '". $sqlHelper->forSql('%_UID_%') ."' "
		/**/	);
		/**/	while ($ar = $res->fetch())
		/**/	{
		/**/		COption::RemoveOption(self::$_module, $ar['NAME'], $SITE_ID);
		/**/	}
		/**/}
		/**/$cookiePrefix = COption::GetOptionString("main", "cookie_name", "BITRIX_SM") .'_'. $SITE_ID .'_';
		/* DELETE AFTER 12.11.2016 */

		foreach ($arSettings as $key => $setting) {
			//DELETE AFTER 12.11.2016
			/**/if (isset($_COOKIE[$cookiePrefix.$key])) {
			/**/	self::setToCookies($key, '', $SITE_ID);
			/**/}
			//DELETE AFTER 12.11.2016
			if (isset($setting['hidden']) && $setting['hidden'] == true) continue;
			if (isset($setting['values']) && is_array($setting['values'])) {
				foreach ($setting['values'] as $k => $v) {
					if (is_array($v)) {
						$setting['values'] = array_merge($setting['values'], $v);
						unset($setting['values'][$k]);
					}
				}
			}
			if (!$setting['custom']
				&& (
					!array_key_exists($key, $options) ||
					(isset($setting['values'])
						&& !isset($setting['values'][$options[$key]])
						&& !in_array($options[$key], $setting['values'])
					)
				)
			) {
				$options[$key] = self::getSetting($key, $setting['default'], array(), $SITE_ID);
			}

			if (empty($options[$key])) {
				$options[$key] = $setting['default'];
			}

			if (strpos($setting['type'], '_MOBILE') !== false) {
				$keyMob = $key . '_MOBILE';
				$valMob = !empty($setting['values_MOBILE']) ? $setting['values_MOBILE'] : $setting['values'];
				if (!array_key_exists($keyMob, $options) || (is_array($valMob) && !in_array($options[$keyMob], $valMob))) {
					$defMob = !empty($setting['default_MOBILE']) ? $setting['default_MOBILE'] : $setting['default'];
					$options[$keyMob] = self::getSetting($keyMob, $defMob, array(), $SITE_ID); //DELETE AFTER 12.11.2016
					//$options[$keyMob] = $defMob; //UNCOMMENT AFTER 12.11.2016
				}
				if ($is_mobile) {
					$bEmpty = false; //DELETE AFTER 12.11.2016
					$options[$key] = $options[$keyMob];
				}

				//DELETE AFTER 12.11.2016
				/**/if (isset($_COOKIE[$cookiePrefix . $keyMob])) {
				/**/	self::setToCookies($keyMob, '', $SITE_ID);
				/**/}
				//DELETE AFTER 12.11.2016
			}
		}

		/* DELETE AFTER 12.11.2016 */
		/**/if ($bEmpty) {
		/**/	$key = self::saveToCache($options, $SITE_ID);
		/**/	if (!empty($key)) {
		/**/		self::$_asDefault = TRUE;
		/**/		self::setKeyToOptions($key);
		/**/	}
		/**/}
		/* DELETE AFTER 12.11.2016 */

		return $options;
	}

	/**
	 * Get settings key from needed storage
	 * @param mixed|string $SITE_ID
	 * @return string Settings cache id
	 */
	static function getKey($SITE_ID = SITE_ID)
	{
		$func = "getKeyFrom" . self::_method();
		return self::$func($SITE_ID);
	}

	/**
	 * set settings
	 * @param array $arOptions
	 * @param bool $asDefault
	 * @param string $SITE_ID default SITE_ID
	 */
	static function setSettings($arOptions, $asDefault = false, $SITE_ID = SITE_ID)
	{
		$uniqKey = self::saveToCache($arOptions, $SITE_ID);
		$func = 'setKeyTo' . self::_method();

		if ($asDefault == true) {
			global $USER;
			if ($USER->IsAdmin()) {
				self::$_asDefault = $asDefault;
			}

			// for save default params in b_options
			foreach ($arOptions as $key => $setting) {
				self::setSetting($key, $setting, $asDefault);
			}
		}

		self::$func($uniqKey, $SITE_ID);
	}

	/**
	 * Save array with options to PHP Cache
	 * @param array $arOptions
	 * @param string $SITE_ID
	 * @return string
	 */
	protected static function saveToCache(array $arOptions, $SITE_ID = SITE_ID)
	{
		$key = md5(serialize($arOptions));
		$cachePath = self::_cachePath($SITE_ID);

		$obCache = new \CPHPCache;
		if ($obCache->InitCache(self::CACHE_TIME, $key, $cachePath, self::CACHE_DIR)) {
			if ($arOptions != $obCache->GetVars()) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				\CPHPCache::Clean($key, $cachePath, self::CACHE_DIR);
			}
		}
		if ($obCache->StartDataCache(self::CACHE_TIME, $key, $cachePath, array(), self::CACHE_DIR)) {
			$obCache->EndDataCache($arOptions);
		}
		return $key;
	}

	protected static function loadFromCache($SITE_ID = SITE_ID)
	{
		$key = self::getKey($SITE_ID);
		if (empty($key)) return array();

		$arCache = array();
		$obCache = new \Yenisite\Core\Cache();
		$cachePath = self::_cachePath($SITE_ID);
		if ($obCache->InitCache(self::CACHE_TIME, $key, $cachePath, self::CACHE_DIR)) {
			$arCache = $obCache->GetVars();
		}
		return $arCache;
	}

	protected static function _cachePath($SITE_ID = SITE_ID)
	{
		$path = '/' . $SITE_ID . '/yenisite_settings.panel/';
		if (self::$_module) {
			$path .= self::$_module . '/';
		}
		return $path;
	}

	protected static function _method()
	{
		if (self::$_method == FALSE) {
			global $USER;
			self::$_method = $USER->IsAuthorized() ? "Options" : "Cookies";
		}
		return self::$_method;
	}

	/**
	 * Get settings key from Options
	 * @param mixed|string $SITE_ID
	 * @return string Settings cache id
	 */
	private static function getKeyFromOptions($SITE_ID = SITE_ID)
	{
		global $USER;
		$prefix = self::getDemoPrefix();
		$optionDef = $prefix . 'SETTINGS_KEY_DEFAULT';
		$option = $prefix . 'SETTINGS_KEY_UID_' . $USER->GetID();

		$value = COption::GetOptionString(self::$_module, $option, false, $SITE_ID);
		if (!$value) {
			$value = COption::GetOptionString(self::$_module, $optionDef, '', $SITE_ID);
		}

		return $value;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection
	 * Get settings key from Cookies. If there is nothing - get from Options
	 * @param mixed|string $SITE_ID
	 * @return string Settings cache id
	 */
	private static function getKeyFromCookies($SITE_ID = SITE_ID)
	{
		global $APPLICATION;
		$prefix = self::getDemoPrefix();
		$cookie = $prefix . $SITE_ID . '_SETTINGS_KEY';
		$value = $APPLICATION->get_cookie($cookie);
		if (empty($value)) {
			$value = self::getKeyFromOptions();
		}
		return $value;
	}

	/**
	 * @noinspection PhpUnusedPrivateMethodInspection
	 * @param $key
	 * @param mixed|string $SITE_ID
	 */
	private static function setKeyToOptions($key, $SITE_ID = SITE_ID)
	{
		global $USER;
		$prefix = self::getDemoPrefix();
		if ($USER->IsAuthorized()) {
			$option = $prefix . 'SETTINGS_KEY_UID_' . $USER->GetID();
			COption::SetOptionString(self::$_module, $option, $key, false, $SITE_ID);
		}
		if (self::$_asDefault == TRUE) {
			$option = $prefix . 'SETTINGS_KEY_DEFAULT';
			COption::SetOptionString(self::$_module, $option, $key, false, $SITE_ID);
		}
	}

	/** @noinspection PhpUnusedPrivateMethodInspection
	 * @param string $key
	 * @param mixed|string $SITE_ID
	 */
	private static function setKeyToCookies($key, $SITE_ID = SITE_ID)
	{
		global $APPLICATION;
		$prefix = self::getDemoPrefix();
		$cookie = $prefix . $SITE_ID . '_SETTINGS_KEY';
		$domain = $APPLICATION->GetCookieDomain();
		if ($domain) {
			$domain = '.' . $domain;
		} else {
			$domain = false;
		}
		$APPLICATION->set_cookie($cookie, $key, false, "/", $domain);
	}

	/**************************************************************************
	 *     DEPRECATED - delete all this after 12.11.2016                      *
	 **************************************************************************/

	/**
	 * get settings by $method
	 * @param string $option
	 * @param string $default
	 * @param array $values - for version support
	 * @param mixed|string $SITE_ID
	 * @return string
	 * @deprecated
	 */
	static function getSetting($option, $default = "", $values = array(), $SITE_ID = SITE_ID)
	{
		return self::getFromOptions($option, $default, $SITE_ID);
	}

	/**
	 * set settings
	 * @param string $option
	 * @param string $value
	 * @param bool $asDefault
	 * @param mixed|string $SITE_ID
	 * @deprecated
	 */
	static function setSetting($option, $value, $asDefault = FALSE, $SITE_ID = SITE_ID) {
		$func = "setTo" . self::_method();

		if ($asDefault == TRUE) {
			global $USER;
			if ($USER->IsAdmin())
				self::$_asDefault = $asDefault;
		}

		self::$func($option, $value, $SITE_ID);
	}

	/**
	 * @param string $option
	 * @param string $default
	 * @param mixed|string $SITE_ID
	 * @return string
	 * @deprecated
	 */
	private static function getFromOptions($option, $default = "", $SITE_ID = SITE_ID)
	{
		$k = $option;
		$value = COption::GetOptionString(self::$_module, $k, $default, $SITE_ID);
		return $value;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection
	 * @param string $option
	 * @param string $default
	 * @param array $values
	 * @param mixed|string $SITE_ID
	 * @return string
	 * @deprecated
	 */
	private static function getFromCookies($option, $default = "", $values = array(), $SITE_ID = SITE_ID) {
		global $APPLICATION;
		$cookiePrefix = $SITE_ID . '_';
		$key = $cookiePrefix . $option;
		$value = $APPLICATION->get_cookie($key);
		if (!$value || (!empty($values) && !in_array($value, $values))) {
			if(!isset($values[$value])) {
				$value = self::getFromOptions($option, $default, $SITE_ID);
			}
		}

		return $value;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection
	 * @param $option
	 * @param $value
	 * @param mixed|string $SITE_ID
	 * @deprecated
	 */
	private static function setToOptions($option, $value, $SITE_ID = SITE_ID) {
		if (self::$_asDefault == TRUE) {
			$key = $option;
			COption::SetOptionString(self::$_module, $key, $value, false, $SITE_ID);
		}
	}

	/** @noinspection PhpUnusedPrivateMethodInspection
	 * @param $option
	 * @param $value
	 * @param mixed|string $SITE_ID
	 * @deprecated
	 */
	private static function setToCookies($option, $value, $SITE_ID = SITE_ID) {
		global $APPLICATION;
		$cookiePrefix = $SITE_ID . '_';
		$key = $cookiePrefix . $option;
		$domain = $APPLICATION->GetCookieDomain();
		if ($domain) {
			$domain = '.' . $domain;
		} else {
			$domain = false;
		}
		$APPLICATION->set_cookie($key, $value, 0, '/', $domain); //delete cookie
	}

	private static function getDemoPrefix()
	{
		$prefix = '';
		if (defined('IS_DEMO') && IS_DEMO) {
			$demoId = (int)$_REQUEST['demo_content'];
			if ($demoId <= 0) {
				$demoId = (int)$_SESSION['RZ_DEMO_ID'];
			}
			if($demoId <= 0 && defined('DEFAULT_DEMO_ID')) {
				$demoId = DEFAULT_DEMO_ID;
			}
			if ($demoId > 0) {
				$prefix = $demoId . '_';
			}
		}
		return $prefix;
	}

	public static function saveFilesFromServer ($arFiles){
	    foreach ($arFiles as $fileName => $value){
	        if (strpos($arFiles[$fileName]['type'],'image') === false) continue;
            if (empty($arFiles[$fileName]['error'])){
                $folder = $_SERVER['DOCUMENT_ROOT'].str_replace(array('file-custom--','-','_'),array('','/','.'),$fileName).basename($arFiles[$fileName]['name']);
                if (move_uploaded_file($arFiles[$fileName]['tmp_name'], $folder)) {
                    echo "<div style='display:none' class='upload_file_info'>succses</div>";
                } else{
                    echo "<div style='display:none' class='upload_file_info'>fail</div>";
                }
            } else{
                switch ($arFiles[$fileName]['error']){
                    case 1:
                        echo "<div style='display:none' class='upload_file_info'>max_size from upload_max_file_size</div>";
                    break;
                    case 2:
                        echo "<div style='display:none' class='upload_file_info'>max_size from MAX_FILE_SIZE</div>";
                    break;
                    case 3:
                        echo "<div style='display:none' class='upload_file_info'>not full get file</div>";
                    break;
                    case 4:
                        echo "<div style='display:none' class='upload_file_info'>file was not load</div>";
                    break;
                    case 6:
                        echo "<div style='display:none' class='upload_file_info'>tmp folder not exist</div>";
                    break;
                    case 7:
                        echo "<div style='display:none' class='upload_file_info'>fail write file on disk</div>";
                    break;
                    case 8:
                        echo "<div style='display:none' class='upload_file_info'>extends of PHP stop load</div>";
                    break;

                }
            }
        }
    }
}