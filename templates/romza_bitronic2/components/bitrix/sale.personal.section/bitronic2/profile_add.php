<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!CRZBitronic2Settings::isPro()) return;

use Bitrix\Main\Localization\Loc;

$APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_PROFILE"));
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_PROFILE"));
?>
<main class="container account-page account-profiles">
	<h1><? $APPLICATION->ShowTitle(false) ?></h1>
	<div class="account row">
		<? include $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'personal/left_menu.php' ?>
		<div class="account-content col-xs-12 col-sm-9 col-xl-10">
<?
$APPLICATION->IncludeComponent(
	"yenisite:sale.personal.profile.add",
	"bitronic2",
	array(
		"PATH_TO_LIST" => $arResult["PATH_TO_PROFILE"],
		"PATH_TO_DETAIL" => $arResult["PATH_TO_PROFILE_DETAIL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"USE_AJAX_LOCATIONS" => $arParams['USE_AJAX_LOCATIONS_PROFILE'],
		"AJAX_LOCATIONS_TEMPLATE" => $arParams['AJAX_LOCATIONS_TEMPLATE'],
		"COMPATIBLE_LOCATION_MODE" => $arParams['COMPATIBLE_LOCATION_MODE_PROFILE']
	),
	$component
);
?>
		</div>
	</div>
</main>
