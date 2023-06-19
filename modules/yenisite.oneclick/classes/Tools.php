<?php
namespace CRZ\OneClick {
	IncludeModuleLangFile(__FILE__);
	class Tools {
		static private $_arJsFiles = array();
		static private $_JsFileCounter = 0;

		/**
		 * ��������� � ���� ���� � JS
		 * @param string $jsFilePath
		 * @return bool
		 */
		static public function addDeferredJS($jsFilePath) {
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
			return false;
		}

		/**
		 * ������� ���������� JS �����
		 * @return string
		 */
		static public function getDeferredJS() {
			$returnString = '';
			foreach (self::$_arJsFiles as $jsFilePath) {
				$returnString .= '<script type="text/javascript" src="' . $jsFilePath . '"></script>' . "\n";
			}
			return $returnString;
		}

		/**
		 * �������� ������ JS ������
		 * @return array
		 */
		static public function getDeferredJSFilesList() {
			return self::$_arJsFiles;
		}

		/**
		 * ��������� ��� getDeferredJS
		 */
		static public function showDeferredJS() {
			/** @var \CMain $APPLICATION */
			global $APPLICATION;
			$APPLICATION->AddBufferContent('CRZTools::getDeferredJS');
		}

		/**
		 * ���� � ��������� JS � ������ ���� ��� �� ��������� �� ����������
		 * @static
		 * @param \CBitrixComponent|string $component
		 * @param null $jsFilePath
		 * @return bool
		 */
		static public function addComponentDeferredJS($component, $jsFilePath = null) {
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
		 * ���������� ������������ �� AJAX � ������ ������
		 * @return bool
		 */
		static public function isAjax() {
			if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
				return true;
			}
			/*if (isset($_REQUEST['ajax']) && strtoupper($_REQUEST['ajax']) == "Y") {
				return true;
			}*/
			return false;
		}

		/**
		 * ����� ������� �������� ��� ����������� ���������� ��������� �����
		 * ����� ������ ��� ������� �������� ������� $this � ��� ����� �������
		 * @return string
		 */
		static public function getLogicalEncoding() {
			if (defined('BX_UTF'))
				$logicalEncoding = "utf-8";
			elseif (defined("SITE_CHARSET") && (strlen(SITE_CHARSET) > 0))
				$logicalEncoding = SITE_CHARSET;
			elseif (defined("LANG_CHARSET") && (strlen(LANG_CHARSET) > 0))
				$logicalEncoding = LANG_CHARSET;
			elseif (defined("BX_DEFAULT_CHARSET"))
				$logicalEncoding = BX_DEFAULT_CHARSET;
			else
				$logicalEncoding = "windows-1251";

			return strtolower($logicalEncoding);
		}

		/**
		 * ������� ��� ��������� ��������� ����� �������-������� �.�. ��� ����� ������ ������
		 * @param mixed $val
		 * @param string $key
		 * @param array $arEnc
		 * @param bool $return
		 * @return NULL | string
		 */
		private static function __ConvertCharset(&$val, $key, $arEnc, $return = false) {
			global $APPLICATION;
			$val = $APPLICATION->ConvertCharset($val, $arEnc['from'], $arEnc['to']);
			if ($return) {
				return $val;
			}
		}

		public static function getEncodeArrayWithKeys($array, $encFrom, $encTo) {

			$resultArray = array();

			if (is_array($array)) {

				foreach ($array as $key => $value) {

					$key = self::__ConvertCharset($key, false, array('from' => $encFrom, 'to' => $encTo), true);

					if (is_array($value)) {

						$resultArray[$key] = self::getEncodeArrayWithKeys($value, $encFrom, $encTo);

					} else {

						$resultArray[$key] = self::__ConvertCharset($value, false, array('from' => $encFrom, 'to' => $encTo), true);

					}
				}
			}

			return $resultArray;
		}

		/**
		 * ������������ ���������� �������� �������
		 * @param $array
		 * @param $encFrom
		 * @param $encTo
		 * @return bool
		 */
		static public function encodeArray(&$array, $encFrom, $encTo) {
			if (!is_array($array)) return false;

			array_walk_recursive($array, 'self::__ConvertCharset', array('from' => $encFrom, 'to' => $encTo));
			return true;
		}

		/**
		 * ������� ��������� ����� �� ���������������, ���� �� �� ������ ���
		 * @param $arRequest
		 * @return bool
		 */
		public static function encodeAjaxRequest(&$arRequest) {
			// ajax ������ ��������� � UTF-8, ���� ��������� ����� ����������, ���� ��������������
			if (($curEnc = self::getLogicalEncoding()) == 'utf-8') return true;
			self::encodeArray($arRequest, 'utf-8', $curEnc);
			return true;
		}

		/**
		 * ����������� ������ ����������, ������ �� ���� ���-��������
		 * @param $arParams
		 * @return string
		 */
		static public function GetEncodedArParams($arParams) {
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
				// ������� ����������� ��������� (��� ��� �� �����)
				if ($key{0} != "~") {
					// ���� ��������� ����� �� utf - ������������ �������� � utf
					if (!is_object($val)) {
						// ���� ����� ��������� �������� � �������� ���������� ����� ���������� ����������
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
		 * ������������� ������ �� ������ � ������������ ���� �����
		 * @param $string
		 * @return array|mixed
		 */
		static public function GetDecodedArParams($string) {
			$isNeedEnc = (($curEnc = self::getLogicalEncoding()) != 'utf-8');
			$arParams = json_decode(base64_decode($string), true);
			if (!is_array($arParams)) return array();
			if ($isNeedEnc) {
				self::encodeArray($arParams, 'utf-8', $curEnc);
			}
			return $arParams;
		}

		/**
		 * �������� ��������������� � UTF-8 ������
		 * ��� ��������� �� ������� �������
		 * @param array $array
		 * @return array
		 */
		public static function GetArrayResponse($array) {
			if (!is_array($array)) return array();
			$isNeedEnc = (($curEnc = self::getLogicalEncoding()) != 'utf-8');
			if ($isNeedEnc) {
				self::encodeArray($array, $curEnc, 'utf-8');
			}
			return $array;
		}

		/**
		 * ���� � ������ $string ��������� ���� #*#
		 * ��������� ���� �� ����� ���������, � ���� ���� ���������� �� ��������
		 * ���� ����� ��������� ��� - ��������
		 * ����.:
		 * $string = "site_#SITE_ID#/about/#NON_EXISTING_CONSTANT#"
		 * ������ == "site_s1/about/"
		 * @param string $string
		 * @return string mixed
		 */
		public static function GetConstantUrl($string) {
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
		 * ������� ��� ShowViewContent ��� ������ ������� ��� view'�
		 * ���������� main ������� OnEpilog
		 * ��������! ������ view ������ �������� � �������! �.�. � ���� ������
		 * �� ��������� ��� � ����� ������
		 * @param $view
		 */
		static public function showViewContent($view) {
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
						'CONTENT_LANG_FILE' => $contentLangFile
					);
					if (!self::$_isViewActive) {
						AddEventHandler('main', 'OnEpilog', 'CRZTools::fireContentTarget');
						//AddEventHandler('main', 'OnEpilogCustom', 'CRZTools::fireContentTarget');
						self::$_isViewActive = true;
					}
				}
			}

		}

		/**
		 * �������� view'� ���������� � ���������� � ������ AddViewContent
		 */
		static public function fireContentTarget() {
			global $APPLICATION, $USER, $DB;
			foreach (self::$_arViewTargets as $view => &$arViewTarget) {
				ob_start();
				__IncludeLang($arViewTarget['CONTENT_LANG_FILE']);
				/** @noinspection PhpIncludeInspection */
				include $arViewTarget['CONTENT_FILE'];
				$content = ob_get_clean();
				$APPLICATION->AddViewContent($view, $content);
				unset($content);
			}
			unset($arViewTarget);
		}

		/**
		 * ������ ������������ ������� � ����� �������������� �����
		 * @param integer $quantity �����
		 * @param string $nominative ������������ ����� ��. ����� (���)
		 * @param string $genetive ����������� ����� ��. �����  (����)
		 * @param string $genplural ����������� ����� ����. ����� (�����)
		 * @return string
		 */
		static public function rusQuantity($quantity, $nominative, $genetive = NULL, $genplural = NULL) {
			$oneState = false;
			if ($genetive == NULL || $genplural == NULL) {
				$oneState = true;
			}
			$quantity = abs(intval($quantity));
			switch ($quantity % 100) {
				case 11:
				case 12:
				case 13:
				case 14:
					return ($oneState) ? $nominative . GetMessage('SOME_GM') : $genplural;
					break;
				default:
					switch ($quantity % 10) {
						case 1:
							return $nominative;
							break;
						case 2:
						case 3:
						case 4:
							return ($oneState) ? $nominative . GetMessage('SOME_G') : $genetive;
							break;
						default:
							return ($oneState) ? $nominative . GetMessage('SOME_GM') : $genplural;
					}
			}
		}

		static private $_moduleId = 0;

		public static function IncludeMainLang($moduleId = false) {
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
			return \Bitrix\Main\Localization\Loc::loadLanguageFile($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/' . $moduleId . '/index.php');
		}

		public static function FormatPrice($num, $currency = false, $precision = 0) {
			if (!$currency) {
				$rubStr = GetMessage('SHIN_RUB_SYMBOL');
				if (!isset($rubStr)) {
					self::IncludeMainLang();
					$rubStr = GetMessage('SHIN_RUB_SYMBOL');
				}
				$currency = '<span class="b-rub">' . $rubStr . '</span>';
			}
			return number_format($num, $precision, '.', ' ') . $currency;
		}
	}
}