<?
use Bitronic2\Mobile;

Mobile::Init();
$setMobile = (Mobile::isMobile()) ? 'Y' : 'N';
?>
<?$APPLICATION->IncludeComponent("yenisite:settings.panel", ".default", array(
		"SOLUTION" => "#SOLUTION_CODE#",
		"SETTINGS_CLASS" => "CRZBitronic2Settings",
		"GLOBAL_VAR" => "rz_b2_options",
		"EDIT_SETTINGS" => array(),
		"SET_MOBILE" => $setMobile,
	),
	false
);