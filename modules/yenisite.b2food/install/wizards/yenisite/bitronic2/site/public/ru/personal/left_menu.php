<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"user_left", 
	array(
		"ROOT_MENU_TYPE" => "user",
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "",
		"USE_EXT" => "N",
		"MENU_CACHE_TYPE" => "A",
		"CACHE_SELECTED_ITEMS" => "N",
		"MENU_CACHE_TIME" => "604800",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"RESIZER_PERSONAL_AVATAR" => "#PERSONAL_AVA_RESIZER_SET#" //TODO
	),
	false
);?>