<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Type\Collection;

// AJAX PATH
$ajaxPath = SITE_DIR."ajax/catalog.php";
$ajaxPathCompare = SITE_DIR."ajax/compare.php";
$ajaxPathFavorite = SITE_DIR."ajax/favorites.php";
$arResult['ADD_URL_TEMPLATE'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";
$arResult['BUY_URL_TEMPLATE'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";

$arParams['ADD_PROPERTIES_TO_BASKET'] = (isset($arParams['ADD_PROPERTIES_TO_BASKET']) && $arParams['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'N' : 'Y');
if ('N' == $arParams['ADD_PROPERTIES_TO_BASKET'])
{
	$arParams["PRODUCT_PROPERTIES"] = array();
	$arParams["OFFERS_CART_PROPERTIES"] = array();
}
$arParams['PARTIAL_PRODUCT_PROPERTIES'] = (isset($arParams['PARTIAL_PRODUCT_PROPERTIES']) && $arParams['PARTIAL_PRODUCT_PROPERTIES'] === 'Y' ? 'Y' : 'N');
if(!is_array($arParams["PRODUCT_PROPERTIES"]))
	$arParams["PRODUCT_PROPERTIES"] = array();
foreach($arParams["PRODUCT_PROPERTIES"] as $k=>$v)
	if($v==="")
		unset($arParams["PRODUCT_PROPERTIES"][$k]);

if (!is_array($arParams["OFFERS_CART_PROPERTIES"]))
	$arParams["OFFERS_CART_PROPERTIES"] = array();
foreach($arParams["OFFERS_CART_PROPERTIES"] as $i => $pid)
	if ($pid === "")
		unset($arParams["OFFERS_CART_PROPERTIES"][$i]);

$bGetProductProperties = ($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y'  && !empty($arParams["PRODUCT_PROPERTIES"]));

$baseCurrency = 'RUB';
if (CModule::IncludeModule('currency') || CModule::IncludeModule('sale')) {
	$baseCurrency = CCurrency::GetBaseCurrency();
}

if (CModule::IncludeModule('yenisite.market')) {
	$arResult['CHECK_QUANTITY'] = (CMarketCatalog::UsesQuantity($arParams['IBLOCK_ID']) == 1);
}

$bCatalog = CModule::IncludeModule('catalog');

if ($bCatalog) {
	$arDefaultMeasure = CCatalogMeasure::getDefaultMeasure(true, true);
	$arMeasureMap = array();
}
$arResult['ELEMENTS'] = array();
$arElementLink = array();

foreach ($arResult['ITEMS'] as $key => &$arItem)
{
	$arItem = CRZBitronic2CatalogUtils::processItemCommon($arItem);


	if(!empty($arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT']))
	{
		$imgAlt = $arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'];
	}
	else
	{
		$imgAlt = $arItem['NAME'];
	}
	$arItem['PICTURE_PRINT']['ALT'] = $imgAlt;
	$arItem['PICTURE_PRINT']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['ID'], $arParams['RESIZER_SECTION']);
	
	if ($bCatalog) {
		$arItem['bOffers'] = CCatalogSKU::IsExistOffers($arItem['ID'], $arItem['IBLOCK_ID']);
	} else {
		//Prices for MARKET
		if (CModule::IncludeModule('yenisite.bitronic2lite') && CModule::IncludeModule('yenisite.market')) {
			$prices = CMarketPrice::GetItemPriceValues($arItem['ID'], $arItem['PRICES']);
			if (count($prices) > 0) {
				unset($arItem['PRICES']);
			}
			$minPrice = false;
			foreach ($prices as $k => $pr) {
				$pr = floatval($pr);
				$arItem['PRICES'][$k]['VALUE'] = $pr;
				$arItem['PRICES'][$k]['PRINT_VALUE'] = $pr;
				if ((empty($minPrice) || $minPrice > $pr) && $pr > 0) {
					$minPrice = $pr;
				}
			}
			if ($minPrice !== false) {
				$arItem['MIN_PRICE']['VALUE'] = $minPrice;
				$arItem['MIN_PRICE']['PRINT_VALUE'] = $minPrice;
				$arItem['MIN_PRICE']['DISCOUNT_VALUE'] = $minPrice;
				$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] = $minPrice;
				$arItem['CATALOG_MEASURE_RATIO'] = 1;
				$arItem['CAN_BUY'] = true;
			}
			$arItem['CHECK_QUANTITY'] = $arResult['CHECK_QUANTITY'];
			$arItem['CATALOG_QUANTITY'] = CMarketCatalogProduct::GetQuantity($arItem['ID'], $arItem['IBLOCK_ID']);
			
			if ($arItem['CHECK_QUANTITY'] && $arItem['CATALOG_QUANTITY'] <= 0) {
				$arItem['CAN_BUY'] = false;
			}
			$arItem['CATALOG_TYPE'] = 1; //simple product
		}
		//end Prices for MARKET
	}

	if ($arItem['bOffers'] && empty($arItem['MIN_PRICE']) && floatval($arItem['PROPERTIES']['MINIMUM_PRICE']['VALUE']) > 0) {
		$arItem['MIN_PRICE'] = array(
			'CURRENCY' => $baseCurrency,
			'DISCOUNT_VALUE' => floatval($arItem['PROPERTIES']['MINIMUM_PRICE']['VALUE'])
			);
		if ($arParams['CONVERT_CURRENCY'] === 'Y' && $arParams['CURRENCY_ID'] != $baseCurrency && CModule::IncludeModule('currency')) {
			$arItem['MIN_PRICE']['ORIG_CURRENCY'] = $arItem['MIN_PRICE']['CURRENCY'];
			$arItem['MIN_PRICE']['ORIG_DISCOUNT_VALUE'] = $arItem['MIN_PRICE']['DISCOUNT_VALUE'];
			$arItem['MIN_PRICE']['CURRENCY'] = $arParams['CURRENCY_ID'];
			$arItem['MIN_PRICE']['DISCOUNT_VALUE'] = CCurrencyRates::ConvertCurrency($arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['ORIG_CURRENCY'], $arItem['MIN_PRICE']['CURRENCY']);
		}
		//echo '<pre>', var_export($arItem, 1), '</pre>';
	}
	
	$arItem['ON_REQUEST'] = (empty($arItem['MIN_PRICE']) || $arItem['MIN_PRICE']['VALUE'] <= 0);
	if ($arItem['ON_REQUEST']) {
		$arItem['CAN_BUY'] = false;
	}

    if (CRZBitronic2CatalogUtils::checkAvPrFotoForElement($arItem, $arParams)){
        unset($arResult['ITEMS'][$key]);
        continue;
    }

	//---MEASURES---
	if ($bCatalog) {
		if (!isset($arItem["CATALOG_MEASURE_RATIO"]))
			$arItem["CATALOG_MEASURE_RATIO"] = 1;
		if (!isset($arItem['CATALOG_MEASURE']))
			$arItem['CATALOG_MEASURE'] = 0;
		$arItem['CATALOG_MEASURE'] = (int)$arItem['CATALOG_MEASURE'];
		if (0 > $arItem['CATALOG_MEASURE'])
			$arItem['CATALOG_MEASURE'] = 0;
		if (!isset($arItem['CATALOG_MEASURE_NAME']))
			$arItem['CATALOG_MEASURE_NAME'] = '';

		$arItem['CATALOG_MEASURE_NAME'] = $arDefaultMeasure['SYMBOL_RUS'];
		$arItem['~CATALOG_MEASURE_NAME'] = $arDefaultMeasure['~SYMBOL_RUS'];
		if (0 < $arItem['CATALOG_MEASURE'])
		{
			if (!isset($arMeasureMap[$arItem['CATALOG_MEASURE']]))
				$arMeasureMap[$arItem['CATALOG_MEASURE']] = array();
			$arMeasureMap[$arItem['CATALOG_MEASURE']][] = $key;
		}
	}
	//---MEASURES---

	$arResult['ELEMENTS'][$key] = $arItem['ID'];
	$arElementLink[$arItem['ID']] = &$arResult["ITEMS"][$key];
}
if (isset($arItem))
	unset($arItem);

if ($bGetProductProperties) {
	$arPropFilter = array(
		'ID' => $arResult["ELEMENTS"],
		'IBLOCK_ID' => $arParams['IBLOCK_ID']
	);
	CIBlockElement::GetPropertyValuesArray($arElementLink, $arParams["IBLOCK_ID"], $arPropFilter);

	foreach ($arResult['ITEMS'] as &$arItem) {
		$arItem["PRODUCT_PROPERTIES"] = CIBlockPriceTools::GetProductProperties(
			$arParams["IBLOCK_ID"],
			$arItem["ID"],
			$arParams["PRODUCT_PROPERTIES"],
			$arItem["PROPERTIES"]
		);
		if (!empty($arItem["PRODUCT_PROPERTIES"]))
		{
			$arItem['PRODUCT_PROPERTIES_FILL'] = CIBlockPriceTools::getFillProductProperties($arItem['PRODUCT_PROPERTIES']);
		}
	}
	if (isset($arItem))
		unset($arItem);
}

if ($bCatalog) {
	//---MEASURES-2---
	if (!empty($arResult["ELEMENTS"]))
	{
		$rsRatios = CCatalogMeasureRatio::getList(
			array(),
			array('PRODUCT_ID' => $arResult["ELEMENTS"]),
			false,
			false,
			array('PRODUCT_ID', 'RATIO')
		);
		while ($arRatio = $rsRatios->Fetch())
		{
			$arRatio['PRODUCT_ID'] = (int)$arRatio['PRODUCT_ID'];
			if (isset($arElementLink[$arRatio['PRODUCT_ID']]))
			{
				$intRatio = (int)$arRatio['RATIO'];
				$dblRatio = doubleval($arRatio['RATIO']);
				$mxRatio = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
				if (CATALOG_VALUE_EPSILON > abs($mxRatio))
					$mxRatio = 1;
				elseif (0 > $mxRatio)
					$mxRatio = 1;
				$arElementLink[$arRatio['PRODUCT_ID']]['CATALOG_MEASURE_RATIO'] = $mxRatio;
			}
		}
	}
	if (!empty($arMeasureMap))
	{
		$rsMeasures = CCatalogMeasure::getList(
			array(),
			array('@ID' => array_keys($arMeasureMap)),
			false,
			false,
			array('ID', 'SYMBOL_RUS')
		);
		while ($arMeasure = $rsMeasures->GetNext())
		{
			$arMeasure['ID'] = (int)$arMeasure['ID'];
			if (isset($arMeasureMap[$arMeasure['ID']]) && !empty($arMeasureMap[$arMeasure['ID']]))
			{
				foreach ($arMeasureMap[$arMeasure['ID']] as &$itemId)
				{
					$arElementLink[$itemId]['CATALOG_MEASURE_NAME'] = $arMeasure['SYMBOL_RUS'];
					$arElementLink[$itemId]['~CATALOG_MEASURE_NAME'] = $arMeasure['~SYMBOL_RUS'];
				}
				unset($itemId);
			}
		}
	}
}	//---MEASURES-2---
unset($arElementLink);

//echo '<pre>', htmlspecialcharsBx(var_export($arParams,1)), '</pre>';

$arResult['ALL_FIELDS'] = array();
$existShow = !empty($arResult['SHOW_FIELDS']);
$existDelete = !empty($arResult['DELETED_FIELDS']);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_FIELDS'] as $propCode)
		{
			$arResult['SHOW_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'N',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode);
		$arResult['ALL_FIELDS'] = $arResult['SHOW_FIELDS'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_FIELDS'] as $propCode)
		{
			$arResult['ALL_FIELDS'][$propCode] = array(
				'CODE' => $propCode,
				'IS_DELETED' => 'Y',
				'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		// unset($propCode, $arResult['DELETED_FIELDS']);
	}
	Collection::sortByColumn($arResult['ALL_FIELDS'], array('SORT' => SORT_ASC));
}

$arResult['ALL_PROPERTIES'] = array();
$existShow = !empty($arResult['SHOW_PROPERTIES']);
$existDelete = !empty($arResult['DELETED_PROPERTIES']);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		$arResult['PROPS_WITHOUT_GROUPS'] = array();

		foreach ($arResult['SHOW_PROPERTIES'] as $propCode => $arProp)
		{
			if ($arResult['DIFFERENT'])
			{
				$arCompare = array();
				foreach($arResult["ITEMS"] as &$arItem)
				{
					$arPropertyValue = $arItem["DISPLAY_PROPERTIES"][$propCode]["VALUE"];
					if (is_array($arPropertyValue))
					{
						sort($arPropertyValue);
						$arPropertyValue = implode(" / ", $arPropertyValue);
					}
					$arCompare[] = $arPropertyValue;
				}
				unset($arItem);
				$showRow = (count(array_unique($arCompare)) > 1);
				if ($showRow == false) {
					unset($arResult['SHOW_PROPERTIES'][$propCode]);
					continue;
				}
			}
			$arResult['SHOW_PROPERTIES'][$propCode]['IS_DELETED'] = 'N';
			$arResult['SHOW_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_PROPERTY_TEMPLATE']);
			$arResult['PROPS_WITHOUT_GROUPS'][$arProp['ID']] = $propCode;
		}
		$arResult['ALL_PROPERTIES'] = $arResult['SHOW_PROPERTIES'];

		if (CModule::IncludeModule('yenisite.infoblockpropsplus')) {
			$arInitArray = CYenisiteInfoblockpropsplus::GetInitArray(array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'SECTION_ID' => 0));
			foreach ($arInitArray['PROPS_TO_GROUPS'] as $group) {
				$firstInGroup = true;
				foreach ($group as $link) {
					if (empty($link['PROPERTY_ID']) || !isset($arResult['PROPS_WITHOUT_GROUPS'][$link['PROPERTY_ID']])) continue;
					if ($firstInGroup) {
						$firstInGroup = false;
						$arResult['SHOW_PROPERTIES'][$link['GROUP_ID']] = array('GROUP_NAME' => $link['GROUP_NAME']);
					}
					$propCode = $arResult['PROPS_WITHOUT_GROUPS'][$link['PROPERTY_ID']];
					$arProp = $arResult['SHOW_PROPERTIES'][$propCode];
					unset($arResult['SHOW_PROPERTIES'][$propCode]);
					unset($arResult['PROPS_WITHOUT_GROUPS'][$link['PROPERTY_ID']]);
					$arResult['SHOW_PROPERTIES'][$propCode] = $arProp;
					$arResult['HAS_GROUPS'] = true;
				}
			}
		}
	}
	unset($arProp, $propCode);
	if ($existDelete)
	{
		foreach ($arResult['DELETED_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult['DELETED_PROPERTIES'][$propCode]['IS_DELETED'] = 'Y';
			$arResult['DELETED_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_PROPERTY_TEMPLATE']);
			$arResult['ALL_PROPERTIES'][$propCode] = $arResult['DELETED_PROPERTIES'][$propCode];
		}
		// unset($arProp, $propCode, $arResult['DELETED_PROPERTIES']);
	}
	Collection::sortByColumn($arResult["ALL_PROPERTIES"], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
}

$arResult["ALL_OFFER_FIELDS"] = array();
$existShow = !empty($arResult["SHOW_OFFER_FIELDS"]);
$existDelete = !empty($arResult["DELETED_OFFER_FIELDS"]);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult["SHOW_OFFER_FIELDS"] as $propCode)
		{
			$arResult["SHOW_OFFER_FIELDS"][$propCode] = array(
				"CODE" => $propCode,
				"IS_DELETED" => "N",
				"ACTION_LINK" => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode);
		$arResult['ALL_OFFER_FIELDS'] = $arResult['SHOW_OFFER_FIELDS'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_OFFER_FIELDS'] as $propCode)
		{
			$arResult['ALL_OFFER_FIELDS'][$propCode] = array(
				"CODE" => $propCode,
				"IS_DELETED" => "Y",
				"ACTION_LINK" => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_FIELD_TEMPLATE']),
				'SORT' => $arResult['FIELDS_SORT'][$propCode]
			);
		}
		unset($propCode, $arResult['DELETED_OFFER_FIELDS']);
	}
	Collection::sortByColumn($arResult['ALL_OFFER_FIELDS'], array('SORT' => SORT_ASC));
}

$arResult['ALL_OFFER_PROPERTIES'] = array();
$existShow = !empty($arResult["SHOW_OFFER_PROPERTIES"]);
$existDelete = !empty($arResult["DELETED_OFFER_PROPERTIES"]);
if ($existShow || $existDelete)
{
	if ($existShow)
	{
		foreach ($arResult['SHOW_OFFER_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult["SHOW_OFFER_PROPERTIES"][$propCode]["IS_DELETED"] = "N";
			$arResult["SHOW_OFFER_PROPERTIES"][$propCode]["ACTION_LINK"] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_PROPERTY_TEMPLATE']);
		}
		unset($arProp, $propCode);
		$arResult['ALL_OFFER_PROPERTIES'] = $arResult['SHOW_OFFER_PROPERTIES'];
	}
	if ($existDelete)
	{
		foreach ($arResult['DELETED_OFFER_PROPERTIES'] as $propCode => $arProp)
		{
			$arResult["DELETED_OFFER_PROPERTIES"][$propCode]["IS_DELETED"] = "Y";
			$arResult["DELETED_OFFER_PROPERTIES"][$propCode]["ACTION_LINK"] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_PROPERTY_TEMPLATE']);
			$arResult['ALL_OFFER_PROPERTIES'][$propCode] = $arResult["DELETED_OFFER_PROPERTIES"][$propCode];
		}
		unset($arProp, $propCode, $arResult['DELETED_OFFER_PROPERTIES']);
	}
	Collection::sortByColumn($arResult['ALL_OFFER_PROPERTIES'], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
}
?>