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
    /*
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

	"NAVIGATION" => array(
        "PARENT" => "YENISITE_RESIZER2_PARAMETERS",
        "NAME" => GetMessage("NAVIGATION"),
        "TYPE" => "LIST",
        "VALUES" => array( "buttons" => "Buttons", "slider" => "Slider" ), 
	"DEFAULT" => "buttons",      
    ), */
		
	"ROTATE_X_MIN" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ROTATE_X_MIN"),
			"TYPE" => "STRING",
			"DEFAULT" => "40"			
		),
		
	"ROTATE_X_MAX" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ROTATE_X_MAX"),
			"TYPE" => "STRING",
			"DEFAULT" => "90"			
		),
		
	"ROTATE_Y_MIN" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ROTATE_Y_MIN"),
			"TYPE" => "STRING",
			"DEFAULT" => "-15"			
		),
		
	"ROTATE_Y_MAX" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ROTATE_Y_MAX"),
			"TYPE" => "STRING",
			"DEFAULT" => "45"			
		),
		
	"ROTATE_Z_MIN" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ROTATE_Z_MIN"),
			"TYPE" => "STRING",
			"DEFAULT" => "-10"			
		),
		
	"ROTATE_Z_MAX" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ROTATE_Z_MAX"),
			"TYPE" => "STRING",
			"DEFAULT" => "10"			
		),
		
	"TRANSLATE_X_MIN" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("TRANSLATE_X_MIN"),
			"TYPE" => "STRING",
			"DEFAULT" => "-400"			
		),
		
	"TRANSLATE_X_MAX" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("TRANSLATE_X_MAX"),
			"TYPE" => "STRING",
			"DEFAULT" => "400"			
		),
		
	"TRANSLATE_Y_MIN" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("TRANSLATE_Y_MIN"),
			"TYPE" => "STRING",
			"DEFAULT" => "-400"			
		),
		
	"TRANSLATE_Y_MAX" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("TRANSLATE_Y_MAX"),
			"TYPE" => "STRING",
			"DEFAULT" => "400"			
		),
		
	"TRANSLATE_Z_MIN" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("TRANSLATE_Z_MIN"),
			"TYPE" => "STRING",
			"DEFAULT" => "350"			
		),
		
	"TRANSLATE_Z_MAX" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("TRANSLATE_Z_MAX"),
			"TYPE" => "STRING",
			"DEFAULT" => "550"			
		),
);

?>
