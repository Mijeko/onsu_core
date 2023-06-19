<?php
require $_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php";
$APPLICATION->SetTitle("Прайс-лист");
global $rz_b2_options;
?>
<main class="container">
	<div class="row">
		<div class="col-xs-12">
<?if(CModule::IncludeModule('yenisite.pricegen')):?>
<?$APPLICATION->IncludeComponent(
	"yenisite:catalog.price_generator", 
	"bitronic2", 
	array(
		"IBLOCK_TYPE" => array(
			0 => "catalog",
		),
		"IBLOCK_ID" => array(
			0 => "#CATALOG_IBLOCK_ID#",
			1 => "",
		),
		"PAGE_ELEMENT_COUNT" => "30",
		"FILE_NAME" => "#SITE_DIR#pricelist/price" . ($rz_b2_options['GEOIP']['ITEM']['ID'] ?: ''),
		"FILE_TYPE" => "xls",
		"FILTER_NAME" => "arrFilter",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"CACHE_FILTER" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"COMPONENT_TEMPLATE" => "bitronic2"
	),
	false
);?>
<?else:?>
			Модуль <a class="link-bd link-std" href="http://marketplace.1c-bitrix.ru/solutions/yenisite.pricegen/">"Генератор файла прайс-листа"</a> не установлен.
<?endif?>
		</div>
	</div>
</main>
<? require $_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php" ?>