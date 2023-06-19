<?
$bLite = CModule::IncludeModule('yenisite.bitronic2lite');
if (CModule::IncludeModule('sale') && !$bLite):
$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket.line", 
	"basket", 
	array(
		"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
		"SHOW_NUM_PRODUCTS" => "Y",
		"SHOW_TOTAL_PRICE" => "Y",
		"SHOW_EMPTY_VALUES" => "Y",
		"SHOW_PERSONAL_LINK" => "N",
		"PATH_TO_PERSONAL" => SITE_DIR."personal/settings.php",
		"SHOW_AUTHOR" => "N",
		"PATH_TO_REGISTER" => SITE_DIR."auth/",
		"PATH_TO_PROFILE" => SITE_DIR."personal/settings.php",
		"SHOW_PRODUCTS" => "Y",
		"SHOW_DELAY" => "Y",
		"SHOW_NOTAVAIL" => "Y",
		"SHOW_SUBSCRIBE" => "Y",
		"SHOW_IMAGE" => "N",
		"SHOW_PRICE" => "Y",
		"SHOW_SUMMARY" => "Y",
		"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
		"POSITION_FIXED" => "N",
		"RESIZER_BASKET_ICON" => "#SMALL_BASKET_ICON_RESIZER_SET#",
	),
	false
);
endif;
if ($bLite && CModule::IncludeModule('yenisite.market')):
$APPLICATION->IncludeComponent(
	"yenisite:catalog.basket.small",
	"basket",
	array(
		"VALUTA" => "Р",
		"BASKET_URL" => SITE_DIR."personal/order/",
		"RESIZER_BASKET_ICON" => "#SMALL_BASKET_ICON_RESIZER_SET#"
	),
	false
);
endif;
