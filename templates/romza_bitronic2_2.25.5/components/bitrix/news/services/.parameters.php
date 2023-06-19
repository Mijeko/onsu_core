<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Iblock;

$arResizerSets = array();

if (Loader::includeModule("yenisite.resizer2")) {
	$rsSets = CResizer2Set::GetList();
	while ($arSet = $rsSets->Fetch()) {
		$arResizerSets[$arSet['id']] = "[${arSet['id']}] " . $arSet["NAME"];
	}
}

if (!Loader::includeModule('iblock'))
	return;
$catalogIncluded = Loader::includeModule('catalog');
$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$arPrice = array();
if ($catalogIncluded) {
	$arPrice = CCatalogIBlockParameters::getPriceTypesList();
} else {
	$arProperty_N = array();
	if ($iblockExists) {
		$propertyIterator = Iblock\PropertyTable::getList(array(
			'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE'),
			'filter' => array('=IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], '=ACTIVE' => 'Y'),
			'order' => array('SORT' => 'ASC', 'NAME' => 'ASC')
		));
		while ($property = $propertyIterator->fetch()) {
			$propertyCode = (string)$property['CODE'];
			if ($propertyCode == '') {
				$propertyCode = $property['ID'];
			}
			$propertyName = '['.$propertyCode.'] '.$property['NAME'];

			if ($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_NUMBER) {
				$arProperty_N[$propertyCode] = $propertyName;
			}
		}
		unset($propertyCode, $propertyName, $property, $propertyIterator);
	}
	$arPrice = $arProperty_N;
}

$arTemplateParameters = array(
	/*"DISPLAY_DATE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PICTURE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_PICTURE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PREVIEW_TEXT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TEXT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"USE_SHARE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_USE_SHARE"),
		"TYPE" => "CHECKBOX",
		"MULTIPLE" => "N",
		"VALUE" => "Y",
		"DEFAULT" =>"N",
		"REFRESH"=> "Y",
	),
	*/
	"RESIZER_SERVICE_LIST" => array(
		"NAME" => GetMessage("RESIZER_NEWS_LIST"),
		"TYPE" => "LIST",
		"VALUES" => $arResizerSets,
		"DEFAULT" => "4",
	),
	
	"RESIZER_SERVICE_DETAIL" => array(
		"NAME" => GetMessage("RESIZER_NEWS_DETAIL"),
		"TYPE" => "LIST",
		"VALUES" => $arResizerSets,
		"DEFAULT" => "2",
	),
	"PRICE_CODE" => array(
		"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arPrice,
	),
);
if ($catalogIncluded)
{
	$arTemplateParameters['CONVERT_CURRENCY'] = array(
		'NAME' => GetMessage('CP_BC_CONVERT_CURRENCY'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	);

	if (isset($arCurrentValues['CONVERT_CURRENCY']) && $arCurrentValues['CONVERT_CURRENCY'] == 'Y')
	{
		$arTemplateParameters['CURRENCY_ID'] = array(
			'NAME' => GetMessage('CP_BC_CURRENCY_ID'),
			'TYPE' => 'LIST',
			'VALUES' => Currency\CurrencyManager::getCurrencyList(),
			'DEFAULT' => Currency\CurrencyManager::getBaseCurrency(),
			"ADDITIONAL_VALUES" => "Y",
		);
	}
}
/*
if ($arCurrentValues["USE_SHARE"] == "Y")
{
	$arTemplateParameters["SHARE_HIDE"] = array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_HIDE"),
		"TYPE" => "CHECKBOX",
		"VALUE" => "Y",
		"DEFAULT" => "N",
	);

	$arTemplateParameters["SHARE_TEMPLATE"] = array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_TEMPLATE"),
		"DEFAULT" => "",
		"TYPE" => "STRING",
		"MULTIPLE" => "N",
		"COLS" => 25,
		"REFRESH"=> "Y",
	);
	
	if (strlen(trim($arCurrentValues["SHARE_TEMPLATE"])) <= 0)
		$shareComponentTemlate = false;
	else
		$shareComponentTemlate = trim($arCurrentValues["SHARE_TEMPLATE"]);

	include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/bitrix/main.share/util.php");

	$arHandlers = __bx_share_get_handlers($shareComponentTemlate);

	$arTemplateParameters["SHARE_HANDLERS"] = array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SYSTEM"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arHandlers["HANDLERS"],
		"DEFAULT" => $arHandlers["HANDLERS_DEFAULT"],
	);

	$arTemplateParameters["SHARE_SHORTEN_URL_LOGIN"] = array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SHORTEN_URL_LOGIN"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
	
	$arTemplateParameters["SHARE_SHORTEN_URL_KEY"] = array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SHORTEN_URL_KEY"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
}*/

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

$arTemplateParameters['LIST_ACTIVE_DATE_FORMAT']
= $arTemplateParameters['LIST_FIELD_CODE']
= $arTemplateParameters['LIST_PROPERTY_CODE']
= $arTemplateParameters['DETAIL_ACTIVE_DATE_FORMAT']
= array(
	'TYPE' => 'LIST',
	'VALUES' => array(''),
	'DEFAULT' => '',
	'HIDDEN' => 'Y'
);

?>