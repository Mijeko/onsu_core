<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



CModule::IncludeModule("yenisite.resizer2");
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
	$arSet[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
}

global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_RESIZER2_PARAMETERS"]= array(
	"NAME" => GetMessage("YENISITE_RESIZER2_PARAMETERS"),
	"SORT" => 2000,
);


$arTemplateParameters = array(
    "SET_LIST" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_LIST"),
        "TYPE" => "LIST",
        "VALUES" => $arSet,   
	"DEFAULT" => "4"     
    ),
 
);
?>
