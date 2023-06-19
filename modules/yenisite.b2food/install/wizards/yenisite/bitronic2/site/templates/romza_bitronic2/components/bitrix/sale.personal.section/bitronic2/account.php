<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ($arParam['SHOW_ACCOUNT_PAGE'] === 'N')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}

global $rz_b2_options;
use Bitrix\Main\Localization\Loc;

$APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_ACCOUNT"));
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_ACCOUNT"));

Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/components/bitrix/sale.personal.section/bitronic2/js/account.js');
?>

<main class="container account-personal-money">
	<h1><? $APPLICATION->ShowTitle(false) ?></h1>
	<div class="account row">
		<? include $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'personal/left_menu.php' ?>
		<div class="bx-content col-xs-12 col-sm-9 col-xl-10">
<?
if ($arParam['SHOW_ACCOUNT_COMPONENT'] !== 'N')
{
	$APPLICATION->IncludeComponent(
		"bitrix:sale.personal.account",
		"",
		Array(
			"SET_TITLE" => "N"
		),
		$component
	);
}
if ($arParam['SHOW_ACCOUNT_PAY_COMPONENT'] !== 'N' && $USER->IsAuthorized())
{
	?>

    <div class="title-h3"><?=Loc::getMessage("SPS_BUY_MONEY")?></div>

	<?
	$APPLICATION->IncludeComponent(
		"bitrix:sale.account.pay",
		"",
		Array(
			"COMPONENT_TEMPLATE" => ".default",
			"REFRESHED_COMPONENT_MODE" => "Y",
			"ELIMINATED_PAY_SYSTEMS" => $arParams['ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS'],
			"PATH_TO_BASKET" => $arParams['PATH_TO_BASKET'],
			"PATH_TO_PAYMENT" => $arParams['PATH_TO_PAYMENT'],
			"PERSON_TYPE" => $arParams['ACCOUNT_PAYMENT_PERSON_TYPE'],
			"REDIRECT_TO_CURRENT_PAGE" => "N",
			"SELL_AMOUNT" => $arParams['ACCOUNT_PAYMENT_SELL_TOTAL'],
			"SELL_CURRENCY" => $arParams['ACCOUNT_PAYMENT_SELL_CURRENCY'],
			"SELL_SHOW_FIXED_VALUES" => $arParams['ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES'],
			"SELL_SHOW_RESULT_SUM" =>  $arParams['ACCOUNT_PAYMENT_SELL_SHOW_RESULT_SUM'] ? $arParams['ACCOUNT_PAYMENT_SELL_SHOW_RESULT_SUM'] : 'Y' ,
			"SELL_TOTAL" => $arParams['ACCOUNT_PAYMENT_SELL_TOTAL'],
			"SELL_USER_INPUT" => $arParams['ACCOUNT_PAYMENT_SELL_USER_INPUT'],
			"SELL_VALUES_FROM_VAR" => "N",
			"SET_TITLE" => "N",
            'PAYMENT_RESIZER_SET' => $arParams['PAYMENT_RESIZER_SET'],
		),
		$component
	);
}
?>

		</div>
	</div>
</main>
