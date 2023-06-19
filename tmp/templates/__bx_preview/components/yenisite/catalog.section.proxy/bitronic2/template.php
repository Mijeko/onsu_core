<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
use Bitrix\Main\Loader;

global ${$arParams['FILTER_NAME']} ;
${$arParams['FILTER_NAME']} = array('!PROPERTY_SHOW_MAIN' => false);

$arParamsCatalog = array();
if (Loader::IncludeModule('yenisite.core')) {
    $arParamsCatalog = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}
CRZBitronic2CatalogUtils::setFilterAvPrFoto(${$arParams['FILTER_NAME']},$arParamsCatalog);

global $rz_b2_options;
if ($rz_b2_options['convert_currency']) {
	$arParams['CONVERT_CURRENCY'] = 'Y';
	$arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
}
$arParams['SHOW_DISCOUNT_PERCENT'] = ($rz_b2_options['show_discount_percent'] === 'N') ? 'N' : 'Y';

$arParams['HIDE_ITEMS_NOT_AVAILABLE'] = $arParamsCatalog['HIDE_ITEMS_NOT_AVAILABLE'];
$arParams['HIDE_ITEMS_ZER_PRICE'] = $arParamsCatalog['HIDE_ITEMS_ZER_PRICE'];
$arParams['HIDE_ITEMS_WITHOUT_IMG'] = $arParamsCatalog['HIDE_ITEMS_WITHOUT_IMG'];

$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"cool_slider",
	$arParams,
	$component,
	array('HIDE_ICONS' => 'N')
);
?>
