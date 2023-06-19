<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arPrice = array();
if(CModule::IncludeModule("catalog"))
{
	$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr=$rsPrice->Fetch())
		$arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
}

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;


$resizer_sets_list = array () ;
if(Loader::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$resizer_sets_list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}

global $arComponentParameters;

// RESIZER:
$arComponentParameters["GROUPS"]["RESIZER_SETS"]= array(
	"NAME" => GetMessage("RESIZER_SETS"),
	"SORT" => 1
);


$arTemplateParameters = array(
	"RESIZER_SEARCH_TITLE" => array(
			"PARENT" => "RESIZER_SETS",
			"NAME" => GetMessage("RESIZER_SEARCH_TITLE"),
			"TYPE" => "LIST",
			"VALUES" => $resizer_sets_list,
			"DEFAULT" => "6",
	),
	"SHOW_INPUT" => array(
		"NAME" => GetMessage("TP_BST_SHOW_INPUT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"REFRESH" => "Y",
	),
	"INPUT_ID" => array(
		"NAME" => GetMessage("TP_BST_INPUT_ID"),
		"TYPE" => "STRING",
		"DEFAULT" => "title-search-input",
	),
	"CONTAINER_ID" => array(
		"NAME" => GetMessage("TP_BST_CONTAINER_ID"),
		"TYPE" => "STRING",
		"DEFAULT" => "title-search",
	),
	"PRICE_CODE" => array(
		"PARENT" => "PRICES",
		"NAME" => GetMessage("TP_BST_PRICE_CODE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arPrice,
	),
	"PRICE_VAT_INCLUDE" => array(
		"PARENT" => "PRICES",
		"NAME" => GetMessage("TP_BST_PRICE_VAT_INCLUDE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"PREVIEW_TRUNCATE_LEN" => array(
		"PARENT" => "ADDITIONAL_SETTINGS",
		"NAME" => GetMessage("TP_BST_PREVIEW_TRUNCATE_LEN"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	/*
	"SHOW_PREVIEW" => array(
		"NAME" => GetMessage("TP_BST_SHOW_PREVIEW"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"REFRESH" => "Y",
	),
	*/
);

// EXAPMPLES
$arTemplateParameters["EXAMPLE_ENABLE"] = array(
	"PARENT" => "VISUAL",
	"NAME" => GetMessage("EXAMPLE_ENABLE"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N",
	"REFRESH" => "Y",
);

if($arCurrentValues["EXAMPLE_ENABLE"] == "Y")
{
	$arTemplateParameters["EXAMPLES"] = array(		
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("EXAMPLES"),
		"TYPE" => "STRING",
		"MULTIPLE" => "Y",
		"ADDITIONAL_VALUES" => "Y",
	);
}
if (isset($arCurrentValues['SHOW_PREVIEW']) && 'Y' == $arCurrentValues['SHOW_PREVIEW'])
{
	$arTemplateParameters["PREVIEW_WIDTH"] = array(
		"NAME" => GetMessage("TP_BST_PREVIEW_WIDTH"),
		"TYPE" => "STRING",
		"DEFAULT" => 75,
	);
	$arTemplateParameters["PREVIEW_HEIGHT"] = array(
		"NAME" => GetMessage("TP_BST_PREVIEW_HEIGHT"),
		"TYPE" => "STRING",
		"DEFAULT" => 75,
	);
}
if (CModule::IncludeModule('catalog') && CModule::IncludeModule('currency'))
{
	$arTemplateParameters['CONVERT_CURRENCY'] = array(
		'PARENT' => 'PRICES',
		'NAME' => GetMessage('TP_BST_CONVERT_CURRENCY'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	);

	if (isset($arCurrentValues['CONVERT_CURRENCY']) && 'Y' == $arCurrentValues['CONVERT_CURRENCY'])
	{
		$arCurrencyList = array();
		$rsCurrencies = CCurrency::GetList(($by = 'SORT'), ($order = 'ASC'));
		while ($arCurrency = $rsCurrencies->Fetch())
		{
			$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
		}
		$arTemplateParameters['CURRENCY_ID'] = array(
			'PARENT' => 'PRICES',
			'NAME' => GetMessage('TP_BST_CURRENCY_ID'),
			'TYPE' => 'LIST',
			'VALUES' => $arCurrencyList,
			'DEFAULT' => CCurrency::GetBaseCurrency(),
			"ADDITIONAL_VALUES" => "Y",
		);
	}
}

//$arTemplateParameters['NUM_CATEGORIES']['HIDDEN'] = 'Y';
$arTemplateParameters['SHOW_OTHERS']['HIDDEN'] = 'Y';
$arTemplateParameters['SHOW_INPUT']['HIDDEN'] = 'Y';
/*
for ($i=0; $i<$arCurrentValues['NUM_CATEGORIES']; $i++) {
	$arTemplateParameters['CATEGORY_'.$i.'_TITLE']['HIDDEN'] = 'Y';
	$arTemplateParameters['CATEGORY_'.$i]['HIDDEN'] = 'Y';
}
*/

if (\Bitrix\Main\Loader::includeModule('yenisite.geoipstore')) {
	$arTemplateParameters['PRICE_CODE']['TYPE'] = 'STRING';
	$arTemplateParameters['PRICE_CODE']['MULTIPLE'] = 'N';
}
?>
