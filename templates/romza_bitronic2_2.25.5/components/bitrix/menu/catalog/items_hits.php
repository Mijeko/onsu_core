<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $rz_b2_options, $arrFilterByAvPrFoto;

$rzHitIblockId = $arResult["ALL_ITEMS"][$idItem_1]['PARAMS']['FILTER']["IBLOCK_ID"];
$catalogParams = array();

if (\Bitrix\Main\Loader::IncludeModule('yenisite.core')) {
    $catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}
CRZBitronic2CatalogUtils::setFilterAvPrFoto($arrFilterByAvPrFoto, $catalogParams);


if ($arParams['HITS_COMPONENT'] != 'BIGDATA') {

	$containerId = 'menu_hits_' . $idItem_1;

	if ($_REQUEST['RZ_B2_AJAX_MENU_HITS'] === 'Y') {

		switch ($arParams['HITS_TYPE']) {
			case 'NEW':  $arSort = array('field' => 'PROPERTY_NEW',        'field2' => 'date_create', 'order2' => 'desc'); break;
			case 'HIT':  $arSort = array('field' => 'PROPERTY_HIT',        'field2' => 'shows',       'order2' => 'desc'); break;
			case 'SALE': $arSort = array('field' => 'PROPERTY_SALE',       'field2' => 'shows',       'order2' => 'desc'); break;
			case 'RECS': $arSort = array('field' => 'PROPERTY_BESTSELLER', 'field2' => 'shows',       'order2' => 'desc'); break;
			default:     $arSort = array('field' => 'shows',               'field2' => 'sort',        'order2' => 'asc');  break;
		}

		$APPLICATION->IncludeComponent(
			"bitrix:catalog.section",
			"menu_catalog",
			array(
				"SHOW_ALL_WO_SECTION" => "Y",
				"ELEMENT_SORT_FIELD" => $arSort['field'],
				"ELEMENT_SORT_ORDER" => "desc",
				"ELEMENT_SORT_FIELD2" => $arSort['field2'],
				"ELEMENT_SORT_ORDER2" => $arSort['order2'],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => "N",
				"CACHE_FILTER" => "Y",
				"FILTER_NAME" => "arrFilterByAvPrFoto",
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"COMPONENT_TEMPLATE" => "bitronic2",
				"SECTION_CODE" => "",
				"SECTION_ID" => $arResult["ALL_ITEMS"][$idItem_1]['PARAMS']['FILTER']["SECTION_ID"],
				"IBLOCK_ID" => $rzHitIblockId,
				"SECTION_USER_FIELDS" => array(),
				"HIDE_NOT_AVAILABLE" => $arParams['SLIDERS_HIDE_NOT_AVAILABLE'],
				"PAGE_ELEMENT_COUNT" => "10",
				"PROPERTY_CODE" => array(),
				"OFFERS_FIELD_CODE" => array('ID'),
				"OFFERS_PROPERTY_CODE" => array(),
				"OFFERS_LIMIT" => "0",
				"SECTION_URL" => "",
				"SECTION_ID_VARIABLE" => "SECTION_ID",
				"AJAX_MODE" => "N",
				"SET_TITLE" => "N",
				"SET_BROWSER_TITLE" => "N",
				"BROWSER_TITLE" => "",
				"SET_META_KEYWORDS" => "N",
				"META_KEYWORDS" => "",
				"SET_META_DESCRIPTION" => "N",
				"META_DESCRIPTION" => "",
				"SET_STATUS_404" => "N",
				"NEWS_COUNT" => "",
				"ACTION_VARIABLE" => "action",
				"PRODUCT_ID_VARIABLE" => "id",
				"PRICE_VAT_INCLUDE" => "Y",
				"CONVERT_CURRENCY" => ($rz_b2_options['convert_currency'] ? 'Y' : 'N'),
				"CURRENCY_ID" => ($rz_b2_options['convert_currency'] ? $rz_b2_options['active-currency'] : ''),
				"PARTIAL_PRODUCT_PROPERTIES" => "N",
				"PRODUCT_PROPERTIES" => array(),
				"OFFERS_CART_PROPERTIES" => array(),
				"DISPLAY_COMPARE_SOLUTION" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"DETAIL_URL" => "",
				"ADD_SECTIONS_CHAIN" => "N",
				"RESIZER_SET" => $arParams["RESIZER_SET"],
				"DISPLAY_BOTTOM_PAGER" => 'N',
				"DISPLAY_TOP_PAGER" => 'N',
				"CONTAINER_ID" => $containerId,

                'HIDE_ITEMS_NOT_AVAILABLE' => $catalogParams['HIDE_ITEMS_NOT_AVAILABLE'],
                'HIDE_ITEMS_ZER_PRICE' => $catalogParams['HIDE_ITEMS_ZER_PRICE'],
                'HIDE_ITEMS_WITHOUT_IMG' => $catalogParams['HIDE_ITEMS_WITHOUT_IMG'],
			),
			false,
			array('HIDE_ICONS' => 'Y')
		);

	} else {
		echo '<div class="scroll-slider-wrap" id="' . $containerId . '"></div>';
	}
} else {
	$frame = $this->createFrame()->begin("");
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.bigdata.products", 
		"menu_hits", 
		array(
			// BITRONIC 2 RELATED
			"RESIZER_SET" => $arParams["RESIZER_SET"],
			//"COMPONENT_TEMPLATE" => "bitronic2",
			// MAIN PARAMETERS
			"RCM_TYPE" => $arParams["HITS_TYPE"],//bestsell
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $rzHitIblockId,
			// DATA SOURCE
			"SHOW_FROM_SECTION" => "Y",
			"SECTION_ID" => $arResult["ALL_ITEMS"][$idItem_1]['PARAMS']['FILTER']["SECTION_ID"],
			"SECTION_CODE" => "",
			"DEPTH" => "2",
			"HIDE_NOT_AVAILABLE" => $arParams['SLIDERS_HIDE_NOT_AVAILABLE'],
			// VISUAL
			"SHOW_DISCOUNT_PERCENT" => "Y",
			"PRODUCT_SUBSCRIPTION" => "N",
			"SHOW_NAME" => "Y",
			"SHOW_IMAGE" => "Y",
			//"MESS_BTN_BUY" => GetMessage('BITRONIC2_BUTTON_BUY'),
			//"MESS_BTN_DETAIL" => GetMessage('BITRONIC2_BUTTON_DETAIL'),
			//"MESS_BTN_SUBSCRIBE" => GetMessage('BITRONIC2_BUTTON_SUBSCRIBE'),
			"PAGE_ELEMENT_COUNT" => "10",
			// CACHE
			"CACHE_TYPE" => $arParams['CACHE_TYPE'],
			"CACHE_TIME" => $arParams['CACHE_TIME'],
			"CACHE_GROUPS" => "N",
			// PRICES
			"SHOW_OLD_PRICE" => "N",
			"PRICE_CODE" => $arParams['PRICE_CODE'],
			//"SHOW_PRICE_COUNT" => $arParams['SHOW_PRICE_COUNT'],
			"PRICE_VAT_INCLUDE" => "Y",
			"CONVERT_CURRENCY" => ($rz_b2_options['convert_currency'] ? 'Y' : 'N'),
			"CURRENCY_ID" => ($rz_b2_options['convert_currency'] ? $rz_b2_options['active-currency'] : ''),
			// BASKET
			//"BASKET_URL" => $arParams["BASKET_URL"],
			//"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			//"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			//"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
			//"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			//"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
			//"USE_PRODUCT_QUANTITY" => "N",
			// CATALOG PRODUCTS
			"SHOW_PRODUCTS_{$rzHitIblockId}" => "Y",
			//"PROPERTY_CODE_{$arParams['IBLOCK_ID']}" => $arParams["LIST_PROPERTY_CODE"],
            //PARAMS FOR HIDE ITEMS
            'HIDE_ITEMS_NOT_AVAILABLE' => $catalogParams['HIDE_ITEMS_NOT_AVAILABLE'],
            'HIDE_ITEMS_ZER_PRICE' => $catalogParams['HIDE_ITEMS_ZER_PRICE'],
            'HIDE_ITEMS_WITHOUT_IMG' => $catalogParams['HIDE_ITEMS_WITHOUT_IMG'],
		),
		false,
		array('HIDE_ICONS' => 'Y')
	);
	$frame->end();
}
