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
		
	"EFFECT" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("EFFECT"),
			"TYPE" => "LIST",
			"VALUES" => Array( "slide-hori" => "Slide horizontal", "slide-vert" => "Slide vertical", "resize" => "Resize", "fade" => "Fade", "none" => "None" ),
			"DEFAULT" => "slide-hori",
		),
		
	"SPEED" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SPEED"),
			"TYPE" => "STRING",
			"DEFAULT" => 400,
		),
		
	"DISPLAY_NEXT_AND_PREV"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("DISPLAY_NEXT_AND_PREV"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
		
	"DISPLAY_BACK_AND_FORWARD"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("DISPLAY_BACK_AND_FORWARD"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
		
	"THUMB_OPACITY" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("THUMB_OPACITY"),
			"TYPE" => "LIST",
			"VALUES" => $arOpacity,
			"DEFAULT" => "0.7",
		),
		
	"CYCLE"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("CYCLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
		
	"SLIDE_SHOW" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SLIDE_SHOW"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		
	"AUTOPLAY_SLIDE_SHOW"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("AUTOPLAY_SLIDE_SHOW"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",			
		),
	
	"SLIDE_SHOW_INTERVAL" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SLIDE_SHOW_INTERVAL"),
			"TYPE" => "STRING",
			"DEFAULT" => "2000",
		),
	/*	
	'WIDTH' => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("WIDTH"),
			"TYPE" => "STRING",
			"DEFAULT" => 100,
		),
	'HEIGHT' => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("HEIGHT"),
			"TYPE" => "STRING",
			"DEFAULT" => 100,
		),
	*/
);

/* $arRes = CResizer2Set::GetByID( $arCurrentValues["SET_DETAIL"] );
$arTemplateParameters["WIDTH"]["DEFAULT"] = $arRes['w'];
$arTemplateParameters["HEIGHT"]["DEFAULT"] = $arRes['h']; */

?>
