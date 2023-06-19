<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitronic2\Mobile;

global $rz_b2_options;

if($rz_b2_options['block_show_compare'] != 'Y') {
	define("ERROR_404", true);
	return;
}
if (strpos($arResult['URL_TEMPLATES']['compare'], '#QUERY#') !== false) {
	$arCodes = explode('-vs-', $arResult['VARIABLES']['QUERY']);
	$arSessionCodes = array();
	foreach ($_SESSION[$arParams["COMPARE_NAME"]][$arParams['IBLOCK_ID']]['ITEMS'] as $key => $arItem) {
		$arSessionCodes[$key] = $arItem['CODE'];
	}
	if (count($arCodes) < 2) {
		$str = implode('-vs-', $arSessionCodes);
		if (empty($str)) {
			$str = 'list';
		}
		if ($str !== $arResult['VARIABLES']['QUERY'] && !Yenisite\Core\Tools::isAjax()) {
			$page = str_replace($arResult['VARIABLES']['QUERY'], $str, $APPLICATION->GetCurPageParam());
			LocalRedirect($page);
		}
	} else {
		foreach ($arSessionCodes as $key => $code) {
			if (in_array($code, $arCodes)) continue;
			unset($arSessionCodes[$key], $_SESSION[$arParams["COMPARE_NAME"]][$arParams['IBLOCK_ID']]['ITEMS'][$key]);
		}
		if (count($arCodes) > count($arSessionCodes)) {
			$action = $_REQUEST[$arParams['ACTION_VARIABLE']];
			$productId = $_REQUEST[$arParams['PRODUCT_ID_VARIABLE']];
			$arNewCodes = array();
			foreach ($arCodes as $key => $code) {
				if (in_array($code, $arSessionCodes)) continue;
				$arNewCodes[] = $code;
			}
			Bitrix\Main\Loader::includeModule('iblock');
			$rs = CIBlockElement::GetList(array(), array('CODE' => $arNewCodes));
			while ($arProduct = $rs->Fetch()) {
				if ($action == 'DELETE_FROM_COMPARE_LIST' || $action == 'DELETE_FROM_COMPARE_RESULT') {
					if (intval($arProduct['ID']) == intval($productId)) continue;
				}
				$_REQUEST[$arParams['ACTION_VARIABLE']] = 'ADD_TO_COMPARE_LIST';
				$_REQUEST[$arParams['PRODUCT_ID_VARIABLE']] = $arProduct['ID'];
				ob_start();
				include $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include_areas/header/compare.php";
				ob_end_clean();
			}
			$_REQUEST[$arParams['ACTION_VARIABLE']] = $action;
			$_REQUEST[$arParams['PRODUCT_ID_VARIABLE']] = $productId;
		}
	}
	switch ($_REQUEST[$arParams['ACTION_VARIABLE']]) {
		case 'ADD_TO_COMPARE_LIST':
		case 'ADD_TO_COMPARE_RESULT':
		case 'DELETE_FROM_COMPARE_LIST':
		case 'DELETE_FROM_COMPARE_RESULT':
		$_REQUEST[$arParams['ACTION_VARIABLE']] = str_replace('RESULT', 'LIST', $_REQUEST[$arParams['ACTION_VARIABLE']]);
		if (empty($_REQUEST[$arParams['PRODUCT_ID_VARIABLE']]) && isset($_REQUEST['ID'])) {
			$_REQUEST[$arParams['PRODUCT_ID_VARIABLE']] = $_REQUEST['ID'];
		}
		ob_start();
		include $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include_areas/header/compare.php";
		ob_end_clean();
		unset($_REQUEST['ID'], $_REQUEST[$arParams['PRODUCT_ID_VARIABLE']], $_REQUEST[$arParams['ACTION_VARIABLE']]);

		$arSessionCodes = array();
		foreach ($_SESSION[$arParams["COMPARE_NAME"]][$arParams['IBLOCK_ID']]['ITEMS'] as $key => $arItem) {
			$arSessionCodes[$key] = $arItem['CODE'];
		}
		$str = implode('-vs-', $arSessionCodes);
		if (empty($str)) {
			$str = 'list';
		}
		if ($str !== $arResult['VARIABLES']['QUERY']) {
			$page = str_replace($arResult['VARIABLES']['QUERY'], $str, $APPLICATION->GetCurPage());
			if (Yenisite\Core\Tools::isAjax()) {
				$_SERVER['REQUEST_URI'] = str_replace($arResult['VARIABLES']['QUERY'], $str, $_SERVER['REQUEST_URI']);
				$_SERVER['SCRIPT_NAME'] = str_replace($arResult['VARIABLES']['QUERY'], $str, $_SERVER['SCRIPT_NAME']);
				$APPLICATION->reinitPath();
				echo '<input type="hidden" id="compareURL" value="', $page, '">';
			} else {
				LocalRedirect($page);
			}
		}
		default: break;
	}
}

CJSCore::Init(array('rz_b2_bx_catalog_item'));
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/sliders/initHorizontalCarousels.js");

if(mobile::isMobile()){
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/pages/initComparePageMobile.js");
} else{
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/pages/initComparePage.js");
}
?>

<main class="container compare-page" <?if(mobile::isMobile()):?>data-page="compare-page-mobile"<?else:?>data-page="compare-page"<?endif?>>
	<h1><? $APPLICATION->ShowTitle(false) ?></h1>
<?
$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.result",
	"",
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"FIELD_CODE" => $arParams["COMPARE_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["COMPARE_PROPERTY_CODE"],
		"NAME" => $arParams["COMPARE_NAME"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => "N",//$arParams["USE_PRICE_COUNT"], BTWO-1467
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"DISPLAY_ELEMENT_SELECT_BOX" => $arParams["DISPLAY_ELEMENT_SELECT_BOX"],
		"ELEMENT_SORT_FIELD_BOX" => $arParams["ELEMENT_SORT_FIELD_BOX"],
		"ELEMENT_SORT_ORDER_BOX" => $arParams["ELEMENT_SORT_ORDER_BOX"],
		"ELEMENT_SORT_FIELD_BOX2" => $arParams["ELEMENT_SORT_FIELD_BOX2"],
		"ELEMENT_SORT_ORDER_BOX2" => $arParams["ELEMENT_SORT_ORDER_BOX2"],
		"ELEMENT_SORT_FIELD" => $arParams["COMPARE_ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["COMPARE_ELEMENT_SORT_ORDER"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"OFFERS_FIELD_CODE" => $arParams["COMPARE_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["COMPARE_OFFERS_PROPERTY_CODE"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

		//SEF features
		"URL_TEMPLATES" => $arResult["URL_TEMPLATES"],
		"URL_VARIABLES" => $arResult["VARIABLES"],
		
		//resizer:
		"RESIZER_SECTION" => $arParams["RESIZER_SECTION"],
		
		//articul:
		"ARTICUL_PROP" => $arParams['ARTICUL_PROP'],

		//meta:
		"META_H1" => $arParams['COMPARE_META_H1'],
		"META_TITLE" => $arParams['COMPARE_META_TITLE'],
		"META_KEYWORDS" => $arParams['COMPARE_META_KEYWORDS'],
		"META_DESCRIPTION" => $arParams['COMPARE_META_DESCRIPTION'],
		'SHOW_STARS' => $rz_b2_options['block_show_stars'],
		'DISPLAY_FAVORITE' => $rz_b2_options['block_show_favorite'] === 'Y' && Loader::includeModule('yenisite.favorite'),
		'DISPLAY_ONECLICK' => $rz_b2_options['block_show_oneclick'] === 'Y' && Loader::includeModule('yenisite.oneclick'),
		"DISPLAY_COMPARE_SOLUTION" => $rz_b2_options['block_show_compare'] == 'Y',
		'SHOW_ARTICLE' => $rz_b2_options['block_show_article'],
		'SHOW_COMMENT_COUNT' => $rz_b2_options['block_show_comment_count'],
		'SHOW_GALLERY_THUMB' => $rz_b2_options['block_show_gallery_thumb'],

        //PARAMS FOR HIDE ITEMS
        'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
        'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
        'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],

        'INCLUDE_MOBILE_PAGE' => mobile::isMobile(),
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>
</main>
