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
		
	"SQUARES_PER_WIDTH" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SQUARES_PER_WIDTH"),
			"TYPE" => "STRING",
			"DEFAULT" => 7,
		),
		
	"SQUARES_PER_HEIGHT" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SQUARES_PER_HEIGHT"),
			"TYPE" => "STRING",
			"DEFAULT" => 5,
		),
		
	"SLIDE_SHOW_INTERVAL" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SLIDE_SHOW_INTERVAL"),
			"TYPE" => "STRING",
			"DEFAULT" => "2500",
		),
		
	"DELAY" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("DELAY"),
			"TYPE" => "STRING",
			"DEFAULT" => "30",
		),
		
	"OPACITY" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OPACITY"),
			"TYPE" => "LIST",
			"VALUES" => $arOpacity,
			"DEFAULT" => "0.7",
		),
		
	"TITLE_SPEED" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("TITLE_SPEED"),
			"TYPE" => "STRING",
			"DEFAULT" => 500,
		),
		
	"EFFECT" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("EFFECT"),
			"TYPE" => "LIST",
			"VALUES" => Array( "random" => "Random", "swirl" => "Swirl", "rain" => "Rain", "straight" => "Straight" ),			
		),
		
	"NAVIGATION"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("NAVIGATION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
		
	"HOVER_PAUSE"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("HOVER_PAUSE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
);

?>
