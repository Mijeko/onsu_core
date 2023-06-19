<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

$arResizerSets = array();

if (Loader::includeModule("yenisite.resizer2")) {
	$rsSets = CResizer2Set::GetList();
	while ($arSet = $rsSets->Fetch()) {
		$arResizerSets[$arSet['id']] = "[${arSet['id']}] " . $arSet["NAME"];
	}
}

$arTemplateParameters = array(
	"RESIZER_SET_SMALL" => array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("RESIZER_SET_SMALL"),
		"TYPE" => "LIST",
		"VALUES" => $arResizerSets,
		"DEFAULT" => "4",
	),
	"RESIZER_SET_BIG" => array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("RESIZER_SET_BIG"),
		"TYPE" => "LIST",
		"VALUES" => $arResizerSets,
		"DEFAULT" => "1",
	),
);

$arTemplateParameters['SECTION_FIELDS'] =
$arTemplateParameters['SECTION_USER_FIELDS'] =
$arTemplateParameters['PRICE_CODE'] =
$arTemplateParameters['PRODUCT_PROPERTIES'] = array(
	'TYPE' => 'LIST',
	'VALUES' => array(''),
	'DEFAULT' => '',
	'HIDDEN' => 'Y'
);

$arTemplateParameters['SECTION_URL'] =
$arTemplateParameters['DETAIL_URL'] =
$arTemplateParameters['BASKET_URL'] =
$arTemplateParameters['ACTION_VARIABLE'] =
$arTemplateParameters['PRODUCT_ID_VARIABLE'] =
$arTemplateParameters['PRODUCT_QUANTITY_VARIABLE'] =
$arTemplateParameters['PRODUCT_PROPS_VARIABLE'] =
$arTemplateParameters['SECTION_ID_VARIABLE'] =
$arTemplateParameters['LINE_ELEMENT_COUNT'] =
$arTemplateParameters['SHOW_PRICE_COUNT'] = array(
	'TYPE' => 'STRING',
	'DEFAULT' => '',
	'HIDDEN' => 'Y'
);

$arTemplateParameters['DISPLAY_COMPARE'] =
$arTemplateParameters['USE_PRICE_COUNT'] =
$arTemplateParameters['PRICE_VAT_INCLUDE'] =
$arTemplateParameters['USE_PRODUCT_QUANTITY'] =
$arTemplateParameters['USE_MAIN_ELEMENT_SECTION'] =
$arTemplateParameters['HIDE_NOT_AVAILABLE'] =
$arTemplateParameters['CONVERT_CURRENCY'] = array(
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N',
	'HIDDEN' => 'Y'
);
