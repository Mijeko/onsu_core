<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @var array $arPrepareParams - you should set keys "HEADER_TEXT" and "RCM_TYPE" explicitly
 * @var CBitrixComponent $component
 */
if ($arParams['DETAIL_BASKET_POPUP']){
    $arPrepareParams['PAGE_ELEMENT_COUNT'] = $arParams['DETAIL_CNT_ELEMENTS_IN_SLIDERS'];
}
if (Bitrix\Main\Loader::includeModule('catalog')):
$APPLICATION->IncludeComponent(
	"bitrix:catalog.bigdata.products",
	"bitronic2",
	Array(
		"HEADER_TEXT" => $arPrepareParams["HEADER_TEXT"],
		"DISPLAY_COMPARE_SOLUTION" => $arPrepareParams['DISPLAY_COMPARE_SOLUTION'],
		"DISPLAY_FAVORITE" => $arPrepareParams["DISPLAY_FAVORITE"],
		"DISPLAY_ONECLICK" => $arPrepareParams["DISPLAY_ONECLICK"],
		"RCM_TYPE" => $arPrepareParams["RCM_TYPE"],
		"ID" => "",
		"IBLOCK_TYPE" => $arPrepareParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arPrepareParams["IBLOCK_ID"],
		"SHOW_FROM_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => $arPrepareParams["SLIDERS_HIDE_NOT_AVAILABLE"],
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"PAGE_ELEMENT_COUNT" => $arPrepareParams['PAGE_ELEMENT_COUNT'] ? : 20,
		"CACHE_TYPE" => $arPrepareParams['CACHE_TYPE'],
		"CACHE_TIME" => $arPrepareParams['CACHE_TIME'],
		"CACHE_GROUPS" => $arPrepareParams['CACHE_GROUPS'],
		"SHOW_OLD_PRICE" => $arPrepareParams['SHOW_OLD_PRICE'],
		"PRICE_CODE" => $arPrepareParams['PRICE_CODE'],
		"SHOW_PRICE_COUNT" => $arPrepareParams['SHOW_PRICE_COUNT'],
		"PRICE_VAT_INCLUDE" => $arPrepareParams['PRICE_VAT_INCLUDE'],
		"CONVERT_CURRENCY" => $arPrepareParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arPrepareParams['CURRENCY_ID'],
		"BASKET_URL" => $arPrepareParams['BASKET_URL'],
		"ACTION_VARIABLE" => $arPrepareParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arPrepareParams["PRODUCT_ID_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => $arPrepareParams["ADD_PROPERTIES_TO_BASKET"],
		"PRODUCT_PROPS_VARIABLE" => $arPrepareParams["PRODUCT_PROPS_VARIABLE"],
		"PARTIAL_PRODUCT_PROPERTIES" => $arPrepareParams["PARTIAL_PRODUCT_PROPERTIES"],
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_PRODUCTS_{$arPrepareParams['IBLOCK_ID']}" => "Y",
		"PROPERTY_CODE_{$arPrepareParams['IBLOCK_ID']}" => $arPrepareParams['PROPERTY_CODE'],
		"CART_PROPERTIES_{$arPrepareParams['IBLOCK_ID']}" => $arPrepareParams['PRODUCT_PROPERTIES'],
        "SECTION_ID" => $arParams['BIGDATA_SECTION'] ? $arResult['IBLOCK_SECTION_ID'] : '',
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "2",
		"RESIZER_SECTION" => $arPrepareParams['RESIZER_SETS']['RESIZER_SECTION'],
		"HOVER-MODE" => $arPrepareParams["HOVER-MODE"],
        'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
        'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
        'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],
	),
	$component
);
endif;
