<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



CModule::IncludeModule("yenisite.resizer2");
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
	$arSet[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
}


$arOpacity = array("0" => "0", "0.1" => "0.1", "0.2" => "0.2", "0.3" => "0.3", "0.4" => "0.4", "0.5" => "0.5", "0.6" => "0.6", "0.7" => "0.7", "0.8" => "0.8", "0.9" => "0.9", "1" => "1");
$arOverlay = array("true" => "Y", "false" => "N");

global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_RESIZER2_PARAMETERS"]= array(
	"NAME" => GetMessage("YENISITE_RESIZER2_PARAMETERS"),
	"SORT" => 2000,
);


$arTemplateParameters = array(
    "SET_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet,    
	"DEFAULT" => "2"     
    ),
    
    "SET_BIG_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_BIG_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet,        
	"DEFAULT" => "1"  
    ),
    
    "SET_SMALL_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_SMALL_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet, 
	"DEFAULT" => "6"       
    ),
    
  
		
    "SHOW_DESCRIPTION"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SHOW_DESCRIPTION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
		
    "SHOW_DELAY_DETAIL" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SHOW_DELAY_DETAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => "300"			
		),
		
    "HIDE_DELAY_DETAIL" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("HIDE_DELAY_DETAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => "600"			
		),
		
    "OPEN_SPEED" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OPEN_SPEED"),
			"TYPE" => "LIST",
			"VALUES" => array( "slow" => GetMessage("SLOW"), "normal" => GetMessage("NORMAL"), "fast" => GetMessage("FAST") ),
		),
		
	"CLOSE_SPEED" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("CLOSE_SPEED"),
			"TYPE" => "LIST",
			"VALUES" => array( "slow" => GetMessage("SLOW"), "normal" => GetMessage("NORMAL"), "fast" => GetMessage("FAST") ),
		),
		
	"OPEN_EFFECT" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OPEN_EFFECT"),
			"TYPE" => "LIST",
			"VALUES" => array("fade" => "fade", "elastic" => "elastic", "none" => "none"),
		),
		
	"CLOSE_EFFECT" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("CLOSE_EFFECT"),
			"TYPE" => "LIST",
			"VALUES" => array("fade" => "fade", "elastic" => "elastic", "none" => "none"),
		),
		
    "OVERLAY" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OVERLAY"),
			"TYPE" => "LIST",
			"VALUES" => $arOverlay,
		),
		
    "OVERLAY_OPACITY" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OVERLAY_OPACITY"),
			"TYPE" => "LIST",
			"VALUES" => $arOpacity,
		),
);
?>
