<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Вакансии");
?>
<main class="container" data-page="vacancy-page">
	<div class="row">
		<div class="col-xs-12 vacancy isotope-module">
			<h1><?$APPLICATION->ShowTitle('h1')?></h1>
			<p>Хотите работать в команде профессионалов? Тогда, присылайте нам Ваше резюме! :)</p>
			<p><a class="big" href="mailto:jobs@company.ru">jobs@company.ru</a></p>

<? $APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"vacancies", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "#VACANCY_IBLOCK_ID#",
		"COMPONENT_TEMPLATE" => "vacancies",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "NAME",
		"SORT_ORDER2" => "ASC",
		"CHECK_DATES" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_REVIEW" => "N",
		"USE_FILTER" => "N",
		"FILTER_NAME" => "",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(),
		"PROPERTY_CODE" => array(),
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Вакансии",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>

		</div>
	</div>
<?
	if ('N' !== $rz_b2_options['block_404-viewed']
	||  'N' !== $rz_b2_options['block_404-bestseller']
	||  'N' !== $rz_b2_options['block_404-recommend']
	) {
		CJSCore::Init(array('rz_b2_bx_catalog_item'));
		Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/custom-scripts/inits/sliders/initHorizontalCarousels.js');

		$arParams = array();
		if (Bitrix\Main\Loader::includeModule('yenisite.core')) {
			$arParams = Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
		}
		// @var array $arPrepareParams
		include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/prepare_params_element.php';

		$frame = new \Bitrix\Main\Page\FrameBuffered("vacancy_sliders");
		$frame->begin('');
		if ('N' !== $rz_b2_options['block_404-viewed']) {
			include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/viewed_products.php';
		}
		if ('N' !== $rz_b2_options['block_404-bestseller']) {
			$arPrepareParams['HEADER_TEXT'] = $arParams['BIGDATA_BESTSELL_TITLE'] ?: 'Лидеры продаж';
			$arPrepareParams['RCM_TYPE'] = 'bestsell';
			include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/bigdata.php';
		}
		if ('N' !== $rz_b2_options['block_404-recommend']) {
			$arPrepareParams['HEADER_TEXT'] = $arParams['BIGDATA_PERSONAL_TITLE'] ?: 'Рекомендуем';
			$arPrepareParams['RCM_TYPE'] = 'personal';
			include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/bigdata.php';
		}
		$frame->end();
	}
?>
</main>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
