<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (Bitrix\Main\Loader::includeModule('catalog')):
    if ($arParams['OFFER']){
        $arParams['IBLOCK_ID'] = $arParams['IBLOCK_ID_CATALOG'];
    }
$APPLICATION->IncludeComponent(
	"bitrix:catalog.recommended.products",
	"bitronic2",
	Array(
		"HEADER_TEXT" => $arParams['RECOMMENDED_TITLE'],
		"DISPLAY_COMPARE_SOLUTION" => $arParams['DISPLAY_COMPARE_SOLUTION'],
		"DISPLAY_FAVORITE" => $arParams["DISPLAY_FAVORITE"],
		"DISPLAY_ONECLICK" => $arParams["DISPLAY_ONECLICK"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ID" => $arResult['ID'],
		"PROPERTY_LINK" => "RECOMMEND",
		"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",///////////
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
        "PAGE_ELEMENT_COUNT" => $arParams['DETAIL_CNT_ELEMENTS_IN_SLIDERS'],
		"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
		"SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRODUCT_SUBSCRIPTION" => 'N',
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"CART_PROPERTIES_{$arParams['IBLOCK_ID']}" => $arParams['PRODUCT_PROPERTIES'],
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
		"MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
		"MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
		"MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
		"HIDE_NOT_AVAILABLE" => $arParams["SLIDERS_HIDE_NOT_AVAILABLE"],
		//"OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
		//"OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
		"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => $arParams['ADD_PICT_PROP'],
		//"ADDITIONAL_PICT_PROP_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams['OFFER_ADD_PICT_PROP'],
		//"PROPERTY_CODE_".$arRecomData['OFFER_IBLOCK_ID'] => array(),
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"RESIZER_SECTION" => $arParams['RESIZER_SETS']['RESIZER_SECTION'],
		"HOVER-MODE" => $arParams["HOVER-MODE"],

        //PARAMS FOR HIDE ITEMS
        'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
        'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
        'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],
        'ORDER_DETAIL_BLOCKS' => $arParams['ORDER_DETAIL_BLOCKS']['order-sPrRecommended'],
        'NAME_ORDER_BLOCK' => 'sPrRecommended',
	),
	$component
);
endif;
