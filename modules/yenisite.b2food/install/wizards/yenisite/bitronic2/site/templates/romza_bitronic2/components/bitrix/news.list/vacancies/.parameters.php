<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters['USE_SEARCH']
= $arTemplateParameters['USE_RSS']
= $arTemplateParameters['USE_RATING']
= $arTemplateParameters['USE_CATEGORIES']
= $arTemplateParameters['USE_REVIEW']
= $arTemplateParameters['USE_FILTER']
= array(
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N',
	'HIDDEN' => 'Y',
);

$arTemplateParameters['ACTIVE_DATE_FORMAT']
= $arTemplateParameters['FIELD_CODE']
= $arTemplateParameters['PROPERTY_CODE']
= array(
	'TYPE' => 'LIST',
	'VALUES' => array(''),
	'DEFAULT' => '',
	'HIDDEN' => 'Y'
);
?>
