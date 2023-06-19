<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (IsModuleInstalled('catalog')):
    if ($arParams['OFFER']){
        $arParams['IBLOCK_ID'] = $arParams['IBLOCK_ID_CATALOG'];
    }
$APPLICATION->IncludeComponent(
	"bitrix:catalog.bigdata.products",
	"bitronic2",
	Array(
		"DISPLAY_COMPARE_SOLUTION" => $arParams['DISPLAY_COMPARE_SOLUTION'],
		"DISPLAY_FAVORITE" => $arParams["DISPLAY_FAVORITE"],
		"DISPLAY_ONECLICK" => $arParams["DISPLAY_ONECLICK"],
		"RCM_TYPE" => "similar_sell",
		"ID" => $arResult["ID"],
		"IBLOCK_TYPE" => 'offers',
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
		"SHOW_OLD_PRICE" => "N",
		"PRICE_CODE" => $arParams['PRICE_CODE'],
		"SHOW_PRICE_COUNT" => $arParams['SHOW_PRICE_COUNT'],
		"PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams['CURRENCY_ID'],
		"BASKET_URL" => $arParams['BASKET_URL'],
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_PRODUCTS_{$arParams['IBLOCK_ID']}" => "Y",
		"PROPERTY_CODE_{$arParams['IBLOCK_ID']}" => $arParams['PROPERTY_CODE'],
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "2",
		"RESIZER_SECTION" => $arParams['RESIZER_SETS']['RESIZER_SECTION'],
		"POPUP" => true,
		"HOVER-MODE" => $arParams['HOVER-MODE'],
        "OFFER_TREE_PROPS_{$arParams['IBLOCK_ID']}" => $arParams['OFFER_TREE_PROPS'],
	)
);
endif;
