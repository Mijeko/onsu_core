<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;

// @var $moduleId
// @var $moduleCode
include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';
if(!Loader::IncludeModule($moduleId)) die('Module ' . $moduleId . ' not installed!');

foreach($arResult["ITEMS"] as &$arElement) {
	$arElement['CATALOG_AVAILABLE'] = CRZBitronic2CatalogUtils::getAvailableStatus($arElement['ID'], $arElement['CATALOG_AVAILABLE'], $arElement);
	$arElement['CATALOG_QUANTITY'] = CRZBitronic2CatalogUtils::getStoresCount($arElement['ID'], $arElement['CATALOG_QUANTITY']);
}
if (isset($arElement)) {
	unset($arElement);
}
