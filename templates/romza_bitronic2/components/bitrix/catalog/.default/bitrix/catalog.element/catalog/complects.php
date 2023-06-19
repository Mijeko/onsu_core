<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $complectsFilter;
$complectsFilter = array(
    'ID' => $arResult['ITEMS_IN_SET']
);

CRZBitronic2CatalogUtils::setFilterAvPrFoto($complectsFilter, $arParams);

$APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    "complects",
    Array(
        "HEADER_TEXT" => $arParams['COMPLECTS_HEADER'],
        "DISPLAY_COMPARE_SOLUTION" => $arParams['DISPLAY_COMPARE_SOLUTION'],
        "DISPLAY_FAVORITE" => $arParams["DISPLAY_FAVORITE"],
        "DISPLAY_ONECLICK" => $arParams["DISPLAY_ONECLICK"],
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        //"ELEMENT_ID" => $arResult["ID"],
        "SECTION_ID" => $bSection ? $arResult["IBLOCK_SECTION_ID"] : 0,
        "SHOW_ALL_WO_SECTION" => "Y",
        "FILTER_NAME" => "complectsFilter",
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
        "PAGE_ELEMENT_COUNT" => 99999999,
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
        "RESIZER_COMPLECTS" => $arParams['RESIZER_SETS']['RESIZER_COMPLECTS'],
        "HOVER-MODE" => $arParams["HOVER-MODE"],

        //PARAMS FOR HIDE ITEMS
        'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
        'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
        'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],
    ),
    $component
);
