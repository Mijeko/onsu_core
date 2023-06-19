<?
namespace Yenisite\Core;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\InheritedProperty;
use Bitrix\Iblock\Template;

Loc::loadMessages(__FILE__);

class Catalog {
	const cache_dir = "romza/core_nav_child";
	const cache_time = 36000000;

	/**
	 * Get array with offer parameters, name: value, name: value, etc
	 * @param array $arItem
	 * @param array $arOfferProps
	 * @return array
	 */
	public static function getFlatOffersList($arItem, $arOfferProps) {
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$arSKU = \CCatalogSKU::GetInfoByProductIBlock($arItem['IBLOCK_ID']);
		$boolSKU = !empty($arSKU) && is_array($arSKU);
		$arResult = array();
		if ($boolSKU) {
			$arSKUPropList = \CIBlockPriceTools::getTreeProperties(
				$arSKU,
				$arOfferProps,
				array(
					'NAME' => '-'
				)
			);
			$arSKUPropIDs = array_keys($arSKUPropList);
			foreach ($arItem['OFFERS'] as &$arOffer) {
				$arResult[$arOffer['ID']]['CAN_BUY'] = ($arOffer['CAN_BUY'] == true && $arOffer['CAN_BUY'] == 'Y');
				$arResult[$arOffer['ID']]['ID'] = $arOffer['ID'];
				$arResult[$arOffer['ID']]['CATCH_BUY'] = $arOffer['CATCH_BUY'];
				foreach ($arSKUPropIDs as &$propID) {
					if (isset($arOffer['PROPERTIES'][$propID]) && !empty($arOffer['PROPERTIES'][$propID]['VALUE'])) {
						$propVal = $arOffer['PROPERTIES'][$propID]['VALUE'];
						if (is_array($propVal)) {
							$propVal = trim(implode(', ', $propVal));
						}
						if (isset($arOffer['PROPERTIES'][$propID]['DISPLAY_VALUE']) && !empty($arOffer['PROPERTIES'][$propID]['DISPLAY_VALUE'])){
							$arResult[$arOffer['ID']]['NAME'] .= " " . $arOffer['PROPERTIES'][$propID]['NAME'] . ":" . $arOffer['PROPERTIES'][$propID]['DISPLAY_VALUE'];
						} else
							$arResult[$arOffer['ID']]['NAME'] .= " " . $arOffer['PROPERTIES'][$propID]['NAME'] . ":" . $propVal;
					}
				}
				unset($propID);
				if (!empty($arOffer['MIN_PRICE']['PRINT_DISCOUNT_VALUE'])) {
					$arResult[$arOffer['ID']]['PRICE'] = htmlspecialcharsex(str_replace('"', '\'', $arOffer['MIN_PRICE']['PRINT_DISCOUNT_VALUE']));
					if ($arOffer['MIN_PRICE']['VALUE'] != $arOffer['MIN_PRICE']['DISCOUNT_VALUE']) {
						$arResult[$arOffer['ID']]['PRICE_OLD'] = htmlspecialcharsex(str_replace('"', '\'', $arOffer['MIN_PRICE']['PRINT_VALUE']));
						$arResult[$arOffer['ID']]['PRICE_DIFF'] = htmlspecialcharsex(str_replace('"', '\'', $arOffer['MIN_PRICE']['PRINT_DISCOUNT_DIFF']));
					}
				}
			}
			unset($arOffer);
		}
		return $arResult;
	}

	/**
	 * Get sort field from cookie or $_GET,
	 * @param array $arParams
	 * @param bool $isSku
	 * @param string $cookieName
	 * @param array $arDefaultSort
	 * @return array
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function getSort($arParams, $isSku = false, $cookieName = "CATALOG_SORT", $arDefaultSort = array())
	{
		global $APPLICATION;
		$arPricesId = array();
		$hasCatalog = Loader::IncludeModule('catalog');
		if (!isset($isSku) && isset($arParams['IBLOCK_ID'])) {
			if ($hasCatalog) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$mxResult = \CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
				if (is_array($mxResult)) {
					$isSku = true;
				} else {
					$isSku = false;
				}
			}
		} elseif (!isset($isSku) && !isset($arParams['IBLOCK_ID'])) {
			return array('BY' => 'NAME', 'ORDER' => 'ASC');
		}
		if (!$isSku) {
			$obCache = new \CPHPCache();
			$arPriceCache = array(
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ACTIVE" => "Y",
				"GLOBAL_ACTIVE" => "Y",
				"PRICE_CODE" => $arParams['PRICE_CODE']
			);
			if ($obCache->InitCache(36000, serialize($arPriceCache), "/iblock/catalog")) {
				$arPricesId = $obCache->GetVars();
			} elseif ($obCache->StartDataCache()) {
				$arPricesId = array();
				if ($hasCatalog) {
					/** @noinspection PhpDynamicAsStaticMethodCallInspection */
					$dbPrices = \CCatalogGroup::GetList(
						array(),
						array("XML_ID" => $arParams['PRICE_CODE']),
						false,
						false,
						array("ID")
					);
					if (defined("BX_COMP_MANAGED_CACHE")) {
						global $CACHE_MANAGER;
						$CACHE_MANAGER->StartTagCache("/iblock/catalog");
						$hasPrices = false;
						while ($arPrice = $dbPrices->GetNext()) {
							$hasPrices = true;
							$arPricesId[] = $arPrice['ID'];
						}
						if ($hasPrices) {
							$CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams["IBLOCK_ID"]);
						}
						$CACHE_MANAGER->EndTagCache();
					} else {
						$hasPrices = false;
						while ($arPrice = $dbPrices->GetNext()) {
							$hasPrices = true;
							$arPricesId[] = $arPrice['ID'];
						}
						if (!$hasPrices) {
							$arPricesId = array();
						}
					}
				} elseif (Loader::IncludeModule('yenisite.market')) {
					/** @noinspection PhpUndefinedClassInspection */
					$dbPrice = \CMarketPrice::GetBasePrice();
					/** @noinspection PhpUndefinedMethodInspection */
					$arPrice = $dbPrice->Fetch();
					$arPricesId['PROP_1'] = $arPrice['code'];
				} else {
					//todo: work with situation when there are no market or catalog
				}
				$obCache->EndDataCache($arPricesId);
			}
		}
		$sortOrder = 'ASC';

		if (empty($arDefaultSort)) {
			$arDefaultSort = array(
				'name' => 'NAME',
				'price_asc' => 'CATALOG_PRICE_#ID#',
				'price_desc' => 'CATALOG_PRICE_#ID#',
				'shows' => 'SHOWS',
			);
		}
		else
		{
			$arDefaultSort = array_change_key_case($arDefaultSort, CASE_LOWER);
		}
		if (empty($cookieName)) {
			$cookieName = 'CATALOG_SORT';
		}
		if (isset($_GET[strtolower($cookieName)])) {
			$dSort = $_GET[strtolower($cookieName)];
		} else {
			$dSort = trim($APPLICATION->get_cookie($cookieName));
		}
		if (empty($dSort)){
			$dSort = $arParams['DEFAULT_SORT'];
		}
		if (!isset($arDefaultSort[strtolower($dSort)])) {
			$dSort = key($arDefaultSort);
		}

		$dSort = strtoupper($dSort);
		$arDSort = explode('_', $dSort);
		if (isset($arDSort[1])) {
			$sortOrder = $arDSort[1];
		}

		$sortBy = $arDefaultSort[strtolower($dSort)];
		if ($arDSort[0] == "PRICE") {
			if ($isSku) {
				$sortBy = "PROPERTY_MINIMUM_PRICE";
			} else {
				$arSort = array();
				foreach ($arPricesId as $key => $id) {
					if ($key[0] == 'P') {
						$arSort[] = 'PROPERTY_' . $id;
					} else {
						$arSort[] = str_replace("#ID#", $id, $arDefaultSort[strtolower($dSort)]);
					}
				}
				$sortBy = $arSort[0];
			}
		}
		return array('BY' => $sortBy, "ORDER" => $sortOrder, 'CURRENT' => strtolower($dSort));
	}

	/**
	 * Get string of view from GET or COOKIE
	 * @param string $cookieName
	 * @param array $arViews
	 * @return string
	 */
	public static function getViewMode($cookieName = "VIEW_MODE", $arViews = array(),$defViewMode = '')
	{
		global $APPLICATION;
		if (empty($cookieName)) {
			$cookieName = 'VIEW_MODE';
		}
		if (empty($arViews)) {
			$arViews = array('grid_view', 'list_view', 'table_view');
		}
		if (isset($_GET[strtolower($cookieName)])) {
			$viewMode = $_GET[strtolower($cookieName)];
		} else {
			$viewMode = trim($APPLICATION->get_cookie($cookieName));
		}
		if (!in_array($viewMode, $arViews)) {
			$viewMode = empty($defViewMode) ? $arViews[0] : $arViews[array_search($defViewMode,$arViews)];
		}
		return $viewMode;
	}

	/**
	 * Get amount of viewed elements from GET or COOKIE
	 * if choose ALL set 10000
	 * @param string $cookieName
	 * @param array $arCount
	 * @return int
	 */
	public static function getCount($cookieName = 'LIST_COUNT', $arCount = array())
	{
		global $APPLICATION;
		if (empty($cookieName)) {
			$cookieName = 'LIST_COUNT';
		}
		if (empty($arCount)) {
			$arCount = array('16', '32', '48', 'ALL');
		}
		if (isset($_GET[strtolower($cookieName)])) {
			$count = $_GET[strtolower($cookieName)];
		} else {
			$count = trim($APPLICATION->get_cookie($cookieName));
		}
		if (!in_array($count, $arCount)) {
			$count = $arCount[0];
		}
		if ($count == 'ALL') {
			$count = 10000;
		}
		return $count;
	}

	/**
	 * Get UF_ default value for section
	 * if section has none than look up for parent sections up to iblock root (inheritance)
	 * @param array $arItem - element array returned by bitrix GetList
	 * @param string $UF_NAME - parameter name with mask UF_*
	 * @return int
	 */
	public static function getUFDefaultValue($arItem, $UF_NAME) {
		$arSelect = array('ID', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL', $UF_NAME);
		$return = false;

		$iblockId = intval($arItem['IBLOCK_ID']);
		$sectionId = (intval($arItem['~IBLOCK_SECTION_ID']) > 0)
			? $arItem['~IBLOCK_SECTION_ID']
			: intval($arItem['IBLOCK_SECTION_ID']);

		if ($iblockId == 0 || $sectionId == 0) {
			return $return;
		}
		// кешируем результат
		$obCache = new \CPHPCache();
		if ($obCache->InitCache(360000, 'sect_' . $sectionId . '_' . $UF_NAME, '/romza/' . $UF_NAME)) {
			$return = $obCache->GetVars();
		} elseif ($obCache->StartDataCache()) {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$dbSect = \CIBlockSection::GetList(array(), array('IBLOCK_ID' => $iblockId, 'ID' => $sectionId), false, $arSelect);
			$arSect = $dbSect->GetNext();
			// check if section has its own value first
			// if none than fetch parent sections and iterate them from lower to upper (inheritance)
			if (!isset($arSect[$UF_NAME])) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$dbSect = \CIBlockSection::GetList(array('left_margin' => 'desc'), array(
					'IBLOCK_ID' => $iblockId,
					"<=LEFT_BORDER" => $arSect["LEFT_MARGIN"],
					">=RIGHT_BORDER" => $arSect["RIGHT_MARGIN"],
					"<DEPTH_LEVEL" => $arSect["DEPTH_LEVEL"]
				), false, $arSelect);
				while ($arSect = $dbSect->GetNext()) {
					if (isset($arSect[$UF_NAME])) {
						$return = $arSect[$UF_NAME];
						break;
					}
				}
			} else {
				$return = $arSect[$UF_NAME];
			}
			$obCache->EndDataCache($return);
		}
		return $return;
	}

	/**
	 * @param $id
	 * @param $valueId
	 * @return string
	 */
	public static function getIconClassFromPropId($id, $valueId) {
		$valueId = intval($valueId);
		$id = intval($id);
		if (intval($id) == 0 || $valueId == 0) {
			return "";
		}
		$iconClass = "";
		$obCache = new \CPHPCache();
		if ($obCache->InitCache(360000, 'prop_' . $id . '_' . $valueId, '/romza/propClassName/')) {
			$iconClass = $obCache->GetVars();
		} elseif ($obCache->StartDataCache()) {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$dbProp = \CIBlockProperty::GetByID($id);
			$arProp = $dbProp->GetNext();
			switch ($arProp['PROPERTY_TYPE']) {
				case "E":
					$arFilter = array(
						"IBLOCK_ID" => $arProp['LINK_IBLOCK_ID'],
						'ID' => $valueId
					);
					/** @noinspection PhpDynamicAsStaticMethodCallInspection */
					$dbElem = \CIBlockElement::GetList(array(), $arFilter, false, false, array('ID', 'PROPERTY_ICON_CLASS'));
					while ($arElem = $dbElem->GetNext()) {
						$iconClass = isset($arElem['PROPERTY_ICON_CLASS_VALUE']) ? $arElem['PROPERTY_ICON_CLASS_VALUE'] : "";
						break;
					}
					break;
				default:
					$iconClass = "";
			}
			$obCache->EndDataCache($iconClass);
		}
		return $iconClass;
	}

	/**
	 * Проверяет соответствие GET параметров фильтра
	 * и его глобального значения, если необходимо добавляет в глобальный фильтр нужные значения
	 * нужно для перехода по ссылкам для свойств которых нет в умном фильтре
	 * @param string $filterName
	 * @param array $getParams
	 */
	public static function setAdditionalFilter($filterName, $getParams = array()) {
		if (empty($getParams)) {
			$getParams = $_GET;
		}
		$arGetFilter = array();
		$arrFilter = &$GLOBALS[$filterName];
		foreach ($getParams as $key => $val) {
			if (strpos($key, $filterName) !== false) {
				$key = str_replace($filterName . '_', '', $key);
				$arLocalFilter = explode('_', $key);
				if (intval($arLocalFilter[0]) == 0) continue;
				if (!isset($arrFilter['=PROPERTY_' . $arLocalFilter[0]])) {
					$arGetFilter[$arLocalFilter[0]] = $arLocalFilter[1];
				}
			}
		}
		if (count($arGetFilter) > 0) {
			foreach ($arGetFilter as $propId => $val) {
				// todo:добавить кеширование
				$rsEnum = \CIBlockPropertyEnum::GetList(array("SORT" => "ASC", "VALUE" => "ASC"), array("PROPERTY_ID" => $propId));
				while ($enum = $rsEnum->Fetch()) {
					if (abs(crc32($enum["ID"])) == $val) {
						$arrFilter['=PROPERTY_' . $propId][] = $enum["ID"];
					}
				}
			}
		}
	}

	/**
	 * @var array
	 */
	static $arPropsVals = array();

	/**
	 * Возвращает массив характеристик для элемента ИБ
	 * $arItem = массив с описанием товара, обязательно наличие ключа ID
	 * * если есть ключ PROPERTIES тогда значения свойств берется из него, если нет - заполняется из БД
	 *
	 * $arNeedProps = array with values to proceed
	 *
	 * example: getParamsOfferList(array('ID'=> 12), array('DIAMETR'))
	 * @param array $arItem
	 * @param array $arNeedProps
	 * @return array
	 */
	public static function getParamsOfferList($arItem, $arNeedProps) {
		if (!Loader::IncludeModule('iblock')) return array();

		if (empty($arNeedProps) || !is_array($arNeedProps) || intval($arItem['ID']) == 0) {
			return array();
		}
		$arPropVals = array();

		$obCache = new \CPHPCache();
		if ($obCache->InitCache(360000, 'item_' . $arItem['ID'] . '_' . md5(serialize($arNeedProps)), '/romza/itemPropsVals')) {
			$arPropVals = $obCache->GetVars();
		} elseif ($obCache->StartDataCache()) {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$IBLOCK_ID = \CIBlockElement::GetIBlockByID($arItem['ID']);

			foreach ($arNeedProps as $propCode) {
				$rsProp = \CIBlockProperty::GetByID($propCode, $IBLOCK_ID);
				$arProp = $rsProp->Fetch();

				// if arItem has no PROPERTIES
				// todo: make one query before foreach
				if (!isset($arItem['PROPERTIES'])) {
					$arItem['PROPERTIES'] = array();
					$rsPropVals = \CIBlockElement::GetProperty($IBLOCK_ID, $arItem['ID'], "sort", "asc", array("CODE" => $propCode));
					while ($ar = $rsPropVals->GetNext()) {
						$arItem['PROPERTIES'][$propCode]['VALUES'][] = $ar['VALUE'];
					}
				}
				if (isset($arItem['PROPERTIES'][$propCode])) {
					// if item has no prop values, select all prop values
					if (empty($arItem['PROPERTIES'][$propCode]['VALUE'])) {
						if (isset(self::$arPropsVals[$IBLOCK_ID . '_' . $propCode])) {
							$arPropVals[$propCode] = self::$arPropsVals[$IBLOCK_ID . '_' . $propCode];
						} else {
							//todo: add obCache
							$rsPropVals = \CIBlockProperty::GetPropertyEnum($propCode, array('SORT' => 'ASC', 'VALUE' => 'ASC'), array('IBLOCK_ID' => $IBLOCK_ID));
							while ($ar = $rsPropVals->GetNext(false, false)) {
								$arPropVals[$propCode]['VALUES'][$ar['ID']] = $ar['VALUE'];
							}
							$arPropVals[$propCode]['NAME'] = $arProp['NAME'];
							$arPropVals[$propCode]['ID'] = $arProp['ID'];
							self::$arPropsVals[$IBLOCK_ID . '_' . $propCode] = $arPropVals[$propCode];
						}
						//elseif item has 1 prop val only
					} elseif (!is_array($arItem['PROPERTIES'][$propCode]['VALUE']) ||
						(is_array($arItem['PROPERTIES'][$propCode]['VALUE']) && count($arItem['PROPERTIES'][$propCode]['VALUE']) == 1)
					) {
						$propVal = is_array($arItem['PROPERTIES'][$propCode]['VALUE']) ? reset($arItem['PROPERTIES'][$propCode]['VALUE']) : $arItem['PROPERTIES'][$propCode]['VALUE'];
						$arPropVals[$propCode]['NAME'] = $arProp['NAME'];
						$arPropVals[$propCode]['ID'] = $arProp['ID'];
						$arPropVals[$propCode]['VALUES'][] = $propVal;
					} elseif ((is_array($arItem['PROPERTIES'][$propCode]['VALUE']) && count($arItem['PROPERTIES'][$propCode]['VALUE']) > 1)) {
						$arPropVals[$propCode]['NAME'] = $arProp['NAME'];
						$arPropVals[$propCode]['ID'] = $arProp['ID'];
						foreach ($arItem['PROPERTIES'][$propCode]['VALUE'] as $propVal) {
							$arPropVals[$propCode]['VALUES'][] = $propVal;
						}
					}
				}
			}
			$obCache->EndDataCache($arPropVals);
		}
		return $arPropVals;
	}

	/**
	 * Print or return HTML to form offer selection by its properties
	 * @param array $arItem
	 * @param array $arNeedProps
	 * @param string $nameTmpl - template string for attribute "name" with #CODE# or #ID#
	 * @param array $addAttr - additional attributes for tag select, where keys are attribute names, and values - its values
	 * @param bool $bReturn - flag to return array('COUNT_ALL' => 'all properties count',
	 *                            'COUNT' => 'visible elements count','HTML' => 'generated HTML')
	 * @return array
	 */
	public static function printParamsOfferList($arItem, $arNeedProps, $nameTmpl = 'PROP[#CODE#]', $addAttr = array(), $bReturn = false) {
		$arPropVals = self::getParamsOfferList($arItem, $arNeedProps);

		if (strpos($nameTmpl, '#CODE#') === false && strpos($nameTmpl, '#ID#') === false) {
			$nameTmpl = 'PROP[#CODE#]';
		}
		$strAttrs = '';
		if (!is_array($addAttr)) {
			$addAttr = array();
		}
		foreach ($addAttr as $attrName => $attrValue) {
			$strAttrs .= ' ' . strtolower($attrName) . '="' . $attrValue . '"';
		}

		$html = '';
		$cAll = 0;
		$cVisible = 0;
		foreach ($arPropVals as $propCode => $arProp) {
			$nameAttr = str_replace(array('#CODE#', '#ID#'), array($propCode, $arProp['ID']), $nameTmpl);
			if (empty($arProp['VALUES'])) continue;
			++$cAll;
			if (count($arProp['VALUES']) == 1) {
				$html .= '<input type="hidden" name="' . $nameAttr . '" value="' . htmlspecialcharsbx(reset($arProp['VALUES'])) . '" />';
			} else {
				++$cVisible;
				$html .= '<label>' . $arProp['NAME'] . ':</label> ';
				$html .= '<select name="' . $nameAttr . '"' . $strAttrs . '>';
				foreach ($arProp['VALUES'] as $val) {
					$val = htmlspecialcharsbx($val);
					$html .= '<option value="' . $val . '">' . $val . '</option>';
				}
				$html .= '</select><br/>';
			}
		}
		if ($bReturn) {
			return array('COUNT_ALL' => $cAll, 'COUNT' => $cVisible, 'HTML' => $html);
		} else {
			echo $html;
		}
		return true;
	}

	public static function getChainSiblings($sectionID, $chainPath) {
		static $arSections, $arSectionsIDs, $arSubSections,$IBLOCK_ID;
		$arResult = array();
		$cacheId = "cache_id_".$sectionID.str_replace('/','_',$chainPath);
		$obCache = new \CPHPCache();

		if ($obCache->InitCache(self::cache_time,$cacheId,self::cache_dir)){
			$arResult = $obCache->GetVars();
		} else if ($obCache->StartDataCache())
		{
			global $CACHE_MANAGER;
			$CACHE_MANAGER->StartTagCache(self::cache_dir);
			if ($arSections === NULL) {
				$arSections = $arSectionsIDs = $arSubSections = array();
				$IBLOCK_ID = false;

				$nav = \CIBlockSection::GetNavChain(false, $sectionID, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "SECTION_PAGE_URL"));
				while ($ar = $nav->GetNext()) {
					$arSections[] = $ar;
					$arSectionsIDs[] = ($ar["IBLOCK_SECTION_ID"]) ? $ar["IBLOCK_SECTION_ID"] : 0;
					$IBLOCK_ID = $ar["IBLOCK_ID"];
				}

				if ($arSectionsIDs) {
					/** @noinspection PhpDynamicAsStaticMethodCallInspection */
					$resSubSection = \CIBlockSection::GetList(array(),
						array(
							"ACTIVE" => "Y",
							"GLOBAL_ACTIVE" => "Y",
							"IBLOCK_ID" => $IBLOCK_ID,
							"SECTION_ID" => $arSectionsIDs
						),
						false,
						array("ID", "NAME", "IBLOCK_SECTION_ID", "SECTION_PAGE_URL")
					);

					while ($arSubSection = $resSubSection->GetNext()) {
						$arSubSection["IBLOCK_SECTION_ID"] = ($arSubSection["IBLOCK_SECTION_ID"] ? $arSubSection["IBLOCK_SECTION_ID"] : 0);
						$arSubSections[$arSubSection["IBLOCK_SECTION_ID"]][] = $arSubSection;
					}
					if (in_array(0, $arSectionsIDs)) {
						/** @noinspection PhpDynamicAsStaticMethodCallInspection */
						$resSubSection = \CIBlockSection::GetList(array(),
							array(
								"ACTIVE" => "Y",
								"GLOBAL_ACTIVE" => "Y",
								"IBLOCK_ID" => $IBLOCK_ID,
								"SECTION_ID" => false
							),
							false,
							array("ID", "NAME", "IBLOCK_SECTION_ID", "SECTION_PAGE_URL")
						);

						while ($arSubSection = $resSubSection->GetNext()) {
                            $arSubSections[$arSubSection["IBLOCK_SECTION_ID"]][] = $arSubSection;
						}
					}
				}
			}

			if ($arSections && strlen($chainPath)) {
				foreach ($arSections as $arSection) {
					if ($arSection["SECTION_PAGE_URL"] == $chainPath) {
						if ($arSubSections[$arSection["IBLOCK_SECTION_ID"]]) {
							foreach ($arSubSections[$arSection["IBLOCK_SECTION_ID"]] as $arSubSection) {
                                $strSectionSEO = self::getSeoForSection($IBLOCK_ID,$arSubSection['ID']);
                                $arSubSection["NAME"] = $strSectionSEO ? : $arSubSection["NAME"];
								$arResult[] = array("NAME" => $arSubSection["NAME"], "LINK" => $arSubSection["SECTION_PAGE_URL"]);
							}
						}
						break;
					}
				}
			}

			if ($IBLOCK_ID > 0) {
				$CACHE_MANAGER->RegisterTag('iblock_id_' . $IBLOCK_ID);
			}

			$CACHE_MANAGER->EndTagCache();

			$obCache->EndDataCache($arResult);
		}
		return $arResult;

	}

	public static function getSeoForSection ($IBLOCK_ID, $SECTION_ID){
        $ipropSectionValues = new InheritedProperty\SectionTemplates($IBLOCK_ID, $SECTION_ID);
        $entityTemplate = new Template\Entity\Section($SECTION_ID);
        $arTemplates = $ipropSectionValues->findTemplates();
        $template = $arTemplates['SECTION_PAGE_TITLE']['TEMPLATE'];
        $resultProcess = Template\Engine::process($entityTemplate,$template);

        return $resultProcess;
    }

	/**
	 * Checks the possibility of buying product
	 *
	 * @param $PRODUCT_ID - current product ID
	 * @param $QUANTITY - current product QUANTITY
	 * @param bool|false $bReturnQ - param to return original product quantity in case of error
	 * @return array keys:
	 * 					bool 'success' - status
	 * 					array 'result' - errors when success == false
	 * 					null |int 'q' - product original quantity
	 */
	public static function checkoutProductPurchase($PRODUCT_ID, $QUANTITY, $bReturnQ = false) {
		static $allModules;

		$arResult = array(
			'success' => true,
			'result' => array(),
		);
		if (!isset($allModules)) {
			$allModules = true;
			if (!Loader::includeModule("sale")) {
				$arResult['result'][11] = Loc::getMessage('CATALOG_ERR_NO_SALE_MODULE');
				$allModules = false;
			}
			if (Loader::includeModule("statistic") && isset($_SESSION['SESS_SEARCHER_ID']) && (int)$_SESSION["SESS_SEARCHER_ID"] > 0) {
				$arResult['result'][12] = Loc::getMessage('CATALOG_ERR_SESS_SEARCHER');
				$allModules = false;
			}
		}
		if (!$allModules) {
			$arResult['success'] = false;
			$arResult['result'][10] = Loc::getMessage('RZ_CORE_CATALOG_NO_NEED_MODULES');
			return $arResult;
		}
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$rsProducts = \CCatalogProduct::GetList(
			array(),
			array('ID' => $PRODUCT_ID),
			false,
			false,
			array(
				'ID',
				'CAN_BUY_ZERO',
				'QUANTITY_TRACE',
				'QUANTITY',
				'WEIGHT',
				'WIDTH',
				'HEIGHT',
				'LENGTH',
				'TYPE',
				'MEASURE'
			)
		);
		if (!($arCatalogProduct = $rsProducts->Fetch())) {
			$arResult['success'] = false;
			$arResult['result'][20] = Loc::getMessage('CATALOG_ERR_NO_PRODUCT');
			return $arResult;
		}
		$arCatalogProduct['MEASURE'] = (int)$arCatalogProduct['MEASURE'];
		$arCatalogProduct['MEASURE_NAME'] = '';
		$arCatalogProduct['MEASURE_CODE'] = 0;
		if ($arCatalogProduct['MEASURE'] <= 0) {
			/** @noinspection PhpUndefinedClassInspection */
			$arMeasure = \CCatalogMeasure::getDefaultMeasure(true, true);
			$arCatalogProduct['MEASURE_NAME'] = $arMeasure['~SYMBOL_RUS'];
			$arCatalogProduct['MEASURE_CODE'] = $arMeasure['CODE'];
		} else {
			$rsMeasures = \CCatalogMeasure::getList(
				array(),
				array('ID' => $arCatalogProduct['MEASURE']),
				false,
				false,
				array('ID', 'SYMBOL_RUS', 'CODE')
			);
			if ($arMeasure = $rsMeasures->GetNext()) {
				$arCatalogProduct['MEASURE_NAME'] = $arMeasure['~SYMBOL_RUS'];
				$arCatalogProduct['MEASURE_CODE'] = $arMeasure['CODE'];
			}
		}

		$dblQuantity = (float)$arCatalogProduct["QUANTITY"];
		$QUANTITY = (float)$QUANTITY;
		$boolQuantity = ($arCatalogProduct["CAN_BUY_ZERO"] != 'Y' && $arCatalogProduct["QUANTITY_TRACE"] == 'Y');
		if ($boolQuantity && $dblQuantity <= 0) {
			$arResult['success'] = false;
			$arResult['result'][21] = Loc::getMessage('CATALOG_ERR_PRODUCT_RUN_OUT');
			return $arResult;
		}
		if ($boolQuantity && ($QUANTITY > $dblQuantity)) {
			$arResult['success'] = false;
			$arResult['result'][22] = Loc::getMessage('RZ_CORE_CATALOG_PRODUCT_RUN_OUT');
			if ($bReturnQ) {
				$arResult['q'] = $dblQuantity;
			}
			return $arResult;
		}
		return $arResult;
	}

	public static function getMeasureOfProduct ($PRODUCT_ID = ''){
	    if (!Loader::includeModule('catalog')) return;

	    $cache_id = 'measure_of_'.$PRODUCT_ID;
	    $arReturn = array();

	    if ($arReturn = Tools::getSavedValues($cache_id)){
	        return $arReturn;
        }

        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $dbProduct = \CCatalogProduct::GetList(
            array(),
            array('ID' => $PRODUCT_ID),
            false,
            false,
            array(
                'ID',
                'CAN_BUY_ZERO',
                'QUANTITY_TRACE',
                'QUANTITY',
                'WEIGHT',
                'WIDTH',
                'HEIGHT',
                'LENGTH',
                'TYPE',
                'MEASURE',
                'ELEMENT_IBLOCK_ID'
            )
        );
        if (!($arCatalogProduct = $dbProduct->Fetch())) {
            return $arReturn;
        }

        $arFilter = array('ID' => $arCatalogProduct['MEASURE']);

        $dbMeasure = \CCatalogMeasure::getList(
            array(),
            $arFilter,
            false,
            false,
            array()
        );
        $arReturn = $dbMeasure->GetNext();

        Tools::saveSomeValuesInCache($arReturn,$cache_id,true,$arCatalogProduct['ELEMENT_IBLOCK_ID']);

        return $arReturn;
    }
	
	/**
	 * @param $siteId
	 * @return array
	 */
	public static function getSiteInfo($siteId = SITE_ID) {
		if (empty($siteId)) {
			return "";
		}
		$obCache = new \CPHPCache();
		if ($obCache->InitCache(3600, 'site_' . $siteId, '/romza/site/')) {
			$arSite = $obCache->GetVars();
		} elseif ($obCache->StartDataCache()) {
			$arSite = \CSite::GetByID($siteId)->Fetch();
			$obCache->EndDataCache($arSite);
		}
		return $arSite;
	}
}