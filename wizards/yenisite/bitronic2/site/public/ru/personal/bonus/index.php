<?
require $_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php";
$APPLICATION->SetTitle("Р‘РѕРЅСѓСЃС‹ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ");

if(!$USER->IsAuthorized())
{
	$APPLICATION->AuthForm("");
	return;
}
?>
<main class="container account-page account-settings">
	<h1><?$APPLICATION->ShowTitle('h1')?></h1>
	<div class="account row">
		<?include '../left_menu.php';?>
		<div class="account-content col-xs-12 col-sm-9 col-xl-10">
			<?$APPLICATION->IncludeComponent(
	"vbcherepanov:vbcherepanov.bonusaccount", 
	"bonus", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "N",
		"COMPONENT_TEMPLATE" => "bonus",
		"SHOW_INNER_ACCOUNT" => "N",
		"SHOW_BONUS_ACCOUNT" => "Y"
	),
	false
);?>

<?$APPLICATION->IncludeComponent(
	"vbcherepanov:vbcherepanov.bonusdescription",
	"bonushistory",
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"NOTACTIVE" => "N",
		"ORDER" => "TIMESTAMP_X",
		"ORDERDEC" => "ASC",
		"COMPONENT_TEMPLATE" => "bonushistory",
		"SHOW_INNER_ACCOUNT" => "N",
		"SHOW_BONUS_ACCOUNT" => "Y"
	),
	false
);?>
		</div>
	</div>
</main>
<? require $_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php" ?>