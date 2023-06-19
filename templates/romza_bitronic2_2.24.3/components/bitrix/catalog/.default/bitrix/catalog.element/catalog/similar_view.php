<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (Bitrix\Main\Loader::includeModule('catalog')):
    if ($arParams['OFFER']){
        $arParams['IBLOCK_ID'] = $arParams['IBLOCK_ID_CATALOG'];
    }
$APPLICATION->IncludeComponent(
	"bitrix:catalog.bigdata.products",
	"bitronic2",
	Array(
		"HEADER_TEXT" => $arParams['SIMILAR_VIEW_TITLE'],
		"DISPLAY_COMPARE_SOLUTION" => $arParams['DISPLAY_COMPARE_SOLUTION'],
		"DISPLAY_FAVORITE" => $arParams["DISPLAY_FAVORITE"],
		"DISPLAY_ONECLICK" => $arParams["DISPLAY_ONECLICK"],
		"RCM_TYPE" => "similar_view",
		"ID" => $arResult["ID"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SHOW_FROM_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => $arParams['SLIDERS_HIDE_NOT_AVAILABLE'],
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
        "PAGE_ELEMENT_COUNT" => $arParams['DETAIL_CNT_ELEMENTS_IN_SLIDERS'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
		"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
		"PRICE_CODE" => $arParams['PRICE_CODE'],
		"SHOW_PRICE_COUNT" => $arParams['SHOW_PRICE_COUNT'],
		"PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams['CURRENCY_ID'],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_PRODUCTS_{$arParams['IBLOCK_ID']}" => "Y",
		"PROPERTY_CODE_{$arParams['IBLOCK_ID']}" => $arParams['PROPERTY_CODE'],
		"CART_PROPERTIES_{$arParams['IBLOCK_ID']}" => $arParams['PRODUCT_PROPERTIES'],
        "SECTION_ID" => $arParams['BIGDATA_SECTION'] ? $arResult['IBLOCK_SECTION_ID'] : '',
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "2",
		"RESIZER_SECTION" => $arParams['RESIZER_SETS']['RESIZER_SECTION'],
		"HOVER-MODE" => $arParams["HOVER-MODE"],

        //PARAMS FOR HIDE ITEMS
        'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
        'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
        'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],
        'POPUP' => $arParams['POPUP'],
        'ORDER_DETAIL_BLOCKS' => $arParams['ORDER_DETAIL_BLOCKS']['order-sPrSimilarView'],
        'NAME_ORDER_BLOCK' => 'sPrSimilarView',
        'SHOW_SIMILAR_ORG' => true
	),
	$component
);
endif;
