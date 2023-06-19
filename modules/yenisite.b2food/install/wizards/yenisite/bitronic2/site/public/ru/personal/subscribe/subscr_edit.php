<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
?>
	<main class="container account-page account-settings">
		<h1><? $APPLICATION->ShowTitle() ?></h1>
		<? if (CModule::IncludeModule('subscribe')): ?>
			<div class="account-content col-xs-12 col-xl-12">
				<? $APPLICATION->IncludeComponent(
					"bitrix:subscribe.edit",
					".default",
					Array(
						"SHOW_HIDDEN" => "N",
						"ALLOW_ANONYMOUS" => "Y",
						"SHOW_AUTH_LINKS" => "Y",
						"CACHE_TIME" => "36000000",
						"SET_TITLE" => "Y"
					)
				); ?>
			</div>
		<? endif ?>
	</main>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>