<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

if (\Bitrix\Main\Loader::IncludeModule('yenisite.core')) {
	$catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}

global $rz_b2_options;
$arParams['PRICE_CODE'] = $catalogParams['PRICE_CODE'] ?: array('BASE');
$arParams['STORES'] = NULL;

if(!empty($rz_b2_options['GEOIP']['PRICES'])) {
	$arParams["PRICE_CODE"] = $rz_b2_options['GEOIP']['PRICES'];
}
if(!empty($rz_b2_options['GEOIP']['STORES'])) {
	$arParams['STORES'] = $rz_b2_options['GEOIP']['STORES'];
}
if ($rz_b2_options['convert_currency']) {
	$arParams['CONVERT_CURRENCY'] = 'Y';
	$arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
}

global $arrFilterCompare;
$arrFilterCompare = array("ID" => empty($arParams['COMPARE_LIST']) ? 0 : array_keys($arParams['COMPARE_LIST']));

CRZBitronic2CatalogUtils::setFilterAvPrFoto($arrFilterCompare, $catalogParams);


$APPLICATION->IncludeComponent('bitrix:catalog.section', 'compare_list', array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"COMPONENT_TEMPLATE" => "compare_list",
		"SECTION_USER_FIELDS" => array(),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilterCompare",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "999",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => $catalogParams['LIST_PROPERTY_CODE'],
		"OFFERS_FIELD_CODE" => array("ID"),
		"OFFERS_LIMIT" => "5",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"COMPARE_URL" => $arParams['COMPARE_URL'],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"ADD_SECTIONS_CHAIN" => "N",
		"SET_STATUS_404" => "N",
		"CACHE_FILTER" => "Y",
		"ACTION_VARIABLE" => 'actions',
		"PRODUCT_ID_VARIABLE" => 'product',
        "ACTION_CATALOG_VARIABLE" => $catalogParams['ACTION_VARIABLE'],
        "PRODUCT_ID_CATALOG_VARIABLE" => $catalogParams['PRODUCT_ID_VARIABLE'],
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"BASKET_URL" => "/personal/cart/",
		"USE_PRODUCT_QUANTITY" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => $catalogParams['PRODUCT_PROPERTIES'],
		"PRICE_CODE" => $arParams['PRICE_CODE'],
		"DISPLAY_COMPARE_SOLUTION" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "", //there is no page navigation
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"RESIZER_SET" => $arParams["RESIZER_SET_COMPARE"],
		"SHOW_AMOUNT_STORE" => "Y",
		"SHOW_VOTING" => $arParams["SHOW_VOTING"],
		"STORE_DISPLAY_TYPE" => $rz_b2_options["store_amount_type"],
		"STORES" => $arParams["STORES"],
		"COMPARE_NAME" => $arParams["NAME"],
		"COMPARE_LIST" => $arParams["COMPARE_LIST"],

        //PARAMS FOR HIDE ITEMS
        'HIDE_ITEMS_NOT_AVAILABLE' => $catalogParams['HIDE_ITEMS_NOT_AVAILABLE'],
        'HIDE_ITEMS_ZER_PRICE' => $catalogParams['HIDE_ITEMS_ZER_PRICE'],
        'HIDE_ITEMS_WITHOUT_IMG' => $catalogParams['HIDE_ITEMS_WITHOUT_IMG'],
	),
	$component
);