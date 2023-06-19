<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if (!Loader::includeModule('iblock'))
	return;
$boolCatalog = Loader::includeModule('catalog');
$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock=array();
$rsIBlock = CIBlock::GetList(array("sort" => "asc"), array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

// RESIZER
$resizer_sets_list = array () ;
if(Loader::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$resizer_sets_list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}

$arTabs = array('NEW', 'HIT', 'SALE', 'BESTSELLER');


$arSKU = false;
$boolSKU = false;
if ($boolCatalog && (isset($arCurrentValues['IBLOCK_ID']) && 0 < intval($arCurrentValues['IBLOCK_ID'])))
{
	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);
	$boolSKU = !empty($arSKU) && is_array($arSKU);

	$rsProps = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y')
	);
	$arProps_ALL = array();
	while ($arProp = $rsProps->Fetch())
	{
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		$arProps_ALL[$arProp['CODE']] = $strPropName;
	}
}

// Fetch component parameters
$arComponentParameters = array();
if (Loader::includeModule('yenisite.core')) {
	$arComponentParameters = \Yenisite\Core\Tools::getComponentParameters($componentPath);
}

$arTemplateParameters['IBLOCK_TYPE'] = array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("IBLOCK_TYPE"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	);
$arTemplateParameters['IBLOCK_ID'] = array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("IBLOCK_IBLOCK"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlock,
		"REFRESH" => "Y",
	);


if ($boolSKU)
{
	$arAllOfferPropList = array();
	$arFileOfferPropList = array(
		'-' => GetMessage('CP_BCS_TPL_PROP_EMPTY')
	);
	$arTreeOfferPropList = array(
		'-' => GetMessage('CP_BCS_TPL_PROP_EMPTY')
	);
	$rsProps = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arSKU['IBLOCK_ID'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsProps->Fetch())
	{
		if ($arProp['ID'] == $arSKU['SKU_PROPERTY_ID'])
			continue;
		$arProp['USER_TYPE'] = (string)$arProp['USER_TYPE'];
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE'])
			$arProp['CODE'] = $arProp['ID'];
		$arAllOfferPropList[$arProp['CODE']] = $strPropName;
		if ('F' == $arProp['PROPERTY_TYPE'])
			$arFileOfferPropList[$arProp['CODE']] = $strPropName;
		if ('N' != $arProp['MULTIPLE'])
			continue;
		if (
			'L' == $arProp['PROPERTY_TYPE']
			|| 'E' == $arProp['PROPERTY_TYPE']
			|| ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
		)
			$arTreeOfferPropList[$arProp['CODE']] = $strPropName;
	}
	$arTemplateParameters['OFFER_TREE_PROPS'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BCS_TPL_OFFER_TREE_PROPS'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'N',
		'DEFAULT' => '-',
		'VALUES' => $arTreeOfferPropList
	);
}
// ARTICUL
$arTemplateParameters['ARTICUL_PROP'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('ARTICUL_PROP'),
	'TYPE' => 'LIST',
	'MULTIPLE' => 'N',
	'DEFAULT' => 'ARTICUL',
	'VALUES' => $arProps_ALL
);
if(Loader::includeModule('yenisite.favorite'))
{
	$arTemplateParameters['DISPLAY_FAVORITE'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('DISPLAY_FAVORITE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'HIDDEN' => 'Y',
	);
}
if(Loader::includeModule('yenisite.oneclick'))
{
	$arTemplateParameters['DISPLAY_ONECLICK'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('DISPLAY_ONECLICK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'HIDDEN' => 'Y',
	);
}

$arTemplateParameters['SHOW_AMOUNT_STORE'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('SHOW_AMOUNT_STORE'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N'
);
$arTemplateParameters['PARTIAL_PRODUCT_PROPERTIES'] = array(
	'PARENT' => 'PRICES',
	'NAME' => GetMessage('PARTIAL_PRODUCT_PROPERTIES'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);

$arTemplateParameters["HIDE_ICON_SLIDER"] = array(
	"PARENT" => "VISUAL",
	"NAME" 	 => GetMessage('BITRONIC2_HIDE_ICON_SLIDER'),
	"TYPE"	 => "CHECKBOX",
	"DEFAULT" => 'N',
	'HIDDEN' => 'Y',
);

$arTemplateParameters["RESIZER_SECTION_ICON"] = array(
	"PARENT" => "VISUAL",
	"NAME" => GetMessage("RESIZER_SECTION_ICON"),
	"TYPE" => "LIST",
	"VALUES" => $resizer_sets_list,
	"DEFAULT" => "5",
);
/*
$arTemplateParameters["SHOW_TAB_CNT"] = array(
	"PARENT" => "BASE",
	"NAME" => GetMessage("BITRONIC2_MAINSPEC_SHOW_TAB_CNT"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
);
*/
$arTemplateParameters['PAGE_ELEMENT_COUNT']['PARENT'] = 'BASE';
$arTemplateParameters['PAGE_ELEMENT_COUNT']['NAME'] = GetMessage('BITRONIC2_MAINSPEC_PAGE_ELEMENT_COUNT');

foreach ($arTabs as $tab) {
	$arComponentParameters['PARAMETERS']['TAB_TEXT_'.$tab]['DEFAULT'] = GetMessage("BITRONIC2_MAINSPEC_TAB_{$tab}");
	$arTemplateParameters['TAB_TEXT_'.$tab] = $arComponentParameters['PARAMETERS']['TAB_TEXT_'.$tab];
}

//HIDE params - not used params
	$arTemplateParameters['LIST_PRICE_SORT']['HIDDEN'] = 'Y';
	$arTemplateParameters['SHOW_ELEMENT']['HIDDEN'] = 'Y';
	$arTemplateParameters['OFFERS_FIELD_CODE']['HIDDEN'] = 'Y';
	$arTemplateParameters['OFFERS_PROPERTY_CODE']['HIDDEN'] = 'Y';
	$arTemplateParameters['USE_PRICE_COUNT']['HIDDEN'] = 'Y';
	$arTemplateParameters['HIDE_BUY_IF_PROPS']['HIDDEN'] = 'Y';
	$arTemplateParameters['OFFERS_CART_PROPERTIES']['HIDDEN'] = 'Y';
	$arTemplateParameters['LINE_ELEMENT_COUNT']['HIDDEN'] = 'Y';
	$arTemplateParameters['SET_TITLE']['HIDDEN'] = 'Y';
	$arTemplateParameters['DISPLAY_COMPARE']['HIDDEN'] = 'Y';
	$arTemplateParameters['ADD_SECTIONS_CHAIN']['HIDDEN'] = 'Y';
	$arTemplateParameters['INCLUDE_JQUERY']['HIDDEN'] = 'Y';
	$arTemplateParameters['META_KEYWORDS']['HIDDEN'] = 'Y';
	$arTemplateParameters['META_DESCRIPTION']['HIDDEN'] = 'Y';
	$arTemplateParameters['BROWSER_TITLE']['HIDDEN'] = 'Y';
	$arTemplateParameters['BLOCK_VIEW_MODE']['HIDDEN'] = 'Y';
	$arTemplateParameters['TABS_INDEX']['HIDDEN'] = 'Y';
	$arTemplateParameters['COLOR_SCHEME']['HIDDEN'] = 'Y';
	$arTemplateParameters['IMAGE_SET_BIG']['HIDDEN'] = 'Y';
	$arTemplateParameters['PRODUCT_DISPLAY_MODE']['HIDDEN'] = 'Y';
	$arTemplateParameters['USE_MOUSEWHEEL']['HIDDEN'] = 'Y';

if (\Bitrix\Main\Loader::includeModule('yenisite.geoipstore')) {
	$arTemplateParameters['PRICE_CODE'] = array(
		'TYPE' => 'STRING',
		'NAME' => $arComponentParameters['PARAMETERS']['PRICE_CODE']['NAME'],
		'MULTIPLE' => 'N'
	);
}
unset($arComponentParameters);
