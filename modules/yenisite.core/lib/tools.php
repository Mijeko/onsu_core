<?php
namespace Yenisite\Core;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);

/** @noinspection PhpInconsistentReturnPointsInspection */
class Tools
{
	static private $_arJsFiles = array();
	static private $_JsFileCounter = 0;
    static private $_cacheDir = '/romza_cache/';
    static private $_cacheTime = 604800;

	/**
	 * Добавляет в стек путь к JS
	 * @param string $jsFilePath
	 * @param bool $useAsset
	 * @return bool
	 */
	static public function addDeferredJS($jsFilePath, $useAsset = false)
	{
		if ($useAsset) {
			static $Asset;
			if (!isset($Asset)) {
				$Asset = Asset::getInstance();
			}
			$Asset->addJs($jsFilePath);
		} else {
			if (!in_array($jsFilePath, self::$_arJsFiles)) {
				if (substr($jsFilePath, -3) == '.js') {
					if (is_file($_SERVER['DOCUMENT_ROOT'] . $jsFilePath)) {
						self::$_arJsFiles[self::$_JsFileCounter] = $jsFilePath;
						self::$_JsFileCounter++;
						return true;
					} elseif (is_file($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/' . $jsFilePath)) {
						self::$_arJsFiles[self::$_JsFileCounter] = SITE_TEMPLATE_PATH . '/' . $jsFilePath;
						self::$_JsFileCounter++;
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * Выводит поочередно JS файлы
	 * @return string
	 */
	static public function getDeferredJS()
	{
		$returnString = '';
		foreach (self::$_arJsFiles as $jsFilePath) {
			$returnString .= '<script type="text/javascript" src="' . $jsFilePath . '"></script>' . "\n";
		}
		return $returnString;
	}

	/**
	 * Получает массив JS файлов
	 * @return array
	 */
	static public function getDeferredJSFilesList()
	{
		return self::$_arJsFiles;
	}

	/**
	 * Отложенка для getDeferredJS
	 */
	static public function showDeferredJS()
	{
		/** @var \CMain $APPLICATION */
		global $APPLICATION;
		$APPLICATION->AddBufferContent(__CLASS__ . '::getDeferredJS');
	}

	/**
	 * Ищет и добавляет JS с учетом того что он подключен из компонента
	 * @static
	 * @param \CBitrixComponent|string $component
	 * @param null $jsFilePath
	 * @return bool
	 */
	static public function addComponentDeferredJS($component, $jsFilePath = null)
	{
		/** @var \CMain $APPLICATION */
		$templateFolder = null;
		if ($component instanceof \CBitrixComponent) {
			$templateFolder = $component->__template->__folder;
		} elseif ($component instanceof \CBitrixComponentTemplate) {
			$template = &$component;
			$templateFolder = $template->__folder;
		} elseif (is_string($component)) {
			$component = str_replace(array('\\', '//'), '/', $component);
			if (
				($bxrootpos = strpos($component, BX_ROOT . '/templates')) !== false
				||
				($bxrootpos = strpos($component, BX_ROOT . '/components')) !== false
			) {
				$component = substr($component, $bxrootpos);
			}
			if (($extpos = strrpos($component, '.php')) !== false
				|| ($extpos = strrpos($component, '.js')) !== false
			) {
				if ($dirseppos = strrpos($component, '/')) {
					$templateFolder = substr($component, 0, $dirseppos);
					if ($jsFilePath == null && strrpos($component, '.js') !== false) {
						$jsFilePath = substr($component, $dirseppos);
						while (substr($jsFilePath, 0, 1) == '/') {
							$jsFilePath = substr($jsFilePath, 1);
						}
					}
				}
			} else {
				$templateFolder = $component;
			}
		}
		if ($jsFilePath == null) {
			if (is_file($_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/script_deferred.js')) {
				$jsFilePath = str_replace(
					array('//', '///'), array('/', '/'),
					$templateFolder . '/script_deferred.js'
				);
				if (!in_array($jsFilePath, self::$_arJsFiles)) {
					self::$_arJsFiles[self::$_JsFileCounter] = $jsFilePath;
					self::$_JsFileCounter++;
					return true;
				}
				return true;
			}
		} elseif (is_file($_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/' . $jsFilePath)) {
			$jsFilePath = str_replace(
				array('//', '///'), array('/', '/'),
				$templateFolder . '/' . $jsFilePath
			);
			if (substr($jsFilePath, -3) == '.js') {
				if (!in_array($jsFilePath, self::$_arJsFiles)) {
					self::$_arJsFiles[self::$_JsFileCounter] = $jsFilePath;
					self::$_JsFileCounter++;
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Determine if it is AJAX request
	 * @return bool
	 */
	static public function isAjax()
	{
		return \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest();
	}

	/**
	 * Determine if it is composite dynamic request
	 * @return bool
	 */
	static public function isComposite()
	{
		return \Bitrix\Main\Page\Frame::isAjaxRequest();
	}

	/**
	 * Determine if component caches his result from its parameters
	 * @return bool
	 */
	static public function isComponentCacheOn(array $arParams)
	{
		switch ($arParams['CACHE_TYPE']) {
			case 'Y':
				return true;
			case 'N':
				return false;
			case 'A':
			default:
				return (\COption::GetOptionString("main", "component_cache_on", "Y") === "Y");
		}
	}

	/**
	 * Копия функции битрикса для определения логической кодировки сайта
	 * Копия потому что функция битрикса требует $this а нам нужна статика
	 * @return string
	 */
	static public function getLogicalEncoding()
	{
		if (defined('BX_UTF')) {
			$logicalEncoding = "utf-8";
		} elseif (defined("SITE_CHARSET") && (strlen(SITE_CHARSET) > 0)) {
			$logicalEncoding = SITE_CHARSET;
		} elseif (defined("LANG_CHARSET") && (strlen(LANG_CHARSET) > 0)) {
			$logicalEncoding = LANG_CHARSET;
		} elseif (defined("BX_DEFAULT_CHARSET")) {
			$logicalEncoding = BX_DEFAULT_CHARSET;
		} else {
			$logicalEncoding = "windows-1251";
		}

		return strtolower($logicalEncoding);
	}

	/**
	 * Обертка для изменения кодировки через битрикс-функцию т.к. нам нужен объект класса
	 * @param mixed $val
	 * @param array $arEnc
	 * @param bool $return
	 * @return NULL|string
	 * @internal param string $key
	 */
	private static function ConvertCharset(&$val, $arEnc, $return = false)
	{
		global $APPLICATION;
		$val = $APPLICATION->ConvertCharset($val, $arEnc['from'], $arEnc['to']);
		if ($return) {
			return $val;
		}
		return true;
	}

	/**
	 * Прокси для array_walk_recursive в self::encodeArray
	 * @param $val
	 * @param $key
	 * @param $arEnc
	 * @return NULL|string
	 */
	private static function __ConvertCharset(&$val, $key, $arEnc)
	{
		return self::ConvertCharset($val, $arEnc);
	}

	/**
	 * Рекурсивно перекодирует массив вместе с ключами
	 * @param $array
	 * @param $encFrom
	 * @param $encTo
	 * @return array
	 */
	public static function getEncodeArrayWithKeys($array, $encFrom, $encTo)
	{
		$resultArray = array();
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				$key = self::ConvertCharset($key, array('from' => $encFrom, 'to' => $encTo), true);
				if (is_array($value)) {
					$resultArray[$key] = self::getEncodeArrayWithKeys($value, $encFrom, $encTo);
				} else {
					$resultArray[$key] = self::ConvertCharset($value, array('from' => $encFrom, 'to' => $encTo), true);
				}
			}
		}
		return $resultArray;
	}

	/**
	 * Перекодирует рекурсивно значения массива
	 * PHP >= 5.3.0
	 * @param $array
	 * @param $encFrom
	 * @param $encTo
	 * @return bool
	 */
	static public function encodeArray(&$array, $encFrom, $encTo)
	{
		if (!is_array($array)) return false;
		array_walk_recursive($array, __CLASS__ . '::__ConvertCharset', array('from' => $encFrom, 'to' => $encTo));
		return true;
	}

	/**
	 * Обертка проверяет нужно ли перекодирование, если да то делает его
	 * @param $arRequest
	 * @return bool
	 */
	public static function encodeAjaxRequest(&$arRequest)
	{
		// ajax всегда прилетает в UTF-8, если кодировка сайта отличается, надо перекодировать
		if (($curEnc = self::getLogicalEncoding()) == 'utf-8') return true;
		self::encodeArray($arRequest, 'utf-8', $curEnc);
		return true;
	}

	/**
	 * Сериализует массив параметров, убирая из него ~ значения
	 * @param $arParams
	 * @return string
	 */
	static public function GetEncodedArParams($arParams)
	{
		if (!is_array($arParams)) return false;
		$arBoolFix = array(
			'DISPLAY_COMPARE' => 1,
			'USE_PRICE_COUNT' => 1,
			'SHOW_PRICE_COUNT' => 1,
			'PRICE_VAT_INCLUDE' => 1,
			'DISPLAY_TOP_PAGER' => 1,
			'DISPLAY_BOTTOM_PAGER' => 1,
			'PAGER_SHOW_ALWAYS' => 1,
			'PAGER_DESC_NUMBERING' => 1,
			'PAGER_SHOW_ALL' => 1,
			'SHOW_ALL_WO_SECTION' => 1,
			'ADD_SECTIONS_CHAIN' => 1,
			'SET_TITLE' => 1,
			'CACHE_FILTER' => 1,
		);
		$isNeedEnc = (($curEnc = self::getLogicalEncoding()) != 'utf-8');

		$newArParams = array();
		foreach ($arParams as $key => $val) {
			// убираем экранированные параметры (они нам не нужны)
			if ($key{0} != "~") {
				// если кодировка сайта не utf - перекодируем значения в utf
				if (!is_object($val)) {
					// фикс новой приколюхи битрикса с подменой параметров после выполнения компонента
					if (isset($arBoolFix[$key])) {
						$val = $arParams['~' . $key];
					}
					$newArParams[$key] = $val;
				}
			}
		}
		if ($isNeedEnc) {
			self::encodeArray($newArParams, $curEnc, 'utf-8');
		}
		return base64_encode(json_encode($newArParams));
	}

	/**
	 * Распаковываем массив из строки и перекодируем если нужно
	 * @param $string
	 * @return array|mixed
	 */
	static public function GetDecodedArParams($string)
	{
		$isNeedEnc = (($curEnc = self::getLogicalEncoding()) != 'utf-8');
		$arParams = json_decode(base64_decode($string), true);
		if (!is_array($arParams)) return array();
		if ($isNeedEnc) {
			self::encodeArray($arParams, 'utf-8', $curEnc);
		}
		return $arParams;
	}

	/**
	 * Получаем перекодированый в UTF-8 массив
	 * для обработки на стороне клиента
	 * @param array $array
	 * @return array
	 */
	public static function GetArrayResponse($array)
	{
		if (!is_array($array)) return array();
		$isNeedEnc = (($curEnc = self::getLogicalEncoding()) != 'utf-8');
		if ($isNeedEnc) {
			self::encodeArray($array, $curEnc, 'utf-8');
		}
		return $array;
	}

	/**
	 * Ищет в строке $string подстроки типа #*#
	 * проверяет есть ли такие константы, и если есть поставляет их значения
	 * Если такой константы нет - вырезает
	 * напр.:
	 * $string = "site_#SITE_ID#/about/#NON_EXISTING_CONSTANT#"
	 * вернет == "site_s1/about/"
	 * @param string $string
	 * @return string mixed
	 */
	public static function GetConstantUrl($string)
	{
		$arResult = array();
		$matches = array();
		preg_match_all("/#(.*?)#/", $string, $matches);
		foreach ($matches[1] as $key => $name) {
			if (@defined($name)) {
				$arResult[$matches[0][$key]] = constant($name);
			} else {
				$arResult[$matches[0][$key]] = '';
			}
		}
		$sResult = str_replace(array_keys($arResult), array_values($arResult), $string);
		$sResult = str_replace('//', '/', $sResult);
		return htmlspecialcharsbx($sResult);
	}

	static private $_isViewActive = false;
	static private $_arViewTargets = array();

	/**
	 * Обертка для ShowViewContent для вывода нужного нам view'а
	 * Использует main событие OnEpilog
	 * ВНИМАНИЕ! внутри view нельзя работать с буфером! т.к. в этот момент
	 * ма находимся уже в конце вывода
	 * @param $view
	 */
	static public function showViewContent($view)
	{
		if (preg_match('~^[a-zA-Z0-9\_\-\.]{1,40}$~', $view)) {
			if (is_dir($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/_view_content')) {
				$contentFile = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/_view_content/' . $view . '.php';
				$contentLangFile = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/lang/' . LANGUAGE_ID . '/_view_content/' . $view . '.php';
			} else {
				$contentFile = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/_view_content.' . $view . '.php';
				$contentLangFile = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . 'lang/' . LANGUAGE_ID . '/_view_content.' . $view . '.php';
			}

			if (file_exists($contentFile)) {
				global $APPLICATION;
				$APPLICATION->ShowViewContent($view);
				self::$_arViewTargets[$view] = array(
					'CONTENT_FILE' => $contentFile,
					'CONTENT_LANG_FILE' => $contentLangFile,
				);
				if (!self::$_isViewActive) {
					AddEventHandler('main', 'OnEpilog', __CLASS__ . '::fireContentTarget');
					//AddEventHandler('main', 'OnEpilogCustom', __CLASS__ . '::fireContentTarget');
					self::$_isViewActive = true;
				}
			}
		}

	}

	/**
	 * Собирает view'ы поочередке в переменную и делает AddViewContent
	 */
	static public function fireContentTarget()
	{
		global /** @noinspection PhpUnusedLocalVariableInspection */
		$APPLICATION, $USER, $DB;
		foreach (self::$_arViewTargets as $view => &$arViewTarget) {
			ob_start();
			Loc::loadMessages($arViewTarget['CONTENT_LANG_FILE']);
			/** @noinspection PhpIncludeInspection */
			include $arViewTarget['CONTENT_FILE'];
			$content = ob_get_clean();
			$APPLICATION->AddViewContent($view, $content);
			unset($content);
		}
		unset($arViewTarget);
	}

	/**
	 * Отдает наименование единицы в форме соотвествующей числу
	 * @param integer $quantity число
	 * @param string $nominative именительный подеж ед. число (час)
	 * @param string $genetive родительный подеж ед. число  (часа)
	 * @param string $genplural родительный подеж множ. число (часов)
	 * @return string
	 */
	static public function rusQuantity($quantity, $nominative, $genetive = null, $genplural = null)
	{
		$oneState = false;
		if ($genetive == null || $genplural == null) {
			$oneState = true;
		}
		$quantity = abs(intval($quantity));
		switch ($quantity % 100) {
			case 11:
			case 12:
			case 13:
			case 14:
				return ($oneState) ? $nominative . GetMessage('YNS_TOOLS_SOME_GM') : $genplural;
				break;
			default:
				switch ($quantity % 10) {
					case 1:
						return $nominative;
						break;
					case 2:
					case 3:
					case 4:
						return ($oneState) ? $nominative . GetMessage('YNS_TOOLS_SOME_G') : $genetive;
						break;
					default:
						return ($oneState) ? $nominative . GetMessage('YNS_TOOLS_SOME_GM') : $genplural;
				}
		}
	}

	/**
	 * Returns russian name of given filesize in bytes
	 * @param integer $bytes - filesize in bytes
	 * @param integer $decimals - preferred number of digits after point
	 * @return string
	 */
	static public function rusFilesize($bytes, $decimals = 2)
	{
		$arSize = array(
			GetMessage('RZ_BYTE'),
			GetMessage('RZ_KILOBYTE'),
			GetMessage('RZ_MEGABYTE'),
			GetMessage('RZ_GIGABYTE'),
			GetMessage('RZ_TERABYTE'),
		);
		$factor = floor((strlen($bytes) - 1) / 3);

		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $arSize[$factor];
	}

	static private $_moduleId = 0;

	/**
	 * Пытается найти и подключить главный Lang файл для модуля
	 * который должен находится в BX_ROOT . '/modules/' . $moduleId . '/index.php'
	 * @param string|bool $moduleId
	 * @return array
	 */
	public static function IncludeMainLang($moduleId = false)
	{
		if (!$moduleId) {
			if (self::$_moduleId !== 0) {
				$moduleId = self::$_moduleId;
			} else {
				$arDecClasses = get_declared_classes();
				foreach ($arDecClasses as &$className) {
					if (preg_match("/CRZ.*Settings/", $className)) {
						if (method_exists($className, 'getModuleId')) {
							self::$_moduleId = $className::getModuleId();
						}
						break;
					}
					unset ($className);
				}
				if (self::$_moduleId !== 0) {
					$moduleId = self::$_moduleId;
				}
			}
		}
		if (!$moduleId) {
			$arFiles = glob(__DIR__ . '/CRZ*Settings.php');
			if (isset($arFiles[0])) {
				$className = basename($arFiles[0], '.php');
				if (method_exists($className, 'getModuleId')) {
					self::$_moduleId = $className::getModuleId();
				}
			}
			if (self::$_moduleId !== 0) {
				$moduleId = self::$_moduleId;
			}
		}
		Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/' . $moduleId . '/index.php');
	}

	/**
	 * Format price for RUB font via number_format function
	 * use when catalog module not installed
	 * @param $num - цена
	 * @param bool $currency - валюта
	 * @param int $precision - точность
	 * @return string
	 */
	public static function FormatPrice($num, $currency = false, $precision = 0, $nbsp = false)
	{
		if (!$currency) {
			$rubStr = GetMessage('YNS_TOOLS_RUB_SYMBOL');
			if (!isset($rubStr)) {
				self::IncludeMainLang();
				$rubStr = GetMessage('YNS_RUB_SYMBOL');
			}
			$currency = '<span class="b-rub">' . $rubStr . '</span>';
		}
		if ($nbsp === true) {
			$nbsp = '&nbsp;';
		}
		if ($nbsp) {
			$currency = $nbsp . $currency;
		}
		return number_format($num, $precision, '.', ' ') . $currency;
	}

	/**
	 * Получает из сесии переменную и возвращает ее как JS объект
	 * @param $key
	 * @return array
	 */
	public static function getSessionParamJS($key)
	{
		$jsResult = '';
		if (empty($_SESSION[$key])) {
			return $jsResult;
		} else {
			return \CUtil::PhpToJSObject($_SESSION[$key]);
		}
	}

	/**
	 * Возвращает номер версии модуля из его /install/version.php
	 * либо false в случае отсутствия такой информации
	 * @param string $moduleName
	 * @return string|bool
	 */
	public static function getModuleVersion($moduleName = '')
	{
		if (strlen($moduleName) == 0) {
			$moduleName = 'yenisite.core';
		}
		$versionPath = $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/' . $moduleName . '/install/version.php';
		if (file_exists($versionPath)) {
			$arModuleVersion = array();
			/** @noinspection PhpIncludeInspection */
			include($versionPath);
			return (strlen($arModuleVersion['VERSION']) > 0)
				? $arModuleVersion['VERSION']
				: false;
		} else {
			return false;
		}
	}

	/**
	 * Проверяет существует ли компонент $cmpName
	 * обертка над функциями FW Bitrix
	 * @param $cmpName
	 * @return bool
	 */
	public static function isComponentExist($cmpName)
	{
		return \CComponentUtil::isComponent(getLocalPath("components" . \CComponentEngine::MakeComponentPath($cmpName)));
	}


	/**
	 * get path for include area
	 * @param string | array | \CBitrixComponentTemplate $component
	 * @param string | array $arParams
	 * @param array $arSettings
	 * @return string
	 */
	protected static function getIncludeAreaPath($component, $arParams, &$arSettings = array())
	{
		$folder = '';
		$name = '';
		if ($component instanceof \CBitrixComponentTemplate) {
			$folder = explode(':', $component->__component->__name);
			$folder = str_replace('.', '_', $folder[1]);
			$name = $component->__name;
			if (is_string($arParams) && !isset($arSettings['ID'])) {
				$arSettings['ID'] = $arParams;
			}
		} elseif (is_array($component)) {
			$folder = $component[0];
			if (strpos($folder, ':') !== false) {
				$folder = explode(':', $folder);
				$folder = str_replace('.', '_', $folder[1]);
			}
			$name = $component[1];
			if (is_string($arParams) && !isset($arSettings['ID'])) {
				$arSettings['ID'] = $arParams;
			}
		} elseif (is_string($component)) {
			$folder = $component;
			$name = '';
		}
		if (!empty($name)) {
			$name .= '-';
		} else {
			if (is_string($arParams)) {
				$name = $arParams;
			}
		}
		$settingsID = $arSettings['ID'] ?: '';
		return SITE_DIR . 'include_areas/' . $folder . '/' . $name . $settingsID . '.php';
	}

	public static function IncludeAreaWithHideParam($folder, $file,$show = true){
	    if (!$show) return;
	    self::IncludeArea($folder,$file);
    }
	/**
	 * Shortcut for \CMain::IncludeComponent('bitrix:main.include')
	 * @example "Tools::IncludeArea($this, 'phone')" in component template.
	 * @example "Tools::IncludeArea('main', 'phone')" simple by name.
	 * @example "Tools::IncludeArea(array('bitrix:catalog.section','template_name'), 'phone')" template emulation.
	 * @param string | array | \CBitrixComponentTemplate $component
	 * @param string | array $arParams
	 * @param array $arSettings
	 * @param bool $hideIcons
	 * @param string $active - 'Y' or 'N'
	 * @param array $arOtherParams other params, support 'TITLE' (title for edit mode)
	 */
	public static function IncludeArea(
		$component,
		$arParams,
		$arSettings = array(),
		$hideIcons = true,
		$active = 'Y',
		$arOtherParams = array(),
        $empty = false
	) {
		global $APPLICATION;
		if (!is_array($arParams)) {
            $PATH = self::getIncludeAreaPath($component, $arParams, $arSettings);
        } else{
            $PATH = self::getIncludeAreaPath($component, $arParams['FILE_NAME'], $arSettings);
        }

		if (is_array($arSettings)) {
			if (!isset($arSettings['TEMPLATE'])) {
				$arSettings['TEMPLATE'] = '.default';
			}
			if (!isset($arSettings['ID'])) {
				$arSettings['ID'] = '';
			}
		} else {
			if ($arSettings === true && is_string($arParams)) {
				$arSettings = array();
				$arSettings['TEMPLATE'] = $arParams;
			}
		}
		$arDefaultParams = array(
			"AREA_FILE_SHOW" => "file",
			"EDIT_TEMPLATE" => "include_areas_template.php",
			"PATH" => $PATH,
		);
		if (is_array($arParams)) {
			$arTargetParams = array_merge($arDefaultParams, $arParams);
		} else {
			$arTargetParams = $arDefaultParams;
		}
		$parent = false;
		$arFuncParams = array("ACTIVE_COMPONENT" => $active);
		if ($hideIcons) {
			$arFuncParams["HIDE_ICONS"] = "Y";
		} else {
			if ($component instanceof \CBitrixComponentTemplate) {
				$parent = $component->__component;
			} else {
				$parent = new \CBitrixComponent;
			}
		}

		$bChangeTitle = !empty($arOtherParams['TITLE']) && Tools::isEditModeOn();
		if ($bChangeTitle) {
			$tempMess = GetMessage('main_comp_include_edit');
			if (empty($tempMess)) {
				Loc::loadLanguageFile($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/components/bitrix/main.include/component.php');
				$tempMess = GetMessage('main_comp_include_edit');
			}
			global $MESS;
			$MESS['main_comp_include_edit'] = $arOtherParams['TITLE'];
		}
		$APPLICATION->IncludeComponent('bitrix:main.include', $arSettings['TEMPLATE'], $arTargetParams, $parent, $arFuncParams);
		if ($bChangeTitle) {
			$MESS['main_comp_include_edit'] = $tempMess;
		}
	}


	/**
	 * Shortcut for self::IncludeArea() with predefined $hideIcons = false
	 * @param string | array | \CBitrixComponentTemplate $component
	 * @param string | array $arParams
	 * @param array $arOtherParams other params, support 'TITLE' (title for edit mode)
	 */
	public static function IncludeAreaEdit($component, $arParams, $arOtherParams = array())
	{
		self::IncludeArea($component, $arParams, array(), false, 'Y', $arOtherParams);
	}

    /**
     * INCLUDE AREA IF WE DOESNT HAS FILE JUST CREATE IT
     */
	/**
	 * @param string | array | \CBitrixComponentTemplate $component
	 * @param string | array $arParams
	 * @param array $arSettings
	 * @return bool
	 */

	public static function createIncludeArea($folder,$file,$fileContent,$includeAreaEdit = true,$messForArea,$arParamsForArea = array(),$activeInclArea = false){
        global $APPLICATION;
        $arSettings['ID'] = $file;
        $PATH = self::getIncludeAreaPath($folder, '', $arSettings);

        $content = $APPLICATION->GetFileContent($_SERVER['DOCUMENT_ROOT'] . $PATH);
        if ($content === false) {
            $io = \CBXVirtualIo::GetInstance();
            if (!$io->DirectoryExists($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include_areas/'.$folder)){
                $io->CreateDirectory($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include_areas/'.$folder);
            }
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include_areas/' . $folder . '/' . $file . '.php')) {
                $newFile = fopen($_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include_areas/' . $folder . '/' . $file . '.php', 'w');
                if ($newFile !== false) {
                    fwrite($newFile, $fileContent);
                }
            }
        }

        if ($includeAreaEdit){
            self::IncludeAreaEdit($folder,$file, array('TITLE' => $messForArea));
        } else{
            if (empty($arParamsForArea)){
                self::IncludeArea($folder,$file);
            }else{
                self::IncludeArea($folder,$arParamsForArea,array(),$activeInclArea,'Y', array('TITLE' => $messForArea));
            }
        }
    }

	public static function isIncludeAreaNotEmpty($component, $arParams, $arSettings = array())
	{
		global $APPLICATION;
		$PATH = self::getIncludeAreaPath($component, $arParams, $arSettings);
		$content = $APPLICATION->GetFileContent($_SERVER['DOCUMENT_ROOT'] . $PATH);
		if ($content === false) {
			return false;
		} else {
			$content = trim($content);
			return strlen($content) > 0;
		}
	}

	public static function includePostfixArea($postfix, $path, $arParams = array(), $template = null, $hasComponent = false)
	{
		$hideIcons = false;
		$pi = pathinfo($path);
		$fileName = $pi['basename'];
		$dpi = pathinfo($pi['dirname']);
		$dirName = $dpi['basename'];
		unset($dpi);

		if (strlen($postfix) > 0) {
			//$hideIcons = false;
			$path = $pi['dirname'] . '/' . $pi['filename'] . '_' . $postfix . '.' . $pi['extension'];
		}
		$arTargetParams = array(
			'PATH' => $path,
		);
		if ($hasComponent && file_exists(rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . $path)) {
			$hideIcons = true;
		}
		if (is_array($arParams)) {
			$arTargetParams = array_merge($arTargetParams, $arParams);
		} else {
			if ($arParams === true) {
				$arTargetParams['EDIT_TEMPLATE'] = $dirName . '_' . $fileName;
			}
		}
		$arSettings = array();
		if (isset($template) && is_string($template)) {
			$arSettings['TEMPLATE'] = $template;
		}
		self::IncludeArea('', $arTargetParams, $arSettings, $hideIcons);
	}

	public static function isEditModeOn()
	{
		return $_GET['bitrix_include_areas'] == 'Y' || !empty($_SESSION['SESS_INCLUDE_AREAS']);
	}

	/**
	 * Get $arComponentParameters from component .parameters.php file
	 * to use as a reference for parameters in template.
	 *
	 * @param string $componentPath
	 * component folder, there is variable in template .parameters.php file with the same name
	 *
	 * @return array - fullfilled $arComponentParameters
	 */
	public static function getComponentParameters($componentPath)
	{
		$arComponentParameters = array();
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		\CComponentUtil::__IncludeLang($componentPath, ".parameters.php");
		include $_SERVER["DOCUMENT_ROOT"] . $componentPath . "/.parameters.php";

		return $arComponentParameters;
	}
    /**
     * Get component params wich store in public files
     */

    public static function getPararmsOfCMP($pathToCMP = '', $non_name = false){
        $arResult = array();
        global $APPLICATION;
        $fileSrc = $APPLICATION->GetFileContent($pathToCMP);
        $arComponents = \PHPParser::ParseScript($fileSrc);
        foreach ($arComponents as $arComponent){
            $arComponent['DATA']['PARAMS']['COMPONENT_NAME_AJAX'] = $arComponent['DATA']['COMPONENT_NAME'];
            $arComponent['DATA']['PARAMS']['COMPONENT_TEMPLATE_AJAX'] = $arComponent['DATA']['TEMPLATE_NAME'];
            if ($non_name){
                $arResult = $arComponent['DATA']['PARAMS'];
            }else {
                $nameComponent = str_replace(array(':', '.'), '_', $arComponent['DATA']['COMPONENT_NAME']);
                $arResult[$nameComponent] = $arComponent['DATA']['PARAMS'];
            }
        }
        return $arResult;
    }

    public static function findNeedDir($string_to_search = 'yenisite.bitronic2', $path = ''){
        $dir = opendir($path);
        $foundDir = '';

        while(($file = readdir($dir)) !== false)
        {
            if(strstr($file,$string_to_search) !== false){
               $foundDir = $file;
                break;
            }
        }

        return $foundDir;
    }

    public static function parsGetParams ($uri, $setInGet = false){
        $result = parse_url($uri);
        $allQueries = str_replace('?','',$result);
        $arValNameQueries = explode('&',$allQueries['query']);
        $valNames = array();

        if (!empty($arValNameQueries)){
            foreach ($arValNameQueries as $arValName){
                $valNames['ARR_GETS'][] = explode('=',$arValName);
            }
        } else{
            $valNames = explode('=',$allQueries['query']);
        }
        if ($setInGet){
            self::setParamsInGet($valNames);
        } else{
            return $valNames;
        }

    }

    public static function setParamsInGet ($arNameParams){
        if (!empty($arNameParams['ARR_GETS'])){
            foreach ($arNameParams['ARR_GETS'] as $combo){
                $_GET[$combo[0]] = $combo[1];
                $_REQUEST[$combo[0]] = $combo[1];
            }
        } else{
            $_GET[$arNameParams[0]] = $arNameParams[1];
            $_REQUEST[$arNameParams[0]] = $arNameParams[1];
        }
    }

    public static function unsetServiceDataRequest(){
        unset($_REQUEST['INCLUDE_WITH_CMP'],$_REQUEST['PATH_TO_CMP'],$_REQUEST['GET_PARAMS_OF_COMPONENT'], $_REQUEST['PARAMS']);
        unset($_GET['INCLUDE_WITH_CMP'],$_GET['PATH_TO_CMP'],$_GET['GET_PARAMS_OF_COMPONENT'],$_GET['PARAMS']);
        unset($_POST['INCLUDE_WITH_CMP'],$_POST['PATH_TO_CMP'],$_POST['GET_PARAMS_OF_COMPONENT'],$_POST['PARAMS']);
    }

    public static function getOnlyFolderOFInclArea ($str){
        $str = str_replace('include_areas/','',$str);
        $str = preg_replace ("/[a-z_][a-z_0-9]*.php/",'',$str);
        return  substr($str, 0,strlen($str) - 1);
    }
    public static function getOnlyFileOFInclArea ($str){
         $matches = '';
         preg_match ("/[a-z_][a-z_0-9]*.php/",$str,$matches);
         return str_replace('.php','',$matches[0]);
    }
    static public function getLogicalArEncoding($Str)
    {
        if (is_array($Str)) {
            foreach ($Str as $arStr) {
                $strEncoding = self::getLogicalArEncoding($arStr);
                if (!is_array($strEncoding)) return $strEncoding;
            }
        }
        $strEncoding = $Str;
        return mb_detect_encoding($strEncoding);
    }

    public static function saveSomeValuesInCache($arValues, $cache_id, $setManager = false, $IBLOCK_ID = 0)
    {
        $obCache = new \CPHPCache;
        $cacheDir = self::$_cacheDir . $cache_id . '/';
        $arReturn = array();

        $obCache->CleanDir($cacheDir);

        if ($obCache->StartDataCache(self::$_cacheTime, $cache_id, $cacheDir)) {

            $arReturn = $arValues;

            if ($setManager) {
                if (defined("BX_COMP_MANAGED_CACHE")) {
                    global $CACHE_MANAGER;
                    if (is_array($IBLOCK_ID)){
                        foreach ($IBLOCK_ID as $idIblock){
                            $CACHE_MANAGER->StartTagCache($cacheDir);
                            $CACHE_MANAGER->RegisterTag("iblock_id_" . $idIblock);
                            $CACHE_MANAGER->EndTagCache();
                        }
                    } else {
                        $CACHE_MANAGER->StartTagCache($cacheDir);
                        $CACHE_MANAGER->RegisterTag("iblock_id_" . $IBLOCK_ID);
                        $CACHE_MANAGER->EndTagCache();
                    }
                }
            }

            $obCache->EndDataCache($arReturn);
        }

        unset($obCache);

        return $arReturn;
    }

    public static function getSavedValues($cache_id)
    {
        $obCache = new \Yenisite\Core\Cache();
        $cacheDir = self::$_cacheDir . $cache_id . '/';
        $arReturn = array();

        if ($obCache->InitCache(self::$_cacheTime, $cache_id, $cacheDir,'cache')) {
            $arReturn = $obCache->GetVars();
        }

        return $arReturn;
    }

    //GET LIST OF CITES
    public static function getListSites(){
        $rsSites = \CSite::GetList($by="sort", $order="desc");
        $arReturn = array();

        while ($arSite = $rsSites->Fetch())
        {
            $arReturn[$arSite['ID']] = $arSite;
        }

        return $arReturn;
    }

    //GET LIST OF TEMPLATES FOR ALL CITES

    public static function getTemplatesOfSites ($arSites){
        if (empty($arSites)) return array();

        $arReturn = array();
        foreach ($arSites as $arSite){
            $rsTemplates = \CSite::GetTemplateList($arSite['ID']);
            while($arTemplate = $rsTemplates->Fetch())
            {
                $arReturn[$arSite['ID']]['TEMPLATES'][$arTemplate['TEMPLATE']] = $arTemplate;
            }
        }

        return $arReturn;
    }


    public static function getSectionPictureById($itemId, $resizer_set = false, $returnImgId = false)
    {
        if(!\CModule::IncludeModule("iblock") || intval($itemId) <=0 )
            return false;

        $obCache = new \CPHPCache;
        $cache_id = 'SECTION_'.$itemId.'_PICT';
        if(intval($resizer_set) > 0 && \CModule::IncludeModule("yenisite.resizer2"))
        {
            $bResizer = true;
            // $cache_id .= "_RS".$resizer_set;
        }

        if($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir))
        {
            $vars = $obCache->GetVars();
            $arReturn = $vars;
        }
        elseif($obCache->StartDataCache())
        {
            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $dbSection = \CIBlockSection::GetByID($itemId);
            if($arSection = $dbSection->GetNext())
            {
                // get image :
                $image = self::GetFieldPicSrc($arSection['PICTURE']);

                $arReturn = array(
                    'PRODUCT_PICTURE_ID' => $image,
                    'PRODUCT_PICTURE_SRC' => \CFile::GetPath($image),
                );

                if(defined("BX_COMP_MANAGED_CACHE"))
                {
                    $CACHE_MANAGER->RegisterTag("iblock_id_".$arSection['IBLOCK_ID']);
                    $CACHE_MANAGER->EndTagCache();
                }
            }
            else
            {
                $arReturn['PRODUCT_PICTURE_ID'] = 0;
                $arReturn['PRODUCT_PICTURE_SRC'] = '';
            }

            $obCache->EndDataCache($arReturn);
        }
        unset($obCache);

        if($returnImgId)
        {
            return $arReturn['PRODUCT_PICTURE_ID'];
        }
        else
        {
            if($bResizer)
                $arReturn['PRODUCT_PICTURE_SRC'] = \CResizer2Resize::ResizeGD2($arReturn['PRODUCT_PICTURE_SRC'], intval($resizer_set));

            return $arReturn['PRODUCT_PICTURE_SRC'] ;
        }
    }

    private function GetFieldPicSrc($arElementPicField)
    {

        if(is_array($arElementPicField))
        {
            return $arElementPicField['ID'] ;
        }
        elseif(intval($arElementPicField) > 0)
        {
            return intval($arElementPicField);
        }

        return false;
    }

    public static function getElementPictureById($itemId, $resizer_set = false, $findInParent = true, $returnImgId = false)
    {
        if(!\CModule::IncludeModule("iblock")) {
            return false;
        }
        if (intval($itemId) <=0) {
            $sectionId = intval(ltrim($itemId, 'Ss'));
            if ($sectionId > 0) {
                return self::getSectionPictureById($sectionId, $resizer_set, $returnImgId);
            }
            return false;
        }

        $obCache = new \CPHPCache;
        $cache_id = 'ELEM_'.$itemId.'_PICT';
        if(intval($resizer_set) > 0 && \CModule::IncludeModule("yenisite.resizer2"))
        {
            $bResizer = true;
            // $cache_id .= "_RS".$resizer_set;
        }

        if($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir))
        {
            $vars = $obCache->GetVars();
            $arReturn = $vars;
        }
        elseif($obCache->StartDataCache())
        {
            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }

            $dbElement = \CIBlockElement::GetByID($itemId);
            if($arElement = $dbElement->GetNext())
            {
                // get image :
                $image = self::getElementPicture($arElement);
                if(intval($image) <= 0 && \Bitrix\Main\Loader::includeModule('catalog'))
                {
                    $arOffers = \CIBlockPriceTools::GetOffersArray($arElement['IBLOCK_ID'], $arElement['ID'], array('sort' => 'asc'), array('ID'), array(), 0, array(), false, array());

                    if(!empty($arOffers))
                    {
                        foreach($arOffers as $arOffer)
                        {
                            $image = self::getElementPictureById($arOffer['ID'], false, false, true);
                            if(intval($image) > 0)
                            {
                                break;
                            }
                        }
                        unset($arOffers);
                    }
                    elseif($findInParent)
                    {
                        $arElement = \CCatalogSKU::GetProductInfo($arElement['ID']);
                        if(is_array($arElement) && intval($arElement['ID'] > 0))
                        {
                            $image = self::getElementPictureById($arElement['ID'], false, true, true);
                        }
                    }
                }

                if ($image != false){
                    $arReturn = array(
                        'PRODUCT_PICTURE_ID' => $image,
                        'PRODUCT_PICTURE_SRC' => \CFile::GetPath($image),
                    );
                } else {
                    $arReturn = array();
                }


                if(defined("BX_COMP_MANAGED_CACHE"))
                {
                    $CACHE_MANAGER->RegisterTag("iblock_id_".$arElement['IBLOCK_ID']);
                    $CACHE_MANAGER->EndTagCache();
                }
            }
            else
            {
                $arReturn['PRODUCT_PICTURE_ID'] = 0;
                $arReturn['PRODUCT_PICTURE_SRC'] = '';
            }

            $obCache->EndDataCache($arReturn);
        }
        unset($obCache);

        if($returnImgId)
        {
            if (!empty($arReturn['PRODUCT_PICTURE_ID']))
                return $arReturn['PRODUCT_PICTURE_ID'];
            else
                return $arReturn;
        }
        else
        {
            if($bResizer)
                $arReturn['PRODUCT_PICTURE_SRC'] = \CResizer2Resize::ResizeGD2($arReturn['PRODUCT_PICTURE_SRC'], intval($resizer_set));

            return $arReturn['PRODUCT_PICTURE_SRC'] ;
        }
    }

    public static function getElementPicture($arElement, $arParamsImage = 'DETAIL_PICTURE', $default_image_code = 'MORE_PHOTO')
    {
        if(!is_array($arElement))
            return false;

        $picsrc = false; $find_in_prop = false;
        if($arParamsImage != 'PREVIEW_PICTURE' && $arParamsImage != 'DETAIL_PICTURE')
        {
            $find_in_prop = true ;
            if(!$picsrc = self::GetPropPicSrc($arElement, $arParamsImage))
            {
                $picsrc = self::GetPropPicSrc($arElement, $default_image_code) ;
            }
        }

        if($arParamsImage == 'DETAIL_PICTURE' || $arParamsImage == 'PREVIEW_PICTURE')
        {
            $picsrc = self::GetFieldPicSrc ($arElement[$arParamsImage]) ;
        }

        if(!$picsrc)
        {
            if(!$find_in_prop)
                $picsrc = self::GetPropPicSrc($arElement, $default_image_code) ;

            if(!$picsrc && $arParamsImage != 'DETAIL_PICTURE')
                $picsrc = self::GetFieldPicSrc($arElement['DETAIL_PICTURE']) ;

            if(!$picsrc && $arParamsImage != 'PREVIEW_PICTURE')
                $picsrc = self::GetFieldPicSrc($arElement['PREVIEW_PICTURE']) ;
        }
        return $picsrc ;
    }

    private static function GetPropPicSrc($arElement, $prop_code, $getCount = false)
    {
        if(!is_array($arElement) || !$prop_code)
            return false;
        if(!\CModule::IncludeModule("iblock"))
            return false;
        $arPropFile = false ;

        if(is_array($arElement['PROPERTIES'][$prop_code]))
        {
            $arPropFile = $arElement['PROPERTIES'][$prop_code]['VALUE'] ;
        }
        else
        {
            if(!empty($arElement['PRODUCT_ID']))
                $arElement['ID'] = $arElement['PRODUCT_ID'];

            if(empty($arElement['IBLOCK_ID']))
            {
                $arElement['IBLOCK_ID'] = self::getElementIblockId($arElement['ID']);
            }

            $dbProp = \CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], array("ID" => "ASC", "VALUE_ID" => "ASC"), Array("CODE" => $prop_code));
            if($arProp = $dbProp->Fetch())
            {
                $arPropFile = $arProp['VALUE'] ;
            }
        }
        if($arPropFile)
        {
            if($getCount)
                return count($arPropFile);
            if(is_array($arPropFile))
            {
                return $arPropFile[0] ;
            }
            elseif(intval($arPropFile) > 0)
            {
                return $arPropFile ;
            }


            /* if(intval($pic_id) > 0)
            {
                return CFile::GetPath(intval($pic_id)) ;
            } */
        }
        return false;
    }

    public static function getElementIblockId($element_id)
    {
        if(intval($element_id) <= 0)
            return false;


        $obCache = new \CPHPCache();
        if ($obCache->InitCache(self::$_cacheTime, "IBID_{$element_id}", self::$_cacheDir))
        {
            $arElement = $obCache->GetVars();
        }
        elseif ($obCache->StartDataCache())
        {
            $res = \CIBlockElement::GetByID($element_id);
            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache(self::$_cacheDir);
            }
            if($ar_res = $res->GetNext())
            {
                $arElement = $ar_res;
                $CACHE_MANAGER->RegisterTag("iblock_id_".$arElement['IBLOCK_ID']);
                $CACHE_MANAGER->EndTagCache();
            }
            $obCache->EndDataCache($arElement);
        }

        return $arElement['IBLOCK_ID'];
    }
}