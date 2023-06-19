<?
global $rz_b2_options;
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	"bitronic2", 
	array(
		"NUM_CATEGORIES" => "2",
		"TOP_COUNT" => "5",
		"CHECK_DATES" => "N",
		"SHOW_OTHERS" => "N",
		"PAGE" => "#SITE_DIR#catalog/",
		"CATEGORY_0_TITLE" => "Товары",
		"CATEGORY_0" => array(
			0 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "all",
		),
		"CATEGORY_1_TITLE" => "Новости",
		"CATEGORY_1" => array(
			0 => "iblock_news",
		),
		"CATEGORY_1_iblock_catalog" => array(
			0 => "all",
		),
		"CATEGORY_OTHERS_TITLE" => "Другое",
		"SHOW_INPUT" => "Y",
		"CONTAINER_ID" => "search",
		"INPUT_ID" => "search-field",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "75",
		"PREVIEW_HEIGHT" => "75",
		"CONVERT_CURRENCY" => "N",
		"ORDER" => "date",
		"USE_LANGUAGE_GUESS" => "N",
		"RESIZER_SEARCH_TITLE" => "#SMALL_BASKET_ICON_RESIZER_SET#",
		"PRICE_VAT_INCLUDE" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"CURRENCY_ID" => "RUB",
		"COMPONENT_TEMPLATE" => "bitronic2",
		"EXAMPLE_ENABLE" => "Y",
		"EXAMPLES" => array(
			0 => "Nexus 5",
			1 => "iPhone 6",
		),
		"SHOW_CATEGORY_SWITCH" => ($rz_b2_options["block_search_category"] !== "N" ? "Y" : "N"),
	),
	false
);