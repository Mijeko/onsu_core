<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = Array();
$db_iblock_type = CIBlockType::GetList(Array("SORT"=>"ASC"));
while($arRes = $db_iblock_type->Fetch())
{
	if($arIBType = CIBlockType::GetByIDLang($arRes["ID"], LANG))
	{
		$arTypesEx[$arRes["ID"]] = $arIBType["NAME"];
	}
}

$arIBlocks = Array();
$db_iblock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
{
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];
}

$properties = CIBlock::GetProperties($arCurrentValues['IBLOCK']);
while ($field = $properties->GetNext())
{
	$fields[$field['CODE']] = $field['NAME'];
}

$colorSchemes = array(
                    'green' => GetMessage("CS_GREEN"),
                    'ice' => GetMessage("CS_ICE"),
                    'metal' => GetMessage("CS_METAL"),
                    'pink' => GetMessage("CS_PINK"),
                    'red' => GetMessage("CS_RED"),
                    'yellow' => GetMessage("CS_YELLOW")
                );

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS"  =>  array(
		"IBLOCK_TYPE"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		),
		"IBLOCK"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"REFRESH" => 'Y',
		),
        
                "COLOR_SCHEME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COLOR_SCHEME"),
			"TYPE" => "LIST",
			"VALUES" => $colorSchemes,
                        "MULTIPLE" => "N",
                        "DEFAULT" => "green",
		),
            
                "MESS_PER_PAGE"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MESS_PER_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => '10',
		),
            
                "ALLOW_RESPONSE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ALLOW_RESPONSE"),
                        "TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
            
                "ALWAYS_SHOW_PAGES" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ALWAYS_SHOW_PAGES"),
                        "TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
            
                "SECTION_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => "SECTION_CODE",
			"TYPE" => "STRING",
                        "DEFAULT" => "",
		),
            
                "SEF_MODE" => array(),
            
                "AJAX_MODE" => array(),
		
		"CACHE_TIME"  =>  Array("DEFAULT"=>300),
	),
);
?>
