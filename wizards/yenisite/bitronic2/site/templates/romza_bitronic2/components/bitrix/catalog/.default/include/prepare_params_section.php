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
use Bitronic2\Mobile;
global $rz_b2_options;

if (isset($arParams['ELEMENT_SORT_FIELD_CUSTOM']) && !empty($arParams['ELEMENT_SORT_FIELD_CUSTOM']) && $arParams['ELEMENT_SORT_FIELD_CUSTOM'] == 'CATALOG_AVAILABLE'){
    $sortField = 'PROPERTYSORT_RZ_AVAILABLE';
} elseif (isset($arParams['ELEMENT_SORT_FIELD_CUSTOM']) && !empty($arParams['ELEMENT_SORT_FIELD_CUSTOM'])){
    $sortField = $arParams['ELEMENT_SORT_FIELD_CUSTOM'];
} else{
    $sortField = '';
}


if (CRZBitronic2Settings::isPro($withGeoip = true)) {
	if(!isset($rz_b2_options['GEOIP'])) {
		$arRes = $APPLICATION->IncludeComponent('yenisite:geoip.store', 'empty');
		$rz_b2_options['GEOIP'] = $arRes;
	}
	if (!empty($rz_b2_options['GEOIP']['PRICES'])) {
		$arParams["PRICE_CODE"] = $rz_b2_options['GEOIP']['PRICES'];
	}
	$arParams['STORES'] = NULL;
	if (!empty($rz_b2_options['GEOIP']['STORES'])) {
		$arParams['STORES'] = $rz_b2_options['GEOIP']['STORES'];
	}
	if (!empty($rz_b2_options['GEOIP']['ITEM'])) {
		$sortField .= '_' . $rz_b2_options['GEOIP']['ITEM']['ID'];
	}
    if (isset($arParams['ELEMENT_SORT_FIELD_CUSTOM']) && !empty($arParams['ELEMENT_SORT_FIELD_CUSTOM']) && $arParams['ELEMENT_SORT_FIELD_CUSTOM'] == 'sort_pro'){
        $sortField = 'sort';
    }
}


global ${$arParams['FILTER_NAME']};
CRZBitronic2CatalogUtils::setFilterAvPrFoto(${$arParams['FILTER_NAME']}, $arParams);


if (isset($rz_b2_options['DEMO_CONTENT']['CATALOG']) && isset($arParams['IBLOCK_ID'])) {
	$arParams['IBLOCK_ID'] = $rz_b2_options['DEMO_CONTENT']['CATALOG'];
}

if (empty($sortField)){
    $sortField = $sort['FOR_PARAMS'];
    $arParams['ELEMENT_SORT_ORDER_CUSTOM'] = $by . (strpos($sort['FOR_PARAMS'], 'property_') === 0 ? ',nulls' : '');
}
$arSectionParams = array(
		"SHOW_ALL_WO_SECTION" => "Y",
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"PAGE_ELEMENT_COUNT" => $page_count,
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_SORT_FIELD" => $sortField,
        "ELEMENT_SORT_ORDER" => $arParams['ELEMENT_SORT_ORDER_CUSTOM'] ? : 'ASC',
		"ELEMENT_SORT_FIELD2" => $sort['FOR_PARAMS'],
		"ELEMENT_SORT_ORDER2" => $by, //. (strpos($sort['FOR_PARAMS'], 'property_') === 0 ? ',nulls' : ''),
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
		"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
		"ADD_SECTIONS_CHAIN" => "Y",
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
        "LIST_PRICE_SORT" => $arParams["LIST_PRICE_SORT"],
		"USE_PRICE_COUNT" => "N",
		"USE_PRICE_COUNT_" => $rz_b2_options["extended-prices-enabled"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"MESSAGE_404" => $arParams["~MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		
		//sku:
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
		"HIDE_DISABLED_PROPS_OF_OFFERS" => $arParams["HIDE_DISABLED_PROPS_OF_OFFERS"] != 'N',

		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT' => $rz_b2_options['show_discount_percent'] === 'N' ? 'N' : 'Y',
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
		'VIEWED_TITLE' => $arParams['DETAIL_VIEWED_TITLE'],

		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
		'SLIDERS_HIDE_NOT_AVAILABLE' => $arParams['SLIDERS_HIDE_NOT_AVAILABLE'] === 'Y' ? 'Y' : 'N',
		'LABEL_PROP' => $arParams['LABEL_PROP'],
		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'PRODUCT_DISPLAY_MODE_CUSTOM' => $arParams['PRODUCT_DISPLAY_MODE_SECTION'],
		'HIDE_ICON_SLIDER' => $arParams['HIDE_ICON_SLIDER'],
		"ADD_PARENT_PHOTO" => $arParams["ADD_PARENT_PHOTO"],
		
		// paginator:
		'PAGER_SHOW_ALWAYS' => $arParams['PAGER_SHOW_ALWAYS'],
		'LAZY_LOAD' => $arParams['PAGER_SHOW_ALWAYS'],
		'PAGER_DESC_NUMBERING' => $arParams['PAGER_DESC_NUMBERING'],
		'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
		'PAGER_SHOW_ALL' => $arParams['PAGER_SHOW_ALL'],
		'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
		'DISPLAY_TOP_PAGER' => $arParams['DISPLAY_TOP_PAGER'],
		'DISPLAY_BOTTOM_PAGER' => $arParams['DISPLAY_BOTTOM_PAGER'],
		'PAGER_TITLE' => $arParams['PAGER_TITLE'],
		//store:
		'USE_STORE' => $arParams['USE_STORE'],
		'HIDE_STORE_LIST' => $arParams['HIDE_STORE_LIST'],
		//resizer:
		"RESIZER_SECTION" => $arParams["RESIZER_SECTION"],
		"RESIZER_SECTION_ICON" => $arParams["RESIZER_SECTION_ICON"],
		"RESIZER_SECTION_VIP" => $arParams["RESIZER_SECTION_VIP"],
		//stickers :
		"STICKER_NEW" => $arParams['STICKER_NEW'],
		"STICKER_HIT" => $arParams['STICKER_HIT'],
		"STICKER_BESTSELLER" => $arParams['STICKER_BESTSELLER'],
		"TAB_PROPERTY_NEW" => $arParams['TAB_PROPERTY_NEW'],
		"TAB_PROPERTY_HIT" => $arParams['TAB_PROPERTY_HIT'],
		"TAB_PROPERTY_SALE" => $arParams['TAB_PROPERTY_SALE'],
		"TAB_PROPERTY_BESTSELLER" => $arParams['TAB_PROPERTY_BESTSELLER'],
		"MAIN_SP_ON_AUTO_NEW" => $arParams['MAIN_SP_ON_AUTO_NEW'],

		"SHOW_CATCHBUY" => $rz_b2_options['catalog_catchbuy_slider'] !== 'N',
		"SHOW_BUY_BTN" => $rz_b2_options['block-buy_button'] === 'Y',
		
		//articul:
		"ARTICUL_PROP" => $arParams['ARTICUL_PROP'],

		//reviews
		'USE_REVIEW' => $arParams['USE_REVIEW'],
		'USE_OWN_REVIEW' => $arParams['USE_OWN_REVIEW'],
		'REVIEWS_MODE' => $arParams['REVIEWS_MODE'],
		"FEEDBACK_IBLOCK_ID" => $arParams["FEEDBACK_IBLOCK_ID"],

		// mobile:
		"IS_MOBILE" => mobile::isMobile(),
		'STORE_DISPLAY_TYPE' => $rz_b2_options['store_amount_type'],
        'PRODUCT_AVAILABILITY_VIEW' => false,
		'STORES' => $arParams['STORES'],
		// other
		'HOVER-MODE' =>  $arResult['HOVER-MODE'],
		"HIDE_BUTTONS" => true, // todo: +++ change to $rz_b2_options
		'SHOW_STARS' => $rz_b2_options['block_show_stars'],
		'DISPLAY_FAVORITE' => $rz_b2_options['block_show_favorite'] == 'Y' && Bitrix\Main\Loader::includeModule('yenisite.favorite'),
		'DISPLAY_ONECLICK' => $rz_b2_options['block_show_oneclick'] == 'Y' && Bitrix\Main\Loader::includeModule('yenisite.oneclick'),
		"DISPLAY_COMPARE_SOLUTION" => $rz_b2_options['block_show_compare'] == 'Y',
		'SHOW_ARTICLE' => $rz_b2_options['block_show_article'],
		'SHOW_COMMENT_COUNT' => $rz_b2_options['block_show_comment_count'],
		'SHOW_GALLERY_THUMB' => $rz_b2_options['block_show_gallery_thumb'],

        //PARAMS FOR HIDE ITEMS
        'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
        'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
        'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],

        //vip
        'VIP_ITEM_PROPERTY' => $arParams['VIP_ITEM_PROPERTY'] != '-' && $arParams['VIP_ITEM_PROPERTY']  ? $arParams['VIP_ITEM_PROPERTY'] : 'RZ_VIP',

       'BIGDATA_SECTION' => $arParams['BIGDATA_SECTION'],
        'SHOW_HOVER_PHOTO' => $rz_b2_options['product-hover-effect'] == 'switch_photo',

        //FILTER_FOR_SKU
        'DATA_FILTER' => $arResult['DATA_OF_FILTER'],
	);

if (Bitrix\Main\ModuleManager::isModuleInstalled("catalog")) {
	// you need it to support live preview of stores popups and have different cache
	$arSectionParams['SHOW_STORE'] = $rz_b2_options['stores'];
}

if (isset($view) && $view == 'table') {
	// you need it to support live preview of catalog_quantity and have different cache
	$arSectionParams['SHOW_QUANTITY'] = $rz_b2_options['show-stock'];
}
if (isset($view) && $view == 'list') {
	// you need it to use change offer
	$arSectionParams['OFFERS_PROPERTY_CODE'] = $arParams["DETAIL_OFFERS_PROPERTY_CODE"];
}
if ($rz_b2_options['convert_currency']) {
	$arSectionParams['CONVERT_CURRENCY'] = 'Y';
	$arSectionParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
}