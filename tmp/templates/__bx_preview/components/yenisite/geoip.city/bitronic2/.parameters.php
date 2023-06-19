<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters['YOURCITY_POPUP'] = array(
	"PARENT" => "YS_LOCATOR",
	"TYPE" => "CHECKBOX",
	"NAME" => GetMessage('YOURCITY_POPUP'),
	"DEFAULT" => "Y",
);

//HIDE params - not used params
	$arTemplateParameters['COLOR_SCHEME']['HIDDEN'] = 'Y';
	$arTemplateParameters['NEW_FONTS']['HIDDEN'] = 'Y';
	$arTemplateParameters['P1_LOCATION_ID']['HIDDEN'] = 'Y';
	$arTemplateParameters['P1_CITY_ID']['HIDDEN'] = 'Y';
	$arTemplateParameters['P2_LOCATION_ID']['HIDDEN'] = 'Y';
	$arTemplateParameters['P2_CITY_ID']['HIDDEN'] = 'Y';