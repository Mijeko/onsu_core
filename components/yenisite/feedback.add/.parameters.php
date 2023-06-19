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

$properties = CIBlock::GetProperties($arCurrentValues['IBLOCK'], array("SORT" => "ASC"));
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
		
		"NAME_FIELD" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("NAME_FIELD"),
			"TYPE" => "LIST",
			"VALUES" => $fields,
			"DEFAULT" => '',
		),
		
                "COLOR_SCHEME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COLOR_SCHEME"),
			"TYPE" => "LIST",
			"VALUES" => $colorSchemes,
                        "MULTIPLE" => "N",
                        "DEFAULT" => "green",
		),
            
                "TITLE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TITLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
            
		"SUCCESS_TEXT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SUCCESS_TEXT"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
            
		"ACTIVE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ACTIVE"),
                        "TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		
		"USE_CAPTCHA" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("USE_CAPTCHA"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
            
            	"TEXT_SHOW" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TEXT_SHOW"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
            
		"TEXT_REQUIRED" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TEXT_REQUIRED"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
            
            	"SHOW_SECTIONS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SHOW_SECTIONS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
            
		"EVENT_NAME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("EVENT_NAME"),
			"TYPE" => "STRING",
                        "DEFAULT" => "",
		),
            
		"PRINT_FIELDS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PRINT_FIELDS"),
			"TYPE" => "LIST",
			"VALUES" => $fields,
                        "MULTIPLE" => "Y",
		),
            
                        
                "NAME" => array(
			"PARENT" => "YENISITE_FORM",
			"NAME" => GetMessage("NAME"),
			"TYPE" => "LIST",
                        "VALUES" => $fields,
			"DEFAULT" => 'name',
                        "MULTIPLE" => "N",                        
		),
		"EMAIL" => array(
			"PARENT" => "YENISITE_FORM",
			"NAME" => GetMessage("EMAIL"),
			"TYPE" => "LIST",
                        "VALUES" => $fields,
			"DEFAULT" => 'email',
                        "MULTIPLE" => "N",
		),
		"PHONE" => array(
			"PARENT" => "YENISITE_FORM",
			"NAME" => GetMessage("PHONE"),
			"TYPE" => "LIST",
                        "VALUES" => $fields,
			"DEFAULT" => 'phone',
                        "MULTIPLE" => "N",
		),
            
                "SECTION_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SECTION_CODE"),
			"TYPE" => "STRING",
                        "DEFAULT" => "",
		),
            
                "ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ELEMENT_ID"),
			"TYPE" => "STRING",
                        "DEFAULT" => "",
		),
            
                "AJAX_MODE" => array(),
		
		"CACHE_TIME"  =>  Array("DEFAULT"=>300),
	),
);

$arComponentParameters["PARAMETERS"]["AJAX_REDIRECT"] = Array(
	"PARENT" => "AJAX_MODE",
	"NAME" => GetMessage("AJAX_REDIRECT"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
);
?>
