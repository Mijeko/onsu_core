<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$rsIBlockType = CIBlockType::GetList(array("sort"=>"asc"), array("ACTIVE"=>"Y"));
while ($arr=$rsIBlockType->Fetch())
{
	if($ar=CIBlockType::GetByIDLang($arr["ID"], LANGUAGE_ID))
		$arIBlockType[$arr["ID"]] = "[".$arr["ID"]."] ".$ar["NAME"];
}

$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];

//HL_IBLOCK:
$bHLIblock = ($arCurrentValues["HL_IBLOCK"] == 'Y' && CModule::includeModule('highloadblock'));
if ($bHLIblock)
{
	$hlblockRes = \Bitrix\Highloadblock\HighloadBlockTable::getList();
	$arIBlock = array();
	while($arr = $hlblockRes->fetch())
	{
		$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
	}
	
	if(intval($arCurrentValues["IBLOCK_ID"]))
	{
		$arHLIBFields = array();
		$hlblockFilds = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('HLBLOCK_'.$arCurrentValues["IBLOCK_ID"], 0, LANGUAGE_ID);
		foreach($hlblockFilds as $arField)
		{
			$arHLIBFields[$arField["FIELD_NAME"]] = "[".$arField['FIELD_NAME']."] ".$arField["LIST_COLUMN_LABEL"];
		}
	}
}

$arComponentParameters = array(
	"PARAMETERS" => array(
		"HL_IBLOCK" => array(
			'PARENT' => 'DATA_SOURCE',
			'NAME' => GetMessage('HL_IBLOCK'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y'
		),
		"IBLOCK_TYPE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_IBLOCK"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		"SECTION_ID" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_SECTION_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["SECTION_ID"]}'
		),
		"HL_NAME_FIELD" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("HL_NAME_FIELD"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arHLIBFields,
		),
		"FILTER_NAME" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_FILTER_NAME_OUT"),
			"TYPE" => "STRING",
			"DEFAULT" => "arrFilter",
		),
		"GENERATION" => array(
			"NAME" => GetMessage("GENERATION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			),
        "LIST_ENABLE" => array(
			"NAME" => GetMessage("LIST_ENABLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			),
		"SHOW_NUMBER" => array(
			"NAME" => GetMessage("SHOW_NUMBER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			),
		"GROUP_NUMBER" => array(
			"NAME" => GetMessage("GROUP_NUMBER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			),
		"SHOW_RUS" => array(
			"NAME" => GetMessage("SHOW_RUS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			),
		"GROUP_RUS" => array(
			"NAME" => GetMessage("GROUP_RUS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			),
		"SHOW_ENG" => array(
			"NAME" => GetMessage("SHOW_ENG"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			),
		"GROUP_ENG" => array(
			"NAME" => GetMessage("GROUP_ENG"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			),
		"SHOW_ALL" => array(
			"NAME" => GetMessage("SHOW_ALL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			),
		"INCLUDE_SUBSECTIONS" => array(
			"NAME" => GetMessage("INCLUDE_SUBSECTIONS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			),
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
	),
);

if ($bHLIblock)
{
	unset(
		$arComponentParameters["PARAMETERS"]["IBLOCK_TYPE"],
		$arComponentParameters["PARAMETERS"]["SECTION_ID"]
	);
}
else
{
	unset(
		$arComponentParameters["PARAMETERS"]["HL_NAME_FIELD"]
	);	
}
?>
