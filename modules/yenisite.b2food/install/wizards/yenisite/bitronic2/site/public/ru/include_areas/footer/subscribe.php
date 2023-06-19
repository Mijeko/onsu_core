<?if(CModule::IncludeModule('subscribe')):?>
<?$APPLICATION->IncludeComponent("bitrix:subscribe.form", ".default", Array(
	"COMPONENT_TEMPLATE" => ".default",
	"USE_PERSONALIZATION" => "Y",
	"SHOW_HIDDEN" => "N",
	"PAGE" => "#SITE_DIR#personal/subscribe/subscr_edit.php",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "3600",
),
	false
);?>
<?endif?>
