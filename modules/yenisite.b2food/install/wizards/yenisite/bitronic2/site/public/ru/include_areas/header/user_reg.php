<?
$APPLICATION->IncludeComponent(
	"bitrix:system.auth.registration", 
	"modal", 
	array(
		"URL_SHOP_RULES" => SITE_DIR.'about/',
		"EMPTY" => $arParams["EMPTY"],
	),
	false
);