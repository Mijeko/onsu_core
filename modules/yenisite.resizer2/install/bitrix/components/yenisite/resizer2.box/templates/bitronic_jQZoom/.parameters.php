<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



CModule::IncludeModule("yenisite.resizer2");
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
	$arSet[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
}


$arOpacity      = array("0" => "0", "0.1" => "0.1", "0.2" => "0.2", "0.3" => "0.3", "0.4" => "0.4", "0.5" => "0.5", "0.6" => "0.6", "0.7" => "0.7", "0.8" => "0.8", "0.9" => "0.9", "1" => "1");
$arOverlay      = array("true" => "Y", "false" => "N");
$arZoomTypes    = array("standard" => "standard", "innerzoom" => "innerzoom", "drag" => "drag", "reverse" => "reverse");
$arShowEffect   = array("show" => "show", "fadein" => "fadein");
$arHideEffect   = array("hide" => "hide", "fadeout" => "fadeout");

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
	"DEFAULT" => "7"  
    ),
    
    "SET_SMALL_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_SMALL_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet, 
	"DEFAULT" => "6"       
    ),
		
    "SHOW_TITLE"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SHOW_TITLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => TRUE,			
		),
    
    "ZOOM_TYPE" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ZOOM_TYPE"),
			"TYPE" => "LIST",
                        "VALUES" => $arZoomTypes,
			"DEFAULT" => "window",	
		),
    "SHOW_EFFECT" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SHOW_EFFECT"),
			"TYPE" => "LIST",
                        "VALUES" => $arShowEffect,
			"DEFAULT" => "show",	
		),
    
    "HIDE_EFFECT" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("HIDE_EFFECT"),
			"TYPE" => "LIST",
                        "VALUES" => $arHideEffect,
			"DEFAULT" => "hide",	
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
    
    "ZOOM_SPEED_IN" => Array(
                    "PARENT" => "YENISITE_RESIZER2_PARAMETERS",
                    "NAME" => GetMessage("ZOOM_SPEED_IN"),
                    "TYPE" => "STRING",
                    "DEFAULT" => "500"			
            ),

    "ZOOM_SPEED_OUT" => Array(
                    "PARENT" => "YENISITE_RESIZER2_PARAMETERS",
                    "NAME" => GetMessage("ZOOM_SPEED_OUT"),
                    "TYPE" => "STRING",
                    "DEFAULT" => "700"			
            ),
    
    
);
?>
