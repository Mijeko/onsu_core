<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_PRIVATE"));
$APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_PRIVATE"));
?>
<main class="container account-page account-settings">
	<h1><? $APPLICATION->ShowTitle(false) ?></h1>
	<div class="account row">
		<? include $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'personal/left_menu.php' ?>
		<div class="account-content col-xs-12 col-sm-9 col-xl-10">
<?$APPLICATION->IncludeComponent(
	"bitrix:main.profile",
	"bitronic2",
	Array(
		"SET_TITLE" => "Y",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_MODE" => $arParams['AJAX_MODE_PRIVATE'],
		"SEND_INFO" => $arParams["SEND_INFO_PRIVATE"],
		"CHECK_RIGHTS" => $arParams['CHECK_RIGHTS_PRIVATE'],
	),
	$component
);?>
		</div>
	</div>
</main>