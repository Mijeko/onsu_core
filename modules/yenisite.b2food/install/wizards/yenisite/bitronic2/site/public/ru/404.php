<?
include_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/urlrewrite.php';
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
CHTTP::SetStatus('404 Not Found');
if (!defined('ERROR_404')) {
	define('ERROR_404', 'Y');
}

$APPLICATION->SetPageProperty('title', 'Страница не найдена');
// $APPLICATION->AddChainItem('404');

global $rz_b2_options;

$asset = Bitrix\Main\Page\Asset::getInstance();
$asset->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/pages/initNotFoundPage.js");

$bCore = Bitrix\Main\Loader::includeModule('yenisite.core');

?>
<main class="container not-found-page" data-page="not-found-page">
	<? if ($bCore) Yenisite\Core\Tools::includeArea('404', 'banner', false, true, $rz_b2_options['block_show_ad_banners']) ?>
	<div class="row">
		<div class="col-xs-12">
			<h1><?$APPLICATION->ShowTitle()?></h1>
			<p>Так сложились звезды, что этой страницы либо не существует, либо ее похитили инопланетяне для опытов. Но это не беда! Мы уверены, что вы обязательно найдете что-нибудь полезное для себя в нашем интернет-магазине.</p>
			<div class="sad-robot"></div>
			<div class="big404">404</div>
			<p>Перейти к <a href="<?=SITE_DIR?>site-map/" class="link"><span class="text">карте сайта</span></a>.</p>
		</div><!-- /.col-xs-12 -->
	</div>
	<?
	if ('N' !== $rz_b2_options['block_404-viewed']
	||  'N' !== $rz_b2_options['block_404-bestseller']
	||  'N' !== $rz_b2_options['block_404-recommend']
	) {
		CJSCore::Init(array('rz_b2_bx_catalog_item'));
		$asset->addJs(SITE_TEMPLATE_PATH . '/js/custom-scripts/inits/sliders/initHorizontalCarousels.js');

		$arParams = array();
		if ($bCore) {
			$arParams = Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
		}
		// @var array $arPrepareParams
		include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/prepare_params_element.php';
	}
	if ($rz_b2_options['block_404-viewed'] !== 'N') {
		include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/viewed_products.php';
	}
	if ($rz_b2_options['block_404-bestseller'] !== 'N') {
		$arPrepareParams['HEADER_TEXT'] = $arParams['BIGDATA_BESTSELL_TITLE'] ?: 'Лидеры продаж';
		$arPrepareParams['RCM_TYPE'] = 'bestsell';
		include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/bigdata.php';
	}
	if ($rz_b2_options['block_404-recommend'] !== 'N') {
		$arPrepareParams['HEADER_TEXT'] = $arParams['BIGDATA_PERSONAL_TITLE'] ?: 'Рекомендуем';
		$arPrepareParams['RCM_TYPE'] = 'personal';
		include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/bigdata.php';
	}
	?>

</main>
<? require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php' ?>