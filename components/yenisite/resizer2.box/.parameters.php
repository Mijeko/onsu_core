<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;


$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arIBlock=array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"], "PROPERTY_TYPE" => "F"));
while ($arr = $rsProp->Fetch())
{
	$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
}

$arComponentParameters = array(
	"GROUPS" => array(
		"RSS" => array(
			"NAME" => GetMessage("CP_BRO_RSS"),
		),
	),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
			"DEFAULT" => '={$arResult["IBLOCK_TYPE"]}'
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_ID"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
			"DEFAULT" => '={$arResult["IBLOCK_ID"]}'
		),
		
		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ELEMENT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" =>'={$arResult["ID"]}'
		),
		
		"PARENT_ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PARENT_ELEMENT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" =>''
		),
		
		"PROPERTY_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PROPERTY_CODE"),
			"TYPE" => "LIST",
			"VALUES" => $arProperty,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" =>'MORE_PHOTO'

		),
		
		"DROP_PREVIEW_DETAIL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DROP_PREVIEW_DETAIL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" =>'N'
		),
		
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),		
	),
);
?>
