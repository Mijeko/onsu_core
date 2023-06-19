<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;
$is_bitronic = false;
if(CModule::IncludeModule('yenisite.bitronic')) $is_bitronic = true;
elseif(CModule::IncludeModule('yenisite.bitroniclite')) $is_bitronic = true;
elseif(CModule::IncludeModule('yenisite.bitronicpro')) 	$is_bitronic = true;

$arParams["SET_TITLE"] = "N";

/**/
if ($this->__folder) {
	$pathToTemplateFolder = $this->__folder;
} else {
	$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));
}
/*COLOR*/
	$arColorSchemes = array ('red', 'green', 'ice', 'metal', 'pink', 'yellow');
	$color_scheme = 'red';
	if($arParams['COLOR_SCHEME'] && in_array($arParams['COLOR_SCHEME'], $arColorSchemes, true))
		$color_scheme = $arParams['COLOR_SCHEME'];
	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/css_color/{$color_scheme}.css");
/*JQUERY*/
	if($arParams['INCLUDE_JQUERY'] == 'Y')
		CJSCore::Init(array("jquery"));
/*SELECTBOX*/
	if(!$is_bitronic) {
		$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/plugins/selectbox.js");
		$APPLICATION->AddHeadScript($pathToTemplateFolder.'/plugins/jquery-ui-1.10.3.custom.min.js');
		$APPLICATION->SetAdditionalCSS($pathToTemplateFolder.'/plugins/ui-lightness/jquery-ui-1.10.3.custom.min.css');
	}
	if ($arParams['USE_MOUSEWHEEL'] != 'N') {
		$APPLICATION->AddHeadScript($pathToTemplateFolder.'/plugins/jquery.mousewheel.js');
	}
		

if(!function_exists('yenisite_date_to_time'))
{
	function yenisite_date_to_time ($date)
	{
		list($date, $time) = explode(" ", $date);
		list($day, $month, $year) = explode(".", $date);
		list($hour, $minute, $second) = explode(":", $time);
		return mktime($hour, $minute, $second, $month, $day, $year);
	}
}
if(!function_exists('yenisite_CATALOG_AVAILABLE'))
{
	function yenisite_CATALOG_AVAILABLE ($arProduct)
	{
		if(!count($arProduct['OFFERS']))
		{
			if ($arProduct['CATALOG_QUANTITY_TRACE'] != 'Y') return true;
			if ($arProduct['CATALOG_CAN_BUY_ZERO'] == 'Y')   return true;
			if ($arProduct['CATALOG_QUANTITY'] > 0)          return true;
				return false;
		}
		else
		{
			if($arProduct['CATALOG_QUANTITY'] > 0) return true;

			foreach ($arProduct['OFFERS'] as $arOffer)
			{
				if ($arOffer['CATALOG_QUANTITY_TRACE'] != 'Y') return true;
				if ($arOffer['CATALOG_CAN_BUY_ZERO'] == 'Y')   return true;
				if ($arOffer['CATALOG_QUANTITY'] > 0)          return true;
			}
		}
		return false;
	}
}
?>
