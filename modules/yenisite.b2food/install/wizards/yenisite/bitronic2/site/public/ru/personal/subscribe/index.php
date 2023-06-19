<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Настройки подписки");
if (!$USER->IsAuthorized()) {
	$APPLICATION->AuthForm("");
	return;
}
?>
	<main class="container account-page account-settings">
		<h1><? $APPLICATION->ShowTitle() ?></h1>
		<div class="account row">
			<? include '../left_menu.php'; ?>
			<? if (CModule::IncludeModule('subscribe')): ?>
			<div class="account-content col-xs-12 col-sm-9 col-xl-10">
				<? $APPLICATION->IncludeComponent("bitrix:subscribe.edit", ".default", Array(
					"AJAX_MODE" => "N",
					"SHOW_HIDDEN" => "N",
					"ALLOW_ANONYMOUS" => "Y",
					"SHOW_AUTH_LINKS" => "Y",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"SET_TITLE" => "N",
					"AJAX_OPTION_SHADOW" => "Y",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
				),
					false
				); ?>
			</div>
		</div>
		<? endif ?>
	</main>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>