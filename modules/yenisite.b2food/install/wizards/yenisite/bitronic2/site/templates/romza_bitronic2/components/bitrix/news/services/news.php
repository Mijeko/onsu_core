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

global $rz_b2_options;

$asset = Bitrix\Main\Page\Asset::getInstance();
$asset->addJs(SITE_TEMPLATE_PATH . '/js/3rd-party-libs/isotope.pkgd.min.js');
$asset->addJs(SITE_TEMPLATE_PATH . '/js/custom-scripts/inits/pages/initServicesPage.js');

?>
<main class="container news-page" data-page="services-page">
	<div class="row">
		<div class="col-xs-12 services isotope-module">
			<h1><? $APPLICATION->ShowTitle('h1') ?></h1>
			<? $APPLICATION->ShowViewContent('service_section_list') ?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"service_list",
	Array(
		"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
		"PAGE_ELEMENT_COUNT"	=>	$arParams["NEWS_COUNT"],
		"ELEMENT_SORT_FIELD"	=>	$arParams["SORT_BY1"],
		"ELEMENT_SORT_ORDER"	=>	$arParams["SORT_ORDER1"],
		"ELEMENT_SORT_FIELD2"	=>	$arParams["SORT_BY2"],
		"ELEMENT_SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
		"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
		"PROPERTY_CODE"	=>	array_merge((array)$arParams["LIST_PROPERTY_CODE"], array("PRICE")),
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
		"SET_TITLE"	=>	$arParams["SET_TITLE"],
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"DISPLAY_COMPARE_SOLUTION" => "N",
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
		"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
		"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
		"DISPLAY_NAME"	=>	"Y",
		"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
		"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
		
		"RESIZER_SET"	=>	$arParams["RESIZER_SERVICE_LIST"],
	),
	$component
);?>
<?
global $serviceSectionFilter;
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list", 
	"services", 
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"COMPONENT_TEMPLATE" => "services",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_FILTER" => $serviceSectionFilter,
		"COUNT_ELEMENTS" => empty($serviceSectionFilter) ? "Y" : "N",
		"TOP_DEPTH" => "2",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SHOW_PARENT_NAME" => "N",
		"HIDE_SECTION_NAME" => "N",
		"SECTION_URL" => "",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => "Y",
		"ADD_SECTIONS_CHAIN" => "N"
	),
	$component
);?>
		</div>
	</div>
<?
	if ('N' !== $rz_b2_options['block_404-viewed']
	||  'N' !== $rz_b2_options['block_404-bestseller']
	||  'N' !== $rz_b2_options['block_404-recommend']
	) {
		CJSCore::Init(array('rz_b2_bx_catalog_item'));
		$asset->addJs(SITE_TEMPLATE_PATH . '/js/custom-scripts/inits/sliders/initHorizontalCarousels.js');

		$arNewsParams = $arParams;
		$arParams = array();
		if (Bitrix\Main\Loader::includeModule('yenisite.core')) {
			$arParams = Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
		}
		// @var array $arPrepareParams
		include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/prepare_params_element.php';

		$frame = $this->createFrame()->begin('');
		if ('N' !== $rz_b2_options['block_404-viewed']) {
			include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/viewed_products.php';
		}
		if ('N' !== $rz_b2_options['block_404-bestseller']) {
			$arPrepareParams['HEADER_TEXT'] = $arParams['BIGDATA_BESTSELL_TITLE'] ?: GetMessage('LIDERS_SALES');
			$arPrepareParams['RCM_TYPE'] = 'bestsell';
			include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/bigdata.php';
		}
		if ('N' !== $rz_b2_options['block_404-recommend']) {
			$arPrepareParams['HEADER_TEXT'] = $arParams['BIGDATA_PERSONAL_TITLE'] ?: GetMessage('RECOMENDATIONS');
			$arPrepareParams['RCM_TYPE'] = 'personal';
			include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/bigdata.php';
		}
		$frame->end();
		$arParams = $arNewsParams;
	}
?>
</main>