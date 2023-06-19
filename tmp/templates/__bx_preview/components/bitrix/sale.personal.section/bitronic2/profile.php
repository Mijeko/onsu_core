<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

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
	"bitrix:sale.personal.profile.list",
	"bitronic2",
	array(
		"PATH_TO_DETAIL" => $arResult['PATH_TO_PROFILE_DETAIL'],
		"PATH_TO_DELETE" => $arResult['PATH_TO_PROFILE_DELETE'],
		"PER_PAGE" => $arParams["PER_PAGE"],
		"SET_TITLE" =>$arParams["SET_TITLE"],
	),
	$component
);
?>
		</div>
	</div>
</main>
