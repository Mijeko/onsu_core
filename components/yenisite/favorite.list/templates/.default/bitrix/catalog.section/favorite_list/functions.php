<?php

namespace Yenisite\Favorite\Template;

use Bitrix\Currency\CurrencyTable as BX_CurrencyTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Tools {

	static private $_cacheDir = '/cachbuyTools/catalog';
	static private $_cacheTime = 604800;

	// SYSTEM PROPS
	static public $_systemProps = array('SERVICE', 'MANUAL', 'ID_3D_MODEL', 'MAILRU_ID', 'VIDEO', 'ARTICLE', 'HOLIDAY', 'SHOW_MAIN', 'HIT', 'SALE', 'PHOTO', 'DESCRIPTION', 'MORE_PHOTO', 'NEW', 'KEYWORDS', 'TITLE', 'FORUM_TOPIC_ID', 'FORUM_MESSAGE_CNT', 'PRICE_BASE', 'H1', 'YML', 'FOR_ORDER', 'WEEK_COUNTER', 'WEEK', 'BESTSELLER', 'SALE_INT', 'SALE_EXT', 'COMPLETE_SETS', 'vote_count', 'vote_sum', 'rating', 'BLOG_POST_ID', 'BLOG_COMMENTS_CNT', 'TURBO_YANDEX_LINK', 'MINIMUM_PRICE', 'MAXIMUM_PRICE');
	static public $_systemPropsMask = array('CML2_', 'TURBO_');

	public static function insertServiceInfo() {
		// TODO: COMPOSITE!!!!!
		return "<div class='cache_time_debug'>" . date('H:i:s - d.m.Y') . "</div>";
	}

	public static function noJsPage() {
		return SITE_DIR . "nojs.php";
	}

	public static function ShowMessage($arMess) {
		// TYPE => (success , fail)
		$arConvertType = array(
			"ERROR" => "fail",
			"error" => "fail",
			"OK" => "success",
			"ok" => "success"
		);

		if (!is_array($arMess))
			$arMess = Array("MESSAGE" => $arMess, "TYPE" => "ERROR");

		if (is_array($arMess["MESSAGE"])) {
			$arMess["MESSAGE"] = implode('<br>', $arMess["MESSAGE"]);
		}

		$arMess["TYPE"] = strtr($arMess["TYPE"], $arConvertType);

		if ($arMess["MESSAGE"] <> "") {
			$arMess["MESSAGE"] = str_replace("<br>", "\n", $arMess["MESSAGE"]);
			$arMess["MESSAGE"] = str_replace("<br />", "\n", $arMess["MESSAGE"]);

			$arMess["MESSAGE"] = htmlspecialcharsbx($arMess["MESSAGE"]);

			$arMess["MESSAGE"] = str_replace("\n", "<br />", $arMess["MESSAGE"]);
			$arMess["MESSAGE"] = str_replace("&amp;", "&", $arMess["MESSAGE"]);

			\CJSCore::RegisterExt('rz_b2_ajax_core', array(
				'js' => SITE_TEMPLATE_PATH . "/js/back-end/ajax_core.js",
				'lang' => SITE_TEMPLATE_PATH . '/lang/' . LANGUAGE_ID . '/ajax.php',
				'rel' => array('jquery'),
			));
			\CJSCore::Init(array('rz_b2_ajax_core'));

			?>
			<script>
				if (typeof RZB2.ajax.showMessage != 'undefined') {
					RZB2.ajax.showMessage('<?=$arMess["MESSAGE"]?>', '<?=$arMess["TYPE"]?>');
				}
				else {
					console.log('undefined RZB2.ajax.showMessage');
				}
			</script><?
		}
	}

	public static function getElementPriceFormat($currency = false, $notFormatValue = 0, $formatValue = false, $arAttr = array()) {
		$notFormatValue = str_replace(' ', '', $notFormatValue);
		$notFormatValue = floatval($notFormatValue);
		if (!$currency && Loader::IncludeModule("sale"))
			$currency = \CSaleLang::GetLangCurrency(SITE_ID);
		if (!$currency)
			$currency = 'RUB';

		$priceFormat = '';
		$price_mask = '#PRICE_VALUE#';
		$templateStr = $price_mask;
		if (!isset($arAttr['CLASS']) || empty($arAttr['CLASS'])) {
			$arAttr['CLASS'] = 'value';
		}

		if (!empty($arAttr)) {
			if (!isset($arAttr['TAG']) || empty($arAttr['TAG'])) {
				$arAttr['TAG'] = 'span';
			}

			$templateStr = '<' . strtolower($arAttr['TAG']);
			foreach ($arAttr as $attr => $value) {
				if ($attr == 'TAG') continue;
				$templateStr .= ' ' . strtolower($attr) . '="' . $value . '"';
			}
			$templateStr .= '>' . $price_mask . '</' . strtolower($arAttr['TAG']) . '>';
		}

		$bCurrency = Loader::IncludeModule('currency');

		if ($bCurrency) {
			$notFormatValue = \CCurrencyLang::CurrencyFormat($notFormatValue, $currency, false);
		}
		if ($currency == 'RUB') {
			$priceFormat = str_replace($price_mask, $notFormatValue, $templateStr) . ' <span class="b-rub">' . GetMessage('BITRONIC2_RUB_CHAR') . '</span>';
		} elseif (!$bCurrency || !$currency) {
			if (empty($formatValue)) $formatValue = $notFormatValue;
			$priceFormat = str_replace($price_mask, $formatValue, $templateStr);
		} else {
			$arCurFormat = \CCurrencyLang::GetCurrencyFormat($currency);
			$priceFormat = str_replace($price_mask, $notFormatValue, str_replace('#', $templateStr, $arCurFormat['FORMAT_STRING']));
		}

		return $priceFormat;
	}

	/**
	 * Return pictures id array of element
	 */
	public static function getElementPictureArray($arElement, $image_prop = 'MORE_PHOTO') {
		global $CACHE_MANAGER;
		if (!is_array($arElement))
			return false;

		$obCache = new \CPHPCache;
		$cache_id = 'ELEM_' . $arElement['ID'] . '_PICT_ARRAY';

		if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
			$vars = $obCache->GetVars();
			$arReturn = $vars;
		} elseif ($obCache->StartDataCache()) {
			if (defined("BX_COMP_MANAGED_CACHE")) {
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache(self::$_cacheDir);
			}

			$arReturn = array();

			$detail = self::GetFieldPicSrc($arElement['DETAIL_PICTURE']);
			if ($detail !== false)
				$arReturn[] = $detail;

			if (is_array($arElement['PROPERTIES'][$image_prop])) {
				$arPropFile = $arElement['PROPERTIES'][$image_prop]['VALUE'];
				if (is_array($arPropFile))
					$arReturn = array_merge($arReturn, $arPropFile);
				elseif (intval($arPropFile) > 0)
					$arReturn[] = intval($arPropFile);
			}

			if ($detail === false) {
				$preview = self::GetFieldPicSrc($arElement['PREVIEW_PICTURE']);
				if ($preview !== false)
					$arReturn[] = $preview;
			}

			if (empty($arReturn)) {
				$arReturn[] = self::getElementPictureById($arElement['ID'], false, true, true);
			}

			if (defined("BX_COMP_MANAGED_CACHE")) {
				$CACHE_MANAGER->RegisterTag("iblock_id_" . $arElement['IBLOCK_ID']);
				$CACHE_MANAGER->EndTagCache();
			}

			$obCache->EndDataCache($arReturn);
		}
		unset($obCache);


		return $arReturn;
	}

	/**
	 * Function return image ID of element
	 */
	public static function getElementPictureById($itemId, $resizer_set = false, $findInParent = true, $returnImgId = false) {
		if (!Loader::IncludeModule("iblock") || intval($itemId) <= 0)
			return false;

		global $CACHE_MANAGER;
		$obCache = new \CPHPCache;
		$cache_id = 'ELEM_' . $itemId . '_PICT';

		$bResizer = false;
		$arReturn = array();
		if (intval($resizer_set) > 0 && Loader::IncludeModule("yenisite.resizer2")) {
			$bResizer = true;
			// $cache_id .= "_RS".$resizer_set;
		}

		if ($obCache->InitCache(self::$_cacheTime, $cache_id, self::$_cacheDir)) {
			$vars = $obCache->GetVars();
			$arReturn = $vars;
		} elseif ($obCache->StartDataCache()) {
			if (defined("BX_COMP_MANAGED_CACHE")) {
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache(self::$_cacheDir);
			}

			$dbElement = \CIBlockElement::GetByID($itemId);
			if ($arElement = $dbElement->GetNext()) {
				// get image :
				$image = self::getElementPicture($arElement);
				if (intval($image) <= 0 && Loader::includeModule('catalog')) {
					$arOffers = \CIBlockPriceTools::GetOffersArray($arElement['IBLOCK_ID'], $arElement['ID'], array('sort' => 'asc'), array('ID'), array(), 0, array(), false, array());

					if (!empty($arOffers)) {
						foreach ($arOffers as $arOffer) {
							$image = self::getElementPictureById($arOffer['ID'], false, false, true);
							if (intval($image) > 0) {
								break;
							}
						}
						unset($arOffers);
					} elseif ($findInParent) {
						$arElement = \CCatalogSKU::GetProductInfo($arElement['ID']);
						if (is_array($arElement) && intval($arElement['ID'] > 0)) {
							$image = self::getElementPictureById($arElement['ID'], false, true, true);
						}
					}
				}

				$arReturn = array(
					'PRODUCT_PICTURE_ID' => $image,
					'PRODUCT_PICTURE_SRC' => \CFile::GetPath($image),
				);

				if (defined("BX_COMP_MANAGED_CACHE")) {
					$CACHE_MANAGER->RegisterTag("iblock_id_" . $arElement['IBLOCK_ID']);
					$CACHE_MANAGER->EndTagCache();
				}
			} else {
				$arReturn['PRODUCT_PICTURE_ID'] = 0;
				$arReturn['PRODUCT_PICTURE_SRC'] = '';
			}

			$obCache->EndDataCache($arReturn);
		}
		unset($obCache);

		if ($returnImgId) {
			return $arReturn['PRODUCT_PICTURE_ID'];
		} else {
			if ($bResizer)
				$arReturn['PRODUCT_PICTURE_SRC'] = \CResizer2Resize::ResizeGD2($arReturn['PRODUCT_PICTURE_SRC'], intval($resizer_set));

			return $arReturn['PRODUCT_PICTURE_SRC'];
		}
	}

	public static function getElementPicture($arElement, $arParamsImage = 'DETAIL_PICTURE', $default_image_code = 'MORE_PHOTO') {
		if (!is_array($arElement))
			return false;

		$picsrc = false;
		$find_in_prop = false;
		if ($arParamsImage != 'PREVIEW_PICTURE' && $arParamsImage != 'DETAIL_PICTURE') {
			$find_in_prop = true;
			if (!$picsrc = self::GetPropPicSrc($arElement, $arParamsImage)) {
				$picsrc = self::GetPropPicSrc($arElement, $default_image_code);
			}
		}

		if ($arParamsImage == 'DETAIL_PICTURE' || $arParamsImage == 'PREVIEW_PICTURE') {
			$picsrc = self::GetFieldPicSrc($arElement[$arParamsImage]);
		}

		if (!$picsrc) {
			if (!$find_in_prop)
				$picsrc = self::GetPropPicSrc($arElement, $default_image_code);

			if (!$picsrc && $arParamsImage != 'DETAIL_PICTURE')
				$picsrc = self::GetFieldPicSrc($arElement['DETAIL_PICTURE']);

			if (!$picsrc && $arParamsImage != 'PREVIEW_PICTURE')
				$picsrc = self::GetFieldPicSrc($arElement['PREVIEW_PICTURE']);
		}
		return $picsrc;
	}

	private static function GetPropPicSrc($arElement, $prop_code, $getCount = false) {
		if (!is_array($arElement) || !$prop_code)
			return false;
		if (!Loader::IncludeModule("iblock"))
			return false;

		$arPropFile = false;

		if (is_array($arElement['PROPERTIES'][$prop_code])) {
			$arPropFile = $arElement['PROPERTIES'][$prop_code]['VALUE'];
		} else {
			if (!empty($arElement['PRODUCT_ID']))
				$arElement['ID'] = $arElement['PRODUCT_ID'];

			if (empty($arElement['IBLOCK_ID'])) {
				$arElement['IBLOCK_ID'] = self::getElementIblockId($arElement['ID']);
			}

			$dbProp = \CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], array("ID" => "ASC", "VALUE_ID" => "ASC"), Array("CODE" => $prop_code));
			if ($arProp = $dbProp->Fetch()) {
				$arPropFile = $arProp['VALUE'];
			}
		}
		if ($arPropFile) {
			if ($getCount)
				return count($arPropFile);
			if (is_array($arPropFile)) {
				return $arPropFile[0];
			} elseif (intval($arPropFile) > 0) {
				return $arPropFile;
			}


			/* if(intval($pic_id) > 0)
			{
				return CFile::GetPath(intval($pic_id)) ;
			} */
		}
		return false;
	}

	private function GetFieldPicSrc($arElementPicField) {

		if (is_array($arElementPicField)) {
			return $arElementPicField['ID'];
		} elseif (intval($arElementPicField) > 0) {
			return intval($arElementPicField); //CFile::GetPath(intval($arElementPicField)) ;
		}

		return false;
	}

	public static function getElementIblockId($element_id) {
		if (intval($element_id) <= 0)
			return false;

		$arElement = array();
		$obCache = new \CPHPCache();
		if ($obCache->InitCache(self::$_cacheTime, "IBID_{$element_id}", self::$_cacheDir)) {
			$arElement = $obCache->GetVars();
		} elseif ($obCache->StartDataCache()) {
			$res = \CIBlockElement::GetByID($element_id);
			global $CACHE_MANAGER;
			if (defined("BX_COMP_MANAGED_CACHE")) {
				$CACHE_MANAGER->StartTagCache(self::$_cacheDir);
			}
			if ($ar_res = $res->GetNext()) {
				$arElement = $ar_res;
				$CACHE_MANAGER->RegisterTag("iblock_id_" . $arElement['IBLOCK_ID']);
				$CACHE_MANAGER->EndTagCache();
			}
			$obCache->EndDataCache($arElement);
		}

		return $arElement['IBLOCK_ID'];
	}

	public static function getCurrencyArray($currencyId = false) {
		if (!Loader::includeModule('currency'))
			return false;
		$arReturn = array();
		$obCache = new \CPHPCache();
		if ($obCache->InitCache(self::$_cacheTime, "CURRENCY_TABLE", self::$_cacheDir)) {
			$arReturn = $obCache->GetVars();
		} elseif ($obCache->StartDataCache()) {
			if (defined("BX_COMP_MANAGED_CACHE")) {
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache(self::$_cacheDir);
			}
			$currencyIterator = BX_CurrencyTable::getList(array(
				'select' => array('CURRENCY')
			));
			while ($currency = $currencyIterator->fetch()) {
				$currencyFormat = \CCurrencyLang::GetFormatDescription($currency['CURRENCY']);
				$arReturn[] = array(
					'CURRENCY' => $currency['CURRENCY'],
					'FORMAT' => array(
						'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
						'DEC_POINT' => $currencyFormat['DEC_POINT'],
						'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
						'DECIMALS' => $currencyFormat['DECIMALS'],
						'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
						'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
					)
				);
			}
			unset($currencyFormat, $currency, $currencyIterator);
			global $CACHE_MANAGER;
			if (defined("BX_COMP_MANAGED_CACHE")) {
				$CACHE_MANAGER->RegisterTag("currency");
				$CACHE_MANAGER->EndTagCache();
			}

			$obCache->EndDataCache($arReturn);
		}
		if ($currencyId !== false) {
			foreach ($arReturn as $arItem) {
				if ($arItem['CURRENCY'] == $currencyId) {
					$arReturn = array($arItem);
				}
			}
		}
		return $arReturn;
	}

	public static function getCurrencyTemplate($currency = false) {
		if (!$currency) {
			return false;
		}

		$strReturn = '';

		if ($currency == 'RUB') {
			$strReturn = '<span class="b-rub">' . GetMessage('BITRONIC2_RUB_CHAR') . '</span>';
		}

		if (!$strReturn) {
			$arCurrencies = self::getCurrencyArray();
			foreach ($arCurrencies as $arCurrency) {
				if ($currency == $arCurrency['CURRENCY']) {
					$strReturn = trim(str_replace('#', '', $arCurrency['FORMAT']['FORMAT_STRING']));
					break;
				}
			}
		}

		return $strReturn;
	}

	public static function getDetailPropShowList($iblockId = false, $arSettingsHide = array()) {
		if (intval($iblockId) <= 0) {
			return false;
		}
		if (!is_array($arSettingsHide)) {
			$arSettingsHide = array();
		}
		$arReturn = array();

		$obCache = new \CPHPCache;
		$cache_id = 'DPSL_' . $iblockId;
		$cacheDir = self::$_cacheDir . '/props';
		$arSettingsHide = array_merge(self::$_systemProps, $arSettingsHide);
		$init_cache = false;

		if ($obCache->InitCache(self::$_cacheTime, $cache_id, $cacheDir)) {
			$arReturn = $obCache->GetVars();

			$init_cache = count(array_intersect($arReturn, $arSettingsHide)) == 0;
		}

		if (!$init_cache && $obCache->StartDataCache()) {
			if (defined("BX_COMP_MANAGED_CACHE")) {
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache($cacheDir);
			}

			$dbProps = \CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockId, 'ACTIVE' => 'Y'));
			while ($arProp = $dbProps->Fetch()) {
				if (in_array($arProp["CODE"], $arSettingsHide))
					continue;

				$find = false;
				foreach (self::$_systemPropsMask as $mask) {
					if (strpos($arProp['CODE'], $mask) !== false) {
						$find = true;
						break;
					}
				}
				if ($find)
					continue;

				$arReturn[] = $arProp['CODE'];
			}
			global $CACHE_MANAGER;
			if (defined("BX_COMP_MANAGED_CACHE")) {
				$CACHE_MANAGER->RegisterTag("iblock_id_" . $iblockId);
				$CACHE_MANAGER->EndTagCache();
			}

			$obCache->EndDataCache($arReturn);
		}

		unset($obCache);

		return $arReturn;
	}
}

?>