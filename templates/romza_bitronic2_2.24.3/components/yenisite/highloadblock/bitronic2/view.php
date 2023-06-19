<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Yenisite\Core\Tools;
use \Bitronic2\Mobile;

global $rz_b2_options;
?>

<main class="container brand-page" data-page="news-item-page">
<? Tools::includeArea('brands', 'banner', false, true, $rz_b2_options['block_show_ad_banners']) ?>
<div class="row">
	<div class="col-xs-12">
		<h1><? $APPLICATION->ShowTitle(false) ?></h1>
<?
$APPLICATION->IncludeComponent("yenisite:highloadblock.view", "bitronic2", Array(
		"BLOCK_ID"   => $arParams['BLOCK_ID'],
		"LIST_URL"   => $arResult['PATH_TO_LIST'],
		"ROW_ID"     => $arResult['VARIABLES']['ID'],
		"ROW_XML_ID" => $arResult['VARIABLES']['XML_ID'],
		"SET_TITLE"  => $arParams['SET_TITLE'],
		"BROWSER_TITLE"     => $arParams['BROWSER_TITLE'],
		"SET_BROWSER_TITLE" => $arParams['SET_BROWSER_TITLE'],
		"ADD_ELEMENT_CHAIN" => $arParams['ADD_ELEMENT_CHAIN'],
		"CATALOG_PATH" => $arParams['PATH_TO_CATALOG'],
		"RESIZER_SET"  => $arParams['VIEW_RESIZER_SET'],
		"SET_404"  => $arParams['SET_404'],
		"SHOW_PROPS_OF_HLB"  => $arParams['SHOW_PROPS_OF_HLB'],
		"PROP_FOR_LINK_COMPONY"  => $arParams['PROP_FOR_LINK_COMPONY'],
		"SET_DESCRIPTION_PAGE"  => $arParams['SET_DESCRIPTION_PAGE'],
		"STR_FOR_DESCRIPTION"  => $arParams['STR_FOR_DESCRIPTION'],
		"SET_KEYWORDS_PAGE"  => $arParams['SET_KEYWORDS_PAGE'],
		"STR_FOR_KEY_WORDS"  => $arParams['STR_FOR_KEY_WORDS'],
		"STR_FOR_BROWSER"  => $arParams['STR_FOR_BROWSER'],
		"ELEMENT_COUNT" => (int)$arParams['ELEMENT_COUNT_BRANDS'] > 0 ? $arParams['ELEMENT_COUNT_BRANDS'] : 20,
		"HOVER-MODE"   =>  Mobile::isMobile() ? 'border-n-shadow' : $rz_b2_options['product-hover-effect'],
	),
	$component
);
?>

	</div><!-- /.col-xs-12 -->
</div><!-- /.row -->

</main>