<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;
use Bitrix\Iblock;

if (!Loader::includeModule('iblock'))
	return;
if (!Loader::includeModule('highloadblock'))
	return;
global $arComponentParameters;

$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$arProps = array();
if ($iblockExists)
{
	$propertyIterator = Iblock\PropertyTable::getList(array(
		'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'USER_TYPE'),
		'filter' => array(
			'=IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
			'=ACTIVE' => 'Y',
			'=PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
			'=USER_TYPE' => 'directory'
		),
		'order' => array('SORT' => 'ASC', 'NAME' => 'ASC')
	));
	while ($property = $propertyIterator->fetch())
	{
		$propertyCode = (string)$property['CODE'];
		if ($propertyCode == '')
			$propertyCode = $property['ID'];
		$propertyName = '['.$propertyCode.'] '.$property['NAME'];

		$arProps[$propertyCode] = $propertyName;
	}
	unset($propertyCode, $propertyName, $property, $propertyIterator);
}

// new Params
$arTemplateParameters = array(
	/*
	"FILTER_NAME" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("CP_BCSF_FILTER_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "arrFilter",
	),
	*/
	"PATH_FOLDER" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("PATH_FOLDER"),
		"TYPE" => "STRING",
		"DEFAULT" => "/catalog/",
	),
	"CATALOG_FILTER_NAME" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("CP_BCSF_CATALOG_FILTER_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "arrFilter",
	),
	"BRAND_DETAIL" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("BRAND_DETAIL"),
		"TYPE" => "STRING",
		"DEFAULT" => "={SITE_DIR.\"brands/#ID#/\"}",
		"HIDDEN" => "Y"
	),
	"BRAND_LIST" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("BRAND_LIST"),
		"TYPE" => "STRING",
		"DEFAULT" => "={SITE_DIR.\"brands/\"}",
		"HIDDEN" => "Y"
	),
	"BRAND_LIST_TEXT" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("BRAND_LIST_TEXT"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("BRAND_LIST_TEXT_DEFAULT"),
	),
);

// edit BLOCK_ID param
$arTemplateParameters['PROP_CODE'] = array(
	"PARENT" => "BASE",
	"NAME" => GetMessage("IBLOCK_CB_PROP_CODE"),
	"TYPE" => "LIST",
	"VALUES" => $arProps,
	"MULTIPLE" => "N",
	"ADDITIONAL_VALUES" => "Y"
);

$arTemplateParameters['ELEMENT_ID']['HIDDEN'] = "Y";
$arTemplateParameters['ELEMENT_CODE']['HIDDEN'] = "Y";