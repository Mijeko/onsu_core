<? global $rz_b2_options;

$bPro = CRZBitronic2Settings::isPro($bGeoipStore = true);

if ($rz_b2_options['block_show_geoip'] == 'Y' && CModule::IncludeModule('yenisite.geoip')): ?>
	<? ob_start() ?>
	<? $APPLICATION->IncludeComponent(
		"yenisite:geoip.city",
		"bitronic2",
		array(
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "360000",
			"AUTOCONFIRM" => "Y",
			"DISABLE_CONFIRM_POPUP" => "Y",
			"COMPONENT_TEMPLATE" => "bitronic2",
			"INCLUDE_JQUERY" => "N",
			"UNITE_WITH_STORE" => ($bPro && $rz_b2_options["geoip_unite"] == "Y") ? "Y" : "N",
		),
		false
	); ?>
	<? $geoip = ob_get_clean() ?>
<? endif ?>
<? if (CModule::IncludeModule('catalog') && $bPro): ?>
	<?
	$arRes = $APPLICATION->IncludeComponent(
		"yenisite:geoip.store",
		"bitronic2",
		array(
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "360000",
			"COLOR_SCHEME" => '',
			"INCLUDE_JQUERY" => "N",
			"NEW_FONTS" => "Y",
			"ONLY_GEOIP" => $rz_b2_options["geoip_unite"],
			"DETERMINE_CURRENCY" => $rz_b2_options["geoip_currency"],
		),
		false
	);
	$rz_b2_options['GEOIP'] = $arRes;
    CRZBitronic2CatalogUtils::checkDifPriceAndSetBasketItems($rz_b2_options['GEOIP']);
	?>
<? endif; ?>
<? if ($geoip) echo $geoip ?>