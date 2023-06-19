<?
if (\Bitrix\Main\Loader::IncludeModule("yenisite.feedback")) {
	$APPLICATION->IncludeComponent(
	"yenisite:feedback.add",
	"modal_price_cry",
	array(
		"IBLOCK_TYPE" => "#FEEDBACK_IBLOCK_TYPE#",
		"IBLOCK" => "#IBLOCK_ID#",
		"SUCCESS_TEXT" => "Спасибо! После обработки запроса наш специалист свяжется с Вами.",
		"PRINT_FIELDS" => array(
			0 => "PRICE",
			1 => "EMAIL",
			2 => "PRODUCT",
			3 => "PRICE_TYPE_ID",
			4 => "URL",
			5 => "PHONE",
			6 => "FIO",
			7 => "PRICE_OTHER",
		),
		"ACTIVE" => "Y",
		"EVENT_NAME" => "FOUND_CHEAP",
		"EMAIL" => "EMAIL",
		"PHONE" => "PHONE",
		"NAME" => "FIO",
		"COMPONENT_TEMPLATE" => "modal_price_cry",
		"NAME_FIELD" => "",
		"COLOR_SCHEME" => "",
		"TITLE" => "",
		"USE_CAPTCHA" => "N",
		"TEXT_SHOW" => "N",
		"TEXT_REQUIRED" => "N",
		"SHOW_SECTIONS" => "N",
		"SECTION_CODE" => "",
		"ELEMENT_ID" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300"
	),
	false
);
}