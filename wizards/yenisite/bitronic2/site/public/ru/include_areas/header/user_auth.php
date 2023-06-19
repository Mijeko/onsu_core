<?
$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form", 
	"modal", 
	array(
		"REGISTER_URL" => "#SITE_DIR#personal/?register=yes",
		"FORGOT_PASSWORD_URL" => "#SITE_DIR#personal/profile/",
		"PROFILE_URL" => "#SITE_DIR#personal/profile/",
		"SHOW_ERRORS" => "Y",
		"RESIZER_USER_AVA_ICON" => "#PERSONAL_AVA_RESIZER_SET#"
	),
	false
);