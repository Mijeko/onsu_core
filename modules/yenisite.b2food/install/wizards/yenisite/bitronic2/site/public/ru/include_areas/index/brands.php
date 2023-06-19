<?php
global $rz_b2_options;
$arBrandParams = array('PATH_TO_VIEW' => '');
if (Bitrix\Main\Loader::IncludeModule('yenisite.core')) {
	$arBrandParams = \Yenisite\Core\Ajax::getParams('yenisite:highloadblock', false, CRZBitronic2CatalogUtils::getBrandPathForUpdate());
}
if (CModule::IncludeModule('iblock')):
	?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.brandblock",
	"main_page",
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "#CATALOG_IBLOCK_ID#",
		"ELEMENT_ID" => "",
		"ELEMENT_CODE" => "",
		"PROP_CODE" => "BRANDS_REF",
		"WIDTH" => "100",
		"HEIGHT" => "100",
		"WIDTH_SMALL" => "100",
		"HEIGHT_SMALL" => "100",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"PATH_FOLDER" => SITE_DIR."catalog/",
		"CATALOG_FILTER_NAME" => "arrFilter",
		"HEADER" => "Мы предлагаем товары ведущих мировых брендов",
		"BRANDS_EXT" => $rz_b2_options["brands_extended"],
		"BRANDS_CLOUD" => $rz_b2_options["brands_cloud"],
		"BRAND_DETAIL" => $arBrandParams["PATH_TO_VIEW"],
		"BRAND_LIST" => $arBrandParams["PATH_TO_LIST"],
		"ELEMENT_COUNT" => "20"
		//"FILTER_NAME" => "arrFilter"
	),
	false
);?>
<? endif ?>
