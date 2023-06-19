<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$bPrice = false;
$bSection = false;
$bAvailable = false;

// check params
$arSPP = $arParams['SIMILAR_PRICE_PROPERTIES'];

if (empty($arSPP) || !is_array($arSPP)) {
	$arSPP = array('---SECTION---', '---PRICE---');
}
if (($key = array_search('---PRICE---', $arSPP)) !== false) {
	$bPrice = true;
	unset($arSPP[$key]);
}
if (($key = array_search('---SECTION---', $arSPP)) !== false) {
	$bSection = true;
	unset($arSPP[$key]);
}
if (($key = array_search('---AVAILABLE---', $arSPP)) !== false) {
	$bAvailable = true;
	unset($arSPP[$key]);
}
unset($key);

// create filter
global $similarFilter;
$similarFilter = array(
	"!ID" => $arResult['ID']
);

// add filter by price
if ($bPrice) {
	if (CModule::IncludeModule('yenisite.bitronic2lite') && CModule::IncludeModule('yenisite.market')) {
		$priceName = 'PROPERTY_';
	} else {
		$priceName = 'CATALOG_PRICE_';
	}
	$priceName .= $arResult['MIN_PRICE']['PRICE_ID'];
	$priceValue = $arResult['MIN_PRICE']['VALUE'];
	if ($arResult['CATALOG_VAT_INCLUDED'] === 'Y' && $arResult['MIN_PRICE']['ORIG_VALUE_VAT']) {
		$priceValue = $arResult['MIN_PRICE']['ORIG_VALUE_VAT'];
	} elseif ($arResult['CATALOG_VAT_INCLUDED'] === 'N' && $arResult['MIN_PRICE']['ORIG_VALUE_NOVAT']) {
		$priceValue = $arResult['MIN_PRICE']['ORIG_VALUE_NOVAT'];
	}
	$similarFilter += array(
		">=".$priceName => $priceValue * (100 - $arParams['SIMILAR_PRICE_PERCENT']) / 100,
		"<=".$priceName => $priceValue * (100 + $arParams['SIMILAR_PRICE_PERCENT']) / 100,
	);
}

// add smart filter props to filter
if ($arParams['SIMILAR_PRICE_SMART_FILTER'] === 'Y') {
	$arSysProps = array('RZ_AVAILABLE');
	if (!empty($arParams['TAB_PROPERTY_NEW']))  $arSysProps[] = $arParams['TAB_PROPERTY_NEW'];
	if (!empty($arParams['TAB_PROPERTY_HIT']))  $arSysProps[] = $arParams['TAB_PROPERTY_HIT'];
	if (!empty($arParams['TAB_PROPERTY_SALE'])) $arSysProps[] = $arParams['TAB_PROPERTY_SALE'];
	if (!empty($arParams['TAB_PROPERTY_BESTSELLER'])) $arSysProps[] = $arParams['TAB_PROPERTY_BESTSELLER'];

	foreach ($arResult['smartFilterItems'] as $arSmartProp) {
		$propCode = $arSmartProp['CODE'];
		if (in_array($propCode, $arSysProps)) continue;

		$arSPP[] = $propCode;
	}
	array_unique($arSPP);
	unset($arSysProps);
}

// add filter by iblock props
if (count($arSPP) > 35) {
    $arSPP = array_slice($arSPP, 0, 35);
}
foreach ($arSPP as $propCode) {
	$arProp = &$arResult['PROPERTIES'][$propCode];
	$propValue = $arProp['VALUE_ENUM_ID'] ?: $arProp['VALUE'];

	$arValue = array();
	if ($arProp['MULTIPLE'] === 'Y' && is_array($propValue)) {
		foreach ($propValue as $oneValue) {
           $arValue[] = array('ID' =>CIBlockElement::SubQuery("ID", array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "PROPERTY_".$propCode => $oneValue)));
		}
	} elseif(!empty($propValue)) {
		$arValue['=PROPERTY_'.$propCode] = $propValue;
	}
	if (!empty($arValue)) {
		if ($arParams['SIMILAR_PRICE_WITH_EMPTY_PROPS'] === 'Y') {
			$similarFilter[] = array(
				'LOGIC' => 'OR',
				$arValue,
				array('PROPERTY_'.$propCode => false)
			);
		} elseif (array_key_exists(0, $arValue)) {
			$similarFilter[] = $arValue;
		} else {
			$similarFilter += $arValue;
		}
	}
	unset($arValue, $propValue, $oneValue);
}
unset($arSPP);

//add filter by availability
if ($bAvailable) {
	if (CRZBitronic2Settings::getEdition() == 'LITE') {
		if (CModule::IncludeModule('yenisite.market') && CMarketCatalog::UsesQuantity($arParams['IBLOCK_ID'])) {
			$similarFilter['>PROPERTY_MARKET_QUANTITY'] = 0;
		}
	} else {
		$similarFilter['CATALOG_AVAILABLE'] = 'Y';
	}
}
CRZBitronic2CatalogUtils::setFilterAvPrFoto($similarFilter, $arParams);

if ($arParams['OFFER']){
    $arParams['IBLOCK_ID'] = $arParams['IBLOCK_ID_CATALOG'];
}

$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"detail_slider",
	Array(
		"HEADER_TEXT" => $arParams['SIMILAR_PRICE_TITLE'],
		"DISPLAY_COMPARE_SOLUTION" => $arParams['DISPLAY_COMPARE_SOLUTION'],
		"DISPLAY_FAVORITE" => $arParams["DISPLAY_FAVORITE"],
		"DISPLAY_ONECLICK" => $arParams["DISPLAY_ONECLICK"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => $bSection ? $arResult["IBLOCK_SECTION_ID"] : 0,
		"SHOW_ALL_WO_SECTION" => "Y",
		"FILTER_NAME" => "similarFilter",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
		"SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRODUCT_SUBSCRIPTION" => 'N',
		"DISPLAY_TOP_PAGER" => 'N',
		"DISPLAY_BOTTOM_PAGER" => 'N',
        "PAGE_ELEMENT_COUNT" => $arParams['DETAIL_CNT_ELEMENTS_IN_SLIDERS'],
		"OFFERS_SORT_FIELD" => "CATALOG_AVAILABLE",
		"OFFERS_SORT_ORDER" => "desc",
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
		"MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
		"MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
		"MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		"PRODUCT_PROPERTIES" => $arParams['PRODUCT_PROPERTIES'],
		// "ADD_PICT_PROP" => $arParams['ADD_PICT_PROP'],
		// 'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		// 'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		// "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => array(
			0 => "ID",
		),
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"RESIZER_SECTION" => $arParams['RESIZER_SETS']['RESIZER_SECTION'],
		"HOVER-MODE" => $arParams["HOVER-MODE"],

        //PARAMS FOR HIDE ITEMS
        'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
        'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
        'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],
        'ORDER_DETAIL_BLOCKS' => $arParams['ORDER_DETAIL_BLOCKS']['order-sPrSimilarProducts'],
        'NAME_ORDER_BLOCK' => 'sPrSimilarProducts',
        'SHOW_SIMILAR_ORG' => true
	),
	$component
);
