<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Yenisite\Core\Tools;

global $rz_b2_options;

if (empty($_REQUEST['sort_id'])) {
	$_REQUEST['sort_id'] = $_GET['sort_id'] = 'UF_SORT';
}

if (empty($_REQUEST['sort_type'])) {
	$_REQUEST['sort_type'] = $_GET['sort_type'] = 'ASC';
}
?>

<main class="container news-page">
<? Tools::includeArea('brands', 'banner', false, true, $rz_b2_options['block_show_ad_banners']) ?>

<div class="row">
	<div class="col-xs-12">
		<h1><? $APPLICATION->ShowTitle(false) ?></h1>
	</div><!-- /.col-xs-12 -->
</div>
<?
$APPLICATION->IncludeComponent("bitrix:highloadblock.list", "bitronic2", Array(
		"BLOCK_ID" => $arParams['BLOCK_ID'],
		"DETAIL_URL" => $arResult['PATH_TO_VIEW'],
		"NAV_TEMPLATE" => $arParams['NAV_TEMPLATE'],
		"RESIZER_SET" => $arParams['LIST_RESIZER_SET'],
	),
	$component
);
?>

</main>
