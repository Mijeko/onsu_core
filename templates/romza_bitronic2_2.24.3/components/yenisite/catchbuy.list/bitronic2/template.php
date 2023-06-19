<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
use \Bitronic2\Mobile;

$this->setFrameMode(true);
CJSCore::Init(array('rz_b2_bx_catalog_item'));
global $rz_b2_options;
$arParams = array_merge($arParams, array(
	"SHOW_STARS" => $rz_b2_options["block_show_stars"],
	"DISPLAY_FAVORITE" => $rz_b2_options['block_show_favorite'] === 'Y' && Loader::includeModule('yenisite.favorite'),
	"DISPLAY_ONECLICK" => $rz_b2_options['block_show_oneclick'] === 'Y' && Loader::includeModule('yenisite.oneclick'),
	"DISPLAY_COMPARE_SOLUTION" => $rz_b2_options["block_show_compare"] == "Y",
	"SHOW_ARTICLE" => $rz_b2_options["block_show_article"],
	"SHOW_COMMENT_COUNT" => $rz_b2_options["block_show_comment_count"],
	"SHOW_GALLERY_THUMB" => $rz_b2_options["block_show_gallery_thumb"],
	"HOVER-MODE" =>  Mobile::isMobile() ? 'border-n-shadow' : $rz_b2_options['product-hover-effect'],
	"COLOR_HEADING" => $rz_b2_options["catchbuy_color_heading"],
	"SHOW_DISCOUNT_PERCENT" => $rz_b2_options["show_discount_percent"] === "N" ? "N" : "Y",
));

$arParamsCatalog = array();
if (Loader::IncludeModule('yenisite.core')) {
    $arParamsCatalog = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}

$arParams['HIDE_ITEMS_NOT_AVAILABLE'] = $arParamsCatalog['HIDE_ITEMS_NOT_AVAILABLE'];
$arParams['HIDE_ITEMS_ZER_PRICE'] = $arParamsCatalog['HIDE_ITEMS_ZER_PRICE'];
$arParams['HIDE_ITEMS_WITHOUT_IMG'] = $arParamsCatalog['HIDE_ITEMS_WITHOUT_IMG'];
$arParams['OFFER_TREE_PROPS'] = $arParamsCatalog['OFFER_TREE_PROPS'] ? : array();
$arParams['OFFERS_CART_PROPERTIES'] = $arParamsCatalog['OFFERS_CART_PROPERTIES'] ? : array();
$arParams['HURRY_ORDER'] = $rz_b2_options['order-sHurry'];

CRZBitronic2CatalogUtils::setFilterAvPrFoto(${$arParams['FILTER_NAME']},$arParamsCatalog);
$APPLICATION->IncludeComponent('bitrix:catalog.section', $arParams['CATALOG_TEMPLATE'], $arParams, $component);
