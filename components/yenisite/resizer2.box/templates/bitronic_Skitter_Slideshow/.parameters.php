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
		"DEFAULT" => "2",
		"REFRESH" => "Y",
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
		
	"ANIMATION" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ANIMATION"),
			"TYPE" => "LIST",
			"VALUES" => Array("cube" => "cube", "cubeRandom" => "cubeRandom", "block" => "block", "cubeStop" => "cubeStop", "cubeHide" => "cubeHide", "cubeSize" => "cubeSize", "horizontal" => "horizontal", "showBars" => "showBars", "showBarsRandom" => "showBarsRandom", "tube" => "tube", "fade" => "fade", "fadeFour" => "fadeFour", "paralell" => "paralell", "blind" => "blind", "blindHeight" => "blindHeight", "blindWidth" => "blindWidth", "directionTop" => "directionTop", "directionBottom" => "directionBottom", "directionLeft" => "directionLeft", "directionRight" => "directionRight", "cubeStopRandom" => "cubeStopRandom", "cubeSpread" => "cubeSpred", "cubeJelly" => "cubeJelly", "glassCube" => "glassCube", "glassBlock" => "glassBlock", "circles" => "circles", "circlesInside" => "circlesInside", "circlesRotate" => "circlesRotate", "cubeShow" => "cubeShow", "upBars" => "upBars", "downBars" => "downBars", "hideBars" => "hideBars", "swapBars" => "swapBars", "swapBarsBack" => "swapBarsBack", "swapBlocks" => "swapBlocks", "cut" => "cut", "random" => "random", "randomSmart" => "randomSmart"),
			"DEFAULT" => "random",
		),
		
	"NAVIGATION" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("NAVIGATION"),
			"TYPE" => "LIST",
			"VALUES" => array(
						"numbers" => GetMessage("NUMBERS"),
						"thumbs" => GetMessage("THUMBS"),
						"dots" => GetMessage("DOTS"),
						"preview" => GetMessage("PREVIEW_WITH_DOTS") ),
			"DEFAULT" => "dots",
		),
	
	"VELOCITY" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("VELOCITY"),
			"TYPE" => "LIST",
			"VALUES" => array("0.1" => "0.1", "0.2" => "0.2", "0.3" => "0.3", "0.4" => "0.4", "0.5" => "0.5", "0.6" => "0.6", "0.7" => "0.7", "0.8" => "0.8", "0.9" => "0.9", "1.0" => "1.0", "1.1" => "1.1", "1.2" => "1.2", "1.3" => "1.3", "1.4" => "1.4", "1.5" => "1.5", "1.6" => "1.6", "1.7" => "1.7", "1.8" => "1.8", "1.9" => "1.9", "2.0" => "2.0"),
			"DEFAULT" => "1.0",
		),
		
	"HIDE_TOOLS" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("HIDE_TOOLS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		
	"ALIGNMENT" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ALIGNMENT"),
			"TYPE" => "LIST",
			"VALUES" => array( "left" => "left", "center" => "center", "right" => "right" ),
			"DEFAULT" => "center",
		),
	
	"AUTOPLAY_SLIDE_SHOW"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("AUTOPLAY_SLIDE_SHOW"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
		
	"CONTROLS"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("CONTROLS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
		
	"CONTROLS_POSITION" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("CONTROLS_POSITION"),
			"TYPE" => "LIST",
			"VALUES" => array( "center" => "center", "leftTop" => "leftTop", "rightTop" => "rightTop", "leftBottom" => "leftBottom", "rightBottom" => "rightBottom" ),
			"DEFAULT" => "rightTop",
		),
	
	"FOCUS"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("FOCUS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
		
	"FOCUS_POSITION" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("FOCUS_POSITION"),
			"TYPE" => "LIST",
			"VALUES" => array( "center" => "center", "leftTop" => "leftTop", "rightTop" => "rightTop", "leftBottom" => "leftBottom", "rightBottom" => "rightBottom" ),
			"DEFAULT" => "leftTop",
		),
		
	"SLIDE_SHOW_INTERVAL" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SLIDE_SHOW_INTERVAL"),
			"TYPE" => "STRING",
			"DEFAULT" => "2500",
		),
		
	"SHOW_RANDOMLY"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SHOW_RANDOMLY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",			
		),
		
	"LABEL"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("LABEL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
);

?>
