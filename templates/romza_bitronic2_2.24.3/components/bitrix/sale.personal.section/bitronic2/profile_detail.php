<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!CRZBitronic2Settings::isPro()) return;

use Bitrix\Main\Localization\Loc;
global $rz_b2_options;

if ($arResult['VARIABLES']['ID'] === 'add'):
	include 'profile_add.php';
else:

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
	"bitrix:sale.personal.profile.detail",
	"bitronic2",
	array(
		"PATH_TO_LIST" => $arResult["PATH_TO_PROFILE"],
		"PATH_TO_DETAIL" => $arResult["PATH_TO_PROFILE_DETAIL"],
		"SET_TITLE" =>$arParams["SET_TITLE"],
		"USE_AJAX_LOCATIONS" => $arParams['USE_AJAX_LOCATIONS_PROFILE'],
		"COMPATIBLE_LOCATION_MODE" => $arParams['COMPATIBLE_LOCATION_MODE_PROFILE'],
		"ID" => $arResult["VARIABLES"]["ID"],
	),
	$component
);
?>
		</div>
	</div>
</main>
<? endif ?>
