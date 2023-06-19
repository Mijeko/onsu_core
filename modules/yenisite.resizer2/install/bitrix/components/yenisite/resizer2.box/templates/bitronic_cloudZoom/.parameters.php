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
	"DEFAULT" => "7"  
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
	"SOFT_FOCUS"	=> Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("SOFT_FOCUS"),
		"TYPE" => "LIST",
		"VALUES" => array('true'=>GetMessage("YES"),'false'=>GetMessage("NO")),	
		"DEFAULT" => 'false'
	),	
	"ZOOM_WIDTH" => Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("ZOOM_WIDTH"),
		"TYPE" => "STRING",
		"DEFAULT" => "350"			
	),
	"ZOOM_HEIGHT" => Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("ZOOM_HEIGHT"),
		"TYPE" => "STRING",
		"DEFAULT" => "350"			
	),
	"POSITION"	=> Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("POSITION"),
		"TYPE" => "LIST",
		"VALUES" => array(	'left'=>GetMessage("LEFT"),
							'right'=>GetMessage("RIGHT"),
							'top'=>GetMessage("TOP"),
							'bottom'=>GetMessage("BOTTOM"),),
		"DEFAULT" => 'right'
	),
	"ADJUST_X" => Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("ADJUST_X"),
		"TYPE" => "STRING",
		"DEFAULT" => "0"			
	),
	"ADJUST_Y" => Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("ADJUST_Y"),
		"TYPE" => "STRING",
		"DEFAULT" => "0"			
	),
	"TINT" => Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("TINT"),
		"TYPE" => "COLORPICKER",
		"DEFAULT" => "000000"
	),
	"TINT_OPACITY" => Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("TINT_OPACITY"),
		"TYPE" => "LIST",
		"VALUES" => array('0'=>"100%",'0.1'=>"90%",'0.2'=>"80%",'0.3'=>"70%",'0.4'=>"60%",'0.5'=>"50%",'0.6'=>"40%",'0.7'=>"30%",'0.8'=>"20%",'0.9'=>"10%",'1'=>"0%"),	
		"DEFAULT" => '0.5'
	),
	"LENS_OPACITY" => Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("LENS_OPACITY"),
		"TYPE" => "LIST",
		"VALUES" => array('0'=>"100%",'0.1'=>"90%",'0.2'=>"80%",'0.3'=>"70%",'0.4'=>"60%",'0.5'=>"50%",'0.6'=>"40%",'0.7'=>"30%",'0.8'=>"20%",'0.9'=>"10%",'1'=>"0%"),	
		"DEFAULT" => '0.5'			
	),
	"SMOOTH_MOVE" => Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("SMOOTH_MOVE"),
		"TYPE" => "STRING",
		"DEFAULT" => "3"			
	),	
	"SHOW_TITLE"	=> Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("SHOW_TITLE"),
		"TYPE" => "LIST",
		"VALUES" => array('true'=>GetMessage("YES"),'false'=>GetMessage("NO")),	
		"DEFAULT" => 'true'
	),	
	"TITLE_OPACITY" => Array(
		"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
		"NAME" => GetMessage("TITLE_OPACITY"),
		"TYPE" => "LIST",
		"VALUES" => array('0'=>"100%",'0.1'=>"90%",'0.2'=>"80%",'0.3'=>"70%",'0.4'=>"60%",'0.5'=>"50%",'0.6'=>"40%",'0.7'=>"30%",'0.8'=>"20%",'0.9'=>"10%",'1'=>"0%"),	
		"DEFAULT" => '0.5'				
	),	
		
		
		
		
		
		
		/*
	"OVERLAY_OPACITY" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OVERLAY_OPACITY"),
			"TYPE" => "LIST",
			"VALUES" => array('0'=>"100%",'0.1'=>"90%",'0.2'=>"80%",'0.3'=>"70%",'0.4'=>"60%",'0.5'=>"50%",'0.6'=>"40%",'0.7'=>"30%",'0.8'=>"20%",'0.9'=>"10%",'1'=>"0%"),		
		),
	"FIXED_NAVIGATION"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("FIXED_NAVIGATION"),
			"TYPE" => "LIST",
			"VALUES" => array('true'=>"Да",'false'=>"Нет"),			
		),	
	"RESIZE_SPEED" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("RESIZE_SPEED"),
			"TYPE" => "STRING",
			"DEFAULT" => "500"	,		
		),
	"BORDER_SIZE" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("BORDER_SIZE"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",			
		),
	"SLIDE_SHOW_TIMER" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SLIDE_SHOW_TIMER"),
			"TYPE" => "STRING",
			"DEFAULT" => "5000",			
		),*/
	
);
?>
