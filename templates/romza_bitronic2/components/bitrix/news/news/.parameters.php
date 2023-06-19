<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if (!Loader::includeModule('iblock'))
    return;
$boolCatalog = Loader::includeModule('catalog');

// SKU:
$arSKU = false;
$boolSKU = false;
if ($boolCatalog && (isset($arCurrentValues['IBLOCK_ID']) && 0 < intval($arCurrentValues['IBLOCK_ID'])))
{
    $arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);
    $boolSKU = !empty($arSKU) && is_array($arSKU);
}

//PROPERTIES:
$arProperties_ALL	= array('-' => GetMessage('CP_BC_TPL_PROP_EMPTY')) ;
$arProperties_LNS	= array('-' => GetMessage('CP_BC_TPL_PROP_EMPTY')) ; // list, number, string
$arProperties_LNS_S = array('-' => GetMessage('CP_BC_TPL_PROP_EMPTY')) ; // same but not multiple
$arProperties_E		= array('-' => GetMessage('CP_BC_TPL_PROP_EMPTY')) ; // link element
if (IntVal($arCurrentValues["IBLOCK_ID"]))
{
    $dbProperties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array('ACTIVE'=>'Y', 'IBLOCK_ID'=>IntVal($arCurrentValues["IBLOCK_ID"])));
    while ($arProp = $dbProperties->GetNext())
    {
        $strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];

        $arProperties_ALL[$arProp["CODE"]] = "[{$arProp['CODE']}] {$arProp['NAME']}" ;
        if(in_array($arProp["PROPERTY_TYPE"], array("L", "N", "S"))) {
            $arProperties_LNS[$arProp["CODE"]] = &$arProperties_ALL[$arProp["CODE"]] ;
            if ($arProp["MULTIPLE"] != "Y") {
                $arProperties_LNS_S[$arProp["CODE"]] = &$arProperties_ALL[$arProp["CODE"]] ;
            }
        }
        if($arProp['PROPERTY_TYPE'] == 'E')
            $arProperties_E[$arProp["CODE"]] = &$arProperties_ALL[$arProp["CODE"]] ;
        if ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
            $arHighloadPropList[$arProp['CODE']] = $strPropName;
    }
}

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
	"RESIZER_NEWS_LIST" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_NEWS_LIST"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "4",
	),
	
	"RESIZER_NEWS_DETAIL" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_NEWS_DETAIL"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "2",
	),

	"RELATED_HEADER_TEXT" => array(
		"NAME" => GetMessage("RELATED_HEADER_TEXT"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("RELATED_HEADER_TEXT_DEFAULT"),
	),
    "ACTION_TEXT" => array(
        "NAME" => GetMessage("ACTION_TEXT"),
        "TYPE" => "STRING",
        "DEFAULT" => GetMessage("ACTION_TEXT_DEFAULT"),
    ),
    "PROP_FOR_DISCOUNT" => array(
        "PARENT" => 'BASE',
		"NAME" => GetMessage("PROP_FOR_DISCOUNT"),
		"TYPE" => "LIST",
		"DEFAULT" => 'DISCOUNTS',
        "VALUES" => $arProperties_ALL,
        'HIDDEN' => ModuleManager::isModuleInstalled('yenisite.bitronic2lite') ? 'Y' : 'N'
	),
    "PROP_FOR_BANNER" => array(
        "PARENT" => 'BASE',
        "NAME" => GetMessage("PROP_FOR_BANNER"),
        "TYPE" => "LIST",
        "DEFAULT" => 'BANNER_IMG',
        "VALUES" => $arProperties_ALL,
        'HIDDEN' => ModuleManager::isModuleInstalled('yenisite.bitronic2lite') ? 'Y' : 'N'
    ),
    "COUNT_ELEMENT_ACTIONS" => array(
        "PARENT" => 'BASE',
		"NAME" => GetMessage("COUNT_ELEMENT_ACTIONS"),
		"TYPE" => "STRING",
		"DEFAULT" => '10',
	)
);
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

?>