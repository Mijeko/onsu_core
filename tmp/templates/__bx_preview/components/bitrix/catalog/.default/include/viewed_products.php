<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (Bitrix\Main\Loader::includeModule('catalog')):
    if ($arParams['OFFER']){
        $arPrepareParams['IBLOCK_ID'] = $arParams['IBLOCK_ID_CATALOG'];
    }
    if ($arParams['DETAIL_BASKET_POPUP']){
        $arPrepareParams['PAGE_ELEMENT_COUNT'] = $arParams['DETAIL_CNT_ELEMENTS_IN_SLIDERS'];
    }
$APPLICATION->IncludeComponent(
	"bitrix:catalog.viewed.products", 
	"bitronic2", 
	array(
		"HEADER_TEXT" => $arPrepareParams["VIEWED_TITLE"],
		"DISPLAY_COMPARE_SOLUTION" => $arPrepareParams["DISPLAY_COMPARE_SOLUTION"],
		"DISPLAY_FAVORITE" => $arPrepareParams["DISPLAY_FAVORITE"],
		"DISPLAY_ONECLICK" => $arPrepareParams["DISPLAY_ONECLICK"],
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => $arPrepareParams["IBLOCK_ID"],
		"={\"SHOW_PRODUCTS_\".\$arPrepareParams[\"IBLOCK_ID\"]}" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => $arPrepareParams["CACHE_TIME"],
		"BASKET_URL" => $arPrepareParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arPrepareParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arPrepareParams["PRODUCT_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arPrepareParams["PRODUCT_QUANTITY_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"PRODUCT_PROPS_VARIABLE" => $arPrepareParams["PRODUCT_PROPS_VARIABLE"],
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PAGE_ELEMENT_COUNT" => $arPrepareParams["PAGE_ELEMENT_COUNT"],
		"SHOW_OLD_PRICE" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"PRICE_CODE" => array(
			0 => "розничная интернет-магаз.",
		),
		"SHOW_PRICE_COUNT" => $arPrepareParams["SHOW_PRICE_COUNT"],
		"PRODUCT_SUBSCRIPTION" => "N",
		"PRICE_VAT_INCLUDE" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => $arPrepareParams["MESS_BTN_BUY"],
		"MESS_BTN_DETAIL" => $arPrepareParams["MESS_BTN_DETAIL"],
		"MESS_NOT_AVAILABLE" => $arPrepareParams["MESS_NOT_AVAILABLE"],
		"MESS_BTN_SUBSCRIBE" => $arPrepareParams["MESS_BTN_SUBSCRIBE"],
		"HIDE_NOT_AVAILABLE" => "N",
		"CART_PROPERTIES_{\$arPrepareParams['IBLOCK_ID']}" => $arPrepareParams["PRODUCT_PROPERTIES"],
		"={\"ADDITIONAL_PICT_PROP_\".\$arPrepareParams[\"IBLOCK_ID\"]}" => $arPrepareParams["ADD_PICT_PROP"],
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => $arPrepareParams["CURRENCY_ID"],
		"RESIZER_SECTION" => $arPrepareParams["RESIZER_SETS"]["RESIZER_SECTION"],
		"HOVER-MODE" => $arPrepareParams["HOVER-MODE"],
		"HIDE_ITEMS_NOT_AVAILABLE" => $arParams["HIDE_ITEMS_NOT_AVAILABLE"],
		"HIDE_ITEMS_ZER_PRICE" => $arParams["HIDE_ITEMS_ZER_PRICE"],
		"HIDE_ITEMS_WITHOUT_IMG" => $arParams["HIDE_ITEMS_WITHOUT_IMG"],
		"ORDER_VIEWED_PRODUCTS" => $arPrepareParams["ORDER_VIEWED_PRODUCTS"],
		"COMPONENT_TEMPLATE" => "bitronic2",
		"SHOW_FROM_SECTION" => "N",
		"SECTION_ID" => $GLOBALS["CATALOG_CURRENT_SECTION_ID"],
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => $GLOBALS["CATALOG_CURRENT_ELEMENT_ID"],
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "2",
		"DETAIL_URL" => "",
		"CACHE_GROUPS" => "Y",
		"SHOW_PRODUCTS_46" => "N",
		"PROPERTY_CODE_46" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_46" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_46" => "PHOTOS_FOR_VK_46",
		"LABEL_PROP_46" => "-",
		"SHOW_PRODUCTS_57" => "N",
		"PROPERTY_CODE_57" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_57" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_57" => "",
		"LABEL_PROP_57" => "-"
	),
	$component
);
endif;
