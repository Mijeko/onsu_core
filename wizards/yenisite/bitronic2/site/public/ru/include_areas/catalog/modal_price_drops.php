<?
if (\Bitrix\Main\Loader::IncludeModule("yenisite.feedback")) {
	$APPLICATION->IncludeComponent(
	"yenisite:feedback.add",
	"modal_price_drops",
	array(
		"IBLOCK_TYPE" => "#FEEDBACK_IBLOCK_TYPE#",
		"IBLOCK" => "#IBLOCK_ID#",
		"SUCCESS_TEXT" => "Спасибо! В случае снижения цены до заданной мы сообщим Вам.",
		"PRINT_FIELDS" => array(
			0 => "PRICE",
			1 => "EMAIL",
			2 => "PRODUCT",
			3 => "PRICE_TYPE_ID",
		),
		"ACTIVE" => "Y",
		"TEXT_SHOW" => "N",
		"TEXT_REQUIRED" => "N",
		"EMAIL" => "EMAIL",
		"COMPONENT_TEMPLATE" => "modal_price_drops",
		"NAME_FIELD" => "",
		"EVENT_NAME" => "PRICE_LOWER_ADMIN",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"COLOR_SCHEME" => "",
		"TITLE" => "",
		"USE_CAPTCHA" => "N",
		"SHOW_SECTIONS" => "N",
		"NAME" => "",
		"PHONE" => "",
		"SECTION_CODE" => "",
		"ELEMENT_ID" => ""
	),
	false
);
}
