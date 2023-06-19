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

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

if(defined("ERROR_404")) return;
//WOAH!!!! HACK HACK HACK =)
//Show items from the entire catalog if we have filter set from brands
if (array_key_exists('rz_all_elements', $_REQUEST) && $_REQUEST['rz_all_elements'] === 'y') {
	$arParams['SHOW_ALL_WO_SECTION'] = 'Y';
	Loc::loadMessages(__DIR__.'/section.php');

	include 'section.php';
	return;
}

global $rz_b2_options;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/pages/initCatalogLvl0Page.js");
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/components/brands-catalog/brands-catalog.js");

// ON COMPOSITE ON THIS PAGE
$this->setFrameMode(true);
?>
<main class="container catalog-lvl0-page" data-page="catalog-lvl0-page">
	<h1><?$APPLICATION->ShowTitle(false)?></h1>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file",	"PATH" => SITE_DIR."include_areas/catalog/lvl0_description.php",	"EDIT_TEMPLATE" => "include_areas_template.php"	), false, array("HIDE_ICONS"=>"N"));?>
	
	<?
	$arBrandParams = array('PATH_TO_VIEW' => SITE_DIR . 'brands/#ID#/');
	if (Loader::IncludeModule('yenisite.core')) {
		$arBrandParams = \Yenisite\Core\Ajax::getParams('yenisite:highloadblock', false, CRZBitronic2CatalogUtils::getBrandPathForUpdate());
	}
	$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "section", array(
			"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['IBLOCK_ID'],
			"ELEMENT_ID" => "",
			"ELEMENT_CODE" => "",
			"PROP_CODE" => $arParams['LIST_BRAND_PROP_CODE'],
			"WIDTH" => "92",
			"HEIGHT" => "50",
			"WIDTH_SMALL" => "92",
			"HEIGHT_SMALL" => "50",
			"CACHE_TYPE" => $arParams['CACHE_TYPE'],
			"CACHE_TIME" => $arParams['CACHE_TIME'],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"PATH_FOLDER" => SITE_DIR."catalog/",
			"CATALOG_FILTER_NAME" => $arParams["FILTER_NAME"],
			//"FILTER_NAME" => $arParams["FILTER_NAME"],
			"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"BRANDS_EXT" => $rz_b2_options["brands_extended"],
			"BRAND_DETAIL" => $arBrandParams['PATH_TO_VIEW'],
		),
		$component
	);?>
	
	<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "catalog_lvl0", array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		"TOP_DEPTH" => '3',
		"SECTION_URL" => "",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"RESIZER_SECTION_LVL0" => $arParams["RESIZER_SECTION_LVL0"],
		"ADD_SECTIONS_CHAIN" => "N",
		"VIEW_MODE" => "TEXT",
		"SHOW_ICONS" => $rz_b2_options["menu-show-icons"],
		"SHOW_PARENT_NAME" => "N"
		),
		$component
	);?>
</main>