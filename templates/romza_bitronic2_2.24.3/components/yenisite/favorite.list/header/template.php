<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
use Bitrix\Main\Loader;

if (!is_array($arParams['OFFERS_FIELD_CODE'])) $arParams['OFFERS_FIELD_CODE'] = array();

global $rz_b2_options, ${$arParams['FILTER_NAME']};
if ($rz_b2_options['convert_currency']) {
	$arParams['CONVERT_CURRENCY'] = 'Y';
	$arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
}
$arParams['PAGE_ELEMENT_COUNT'] = 99999;
$arParams['OFFERS_LIMIT'] = 0;
$arParams['OFFERS_FIELD_CODE'] = array_unique(array_merge($arParams['OFFERS_FIELD_CODE'], array('ID', 'NAME', 'DETAIL_PAGE_URL')));
$arParams['SHOW_VOTING'] = $rz_b2_options['block_show_stars'];
$arParams['SHOW_ARTICLE'] = $rz_b2_options['block_show_article'];

$arParamsCatalog = array();
if (Loader::IncludeModule('yenisite.core')) {
    $arParamsCatalog = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}

CRZBitronic2CatalogUtils::setFilterAvPrFoto(${$arParams['FILTER_NAME']},$arParamsCatalog);

$arParams['HIDE_ITEMS_NOT_AVAILABLE'] = $arParamsCatalog['HIDE_ITEMS_NOT_AVAILABLE'];
$arParams['HIDE_ITEMS_ZER_PRICE'] = $arParamsCatalog['HIDE_ITEMS_ZER_PRICE'];
$arParams['HIDE_ITEMS_WITHOUT_IMG'] = $arParamsCatalog['HIDE_ITEMS_WITHOUT_IMG'];

$APPLICATION->IncludeComponent('bitrix:catalog.section', $arParams['CATALOG_TEMPLATE'], $arParams, $component);
