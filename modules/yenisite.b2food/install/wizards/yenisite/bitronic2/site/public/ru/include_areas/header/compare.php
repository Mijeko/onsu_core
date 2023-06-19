<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.list", 
	"header", 
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "#CATALOG_IBLOCK_ID#",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"DETAIL_URL" => "#SITE_DIR#catalog/#ELEMENT_CODE#.html",
		"COMPARE_URL" => "#SITE_DIR#catalog/compare/#QUERY#/",
		"NAME" => "CATALOG_COMPARE_LIST",
		"AJAX_OPTION_ADDITIONAL" => "",
		"RESIZER_SET_COMPARE" => "#SMALL_BASKET_ICON_RESIZER_SET#",
		"COMPONENT_TEMPLATE" => "header",
		"SHOW_VOTING" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id"
	),
	false
);
