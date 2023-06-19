<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");?>
<main class="container site-map-page" data-page="site-map-page">
	<div class="row">
		<div class="col-xs-12">
		<h1><?$APPLICATION->ShowTitle()?></h1>
	<?
	$APPLICATION->IncludeComponent("bitrix:menu", "sitemap", array(
		"ROOT_MENU_TYPE" => "top",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
        "CHILD_MENU_TYPE" => "top_sub",
        "MAX_LEVEL" => "2",
		),
		false
	);

	$APPLICATION->IncludeComponent("bitrix:menu", "sitemap", array(
		"ROOT_MENU_TYPE" => "catalog",
		"USE_EXT" => "Y",
		"SECOND" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400"
		),
		false
	);
	?>
		</div>
	</div>
</main>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");