<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (CModule::IncludeModule("statistic")) {
	$pathToTemplateFolder = $templateFolder;

	$arColorSchemes = array('red', 'green', 'ice', 'metal', 'pink', 'yellow');
	$tmp = COption::GetOptionString('yenisite.market', 'color_scheme');

	$color_scheme = 'red';

	if ($arParams['COLOR_SCHEME'] && in_array($arParams['COLOR_SCHEME'], $arColorSchemes, true))
		$color_scheme = $arParams['COLOR_SCHEME'];
	else if ($arParams['COLOR_SCHEME'] === "blue")
		$color_scheme = 'ice';
	else if (strlen($tmp) != 0) {
		if (($bitronic_color_scheme = getBitronicSettings("COLOR_SCHEME")) && in_array($bitronic_color_scheme, $arColorSchemes))
			$color_scheme = $bitronic_color_scheme;
	}

	// old or new fonts
	if ($arParams['NEW_FONTS'] == 'Y') {
		$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/new_fonts.css");
		$arResult['FONTS'] = "_NEW";
	} else {
		$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/old_fonts.css");
		$arResult['FONTS'] = "";
	}

	if ($arParams['INCLUDE_JQUERY'] == 'Y') CJSCore::Init(array("jquery"));

	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/{$color_scheme}.css");

	$APPLICATION->AddHeadScript($componentPath . "/js/jquery.textchange.min.js");

	global $ys_options;
	?><script>
		YS.GeoIP.OrderProps = {	
			'PERSON_TYPE_1' : {
				'locationID' : <?=(!empty($arParams['P1_LOCATION_ID'])) ? $arParams['P1_LOCATION_ID'] : 5 ?>,
				'cityID' : <?=(!empty($arParams['P1_CITY_ID'])) ? $arParams['P1_CITY_ID'] : 6 ?>},
			'PERSON_TYPE_2' : {
				'locationID' : <?=(!empty($arParams['P2_LOCATION_ID'])) ? $arParams['P2_LOCATION_ID'] : 18 ?>,	
				'cityID' : <?=(!empty($arParams['P2_CITY_ID'])) ? $arParams['P2_CITY_ID'] : 17 ?>}
			
			<?if(isset($ys_options['order']) && $ys_options['order'] == 'one_step'):?>
			, 'ONE_STEP' : true
			<?endif?>
		};
		YS.GeoIP.AutoConfirm = <?if($arParams['AUTOCONFIRM'] == "Y"):?>true<?else:?>false<?endif?>;
	</script><?
	if (strlen($tmp) != 0) {
		$APPLICATION->AddHeadScript($componentPath . "/js/bitronic.js");
	} else {
		$APPLICATION->AddHeadScript($componentPath . "/js/eshop.js");
		$APPLICATION->AddHeadScript($componentPath . "/js/jquery.jgrowl_minimized.js");

		$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/css/jquery.jgrowl.css");
	}
}
?>