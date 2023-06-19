<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;
use \Bitronic2\Mobile;

global $rz_b2_options;

$APPLICATION->IncludeComponent(
	"bitrix:catalog.bigdata.products", 
	"catalog_hits", 
	array(
		// BITRONIC 2 RELATED
		"RESIZER_SECTION" => $arParams["RESIZER_SECTION"],
		"SHOW_HITS" => $arParams["SECTION_SHOW_HITS"],
		"SHOW_MINIFIED" => ($_COOKIE['RZ_show_hits_catalog'] === 'false'),
		//"COMPONENT_TEMPLATE" => "bitronic2",
		// MAIN PARAMETERS
		"RCM_TYPE" => $arParams["SECTION_HITS_RCM_TYPE"],//bestsell
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		// DATA SOURCE
		"SHOW_FROM_SECTION" => "Y",
        "SECTION_ID" => $arParams['BIGDATA_SECTION'] ? $arResult['IBLOCK_SECTION_ID'] : '',
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"DEPTH" => "2",
		"HIDE_NOT_AVAILABLE" => $arParams["SECTION_HITS_HIDE_NOT_AVAILABLE"],//"Y"
		// VISUAL
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => GetMessage('BITRONIC2_BUTTON_BUY'),
		"MESS_BTN_DETAIL" => GetMessage('BITRONIC2_BUTTON_DETAIL'),
		"MESS_BTN_SUBSCRIBE" => GetMessage('BITRONIC2_BUTTON_SUBSCRIBE'),
		"PAGE_ELEMENT_COUNT" => $arParams['CATALOG_ELEMENT_HITS_COUNT'] ? : 4,
		// CACHE
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
		// PRICES
		"SHOW_OLD_PRICE" => "N",
		"PRICE_CODE" => $arParams['PRICE_CODE'],
		"SHOW_PRICE_COUNT" => $arParams['SHOW_PRICE_COUNT'],
		"PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams['CURRENCY_ID'],
		// BASKET
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"USE_PRODUCT_QUANTITY" => "N",
		// CATALOG PRODUCTS
		"SHOW_PRODUCTS_{$arParams['IBLOCK_ID']}" => "Y",
		"PROPERTY_CODE_{$arParams['IBLOCK_ID']}" => $arParams["LIST_PROPERTY_CODE"],
		"CART_PROPERTIES_{$arParams['IBLOCK_ID']}" => $arParams['PRODUCT_PROPERTIES'],
		'DISPLAY_FAVORITE' => $rz_b2_options['block_show_favorite'] === 'Y' && Loader::includeModule('yenisite.favorite'),
		'DISPLAY_ONECLICK' => $rz_b2_options['block_show_oneclick'] === 'Y' && Loader::includeModule('yenisite.oneclick'),
		"DISPLAY_COMPARE_SOLUTION" => $rz_b2_options['block_show_compare'] == 'Y',
		'SHOW_STARS' => $rz_b2_options['block_show_stars'],
		'HOVER-MODE' => Mobile::isMobile() ? 'border-n-shadow' : $rz_b2_options['product-hover-effect'],
        'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
        'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
        'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],
	),
	$component
);?>