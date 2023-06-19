<?
if(\Bitrix\Main\Loader::includeModule('yenisite.core') && !IsModuleInstalled('yenisite.bitronic2lite'))
{
global $rz_b2_options;

if ($rz_b2_options['geoip_currency'] !== 'N' && $rz_b2_options['currency-switcher'] === 'N') {
	if (isset($_COOKIE['RZ_CURRENCY'])) {
		unset($_COOKIE['RZ_CURRENCY']);
	}
	if (isset($_GET['RZ_CURRENCY_NEW'])) {
		unset($_GET['RZ_CURRENCY_NEW']);
	}
	if (isset($_REQUEST['RZ_CURRENCY_NEW'])) {
		unset($_REQUEST['RZ_CURRENCY_NEW']);
	}
}

$rz_b2_options['active-currency'] = $APPLICATION->IncludeComponent(
		"yenisite:currency.switcher", 
		"bitronic2", 
		array(
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "86400",
			"CURRENCY_LIST" => array(),
			"DEFAULT_CURRENCY" => "BASE",
			"GEOIP_CURRENCY" => $rz_b2_options["geoip_currency"] === "Y" ? $_SESSION["GEOIP_CURRENCY_ID"] : false,
		),
		false
	);
$rz_b2_options['convert_currency'] = ($rz_b2_options['currency-switcher'] === 'Y' || $rz_b2_options['geoip_currency'] === 'Y');
}
?>