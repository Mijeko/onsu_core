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
	"ANIMATE"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ANIMATE"),
			"TYPE" => "LIST",
			"VALUES" => array('true'=>GetMessage("YES"),'false'=>GetMessage("NO")),
			"DEFAULT" => 'true',
		),
	"ANIMATE_FADE"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ANIMATE_FADE"),
			"TYPE" => "LIST",
			"VALUES" => array('true'=>GetMessage("YES"),'false'=>GetMessage("NO")),
			"DEFAULT" => 'true',
		),
	"ANIMATE_SEQUENCE"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ANIMATE_SEQUENCE"),
			"TYPE" => "LIST",
			"VALUES" => array(	'wh'=>GetMessage("WIDTH"),
								'hw'=>GetMessage("HEIGHT"),
								'sync'=>GetMessage("SYNC"),),
			"DEFAULT" => 'sync',
		),
	"CONTINUOUS"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("CONTINUOUS"),
			"TYPE" => "LIST",
			"VALUES" => array('true'=>GetMessage("YES"),'false'=>GetMessage("NO")),
			"DEFAULT" => 'false',
		),
	
		
	"DISPLAY_COUNTER"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("DISPLAY_COUNTER"),
			"TYPE" => "LIST",
			"VALUES" => array('true'=>GetMessage("YES"),'false'=>GetMessage("NO")),
			"DEFAULT" => 'true',
		),
	"ENABLE_KEYS"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ENABLE_KEYS"),
			"TYPE" => "LIST",
			"VALUES" => array('true'=>GetMessage("YES"),'false'=>GetMessage("NO")),
			"DEFAULT" => 'true',
		),	
	"FADE_DURATION" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("FADE_DURATION"),
			"TYPE" => "STRING",
			"DEFAULT" => "0.35"			
		),	
	/*"INITIAL_HEIGHT" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("INITIAL_HEIGHT"),
			"TYPE" => "STRING",
			"DEFAULT" => "160"			
		),
	"INITIAL_WIDTH" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("INITIAL_WIDTH"),
			"TYPE" => "STRING",
			"DEFAULT" => "320"			
		),*/
	"MODAL"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("MODAL"),
			"TYPE" => "LIST",
			"VALUES" => array('true'=>GetMessage("YES"),'false'=>GetMessage("NO")),
			"DEFAULT" => 'false',
		),
	"OVERLAY_OPACITY" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OVERLAY_OPACITY"),
			"TYPE" => "LIST",
			"VALUES" => array('0'=>"100%",'0.1'=>"90%",'0.2'=>"80%",'0.3'=>"70%",'0.4'=>"60%",'0.5'=>"50%",'0.6'=>"40%",'0.7'=>"30%",'0.8'=>"20%",'0.9'=>"10%",'1'=>"0%"),	
			"DEFAULT" => '0.5',
		),
	"RESIZE_DURATION" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("RESIZE_DURATION"),
			"TYPE" => "STRING",
			"DEFAULT" => "0.35"			
		),	
	"SHOW_OVERLAY"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SHOW_OVERLAY"),
			"TYPE" => "LIST",
			"VALUES" => array('true'=>GetMessage("YES"),'false'=>GetMessage("NO")),
			"DEFAULT" => 'true',
		),	
	"SLIDES_SHOW_DELAY" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SLIDES_SHOW_DELAY"),
			"TYPE" => "STRING",
			"DEFAULT" => "0"			
		),	
	"VIEWPORT_PADDING" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("VIEWPORT_PADDING"),
			"TYPE" => "STRING",
			"DEFAULT" => "20"			
		),	
	"OVERLAY_COLOR" => Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OVERLAY_COLOR"),
			"TYPE" => "COLORPICKER",
			"DEFAULT" => "000000"			
		),	
		
	//COUNTER_TYPE - последний
	"COUNTER_TYPE"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("COUNTER_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => array(	'default'=>GetMessage("TEXT"),
								'skip'=>GetMessage("LINK"),
								),
			"REFRESH" => 'Y',
			"DEFAULT" => 'default',
		),
);
	if($arCurrentValues["COUNTER_TYPE"]=='skip')
	{
		$arTemplateParameters["COUNTER_LIMIT"] = Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("COUNTER_LIMIT"),
			"TYPE" => "STRING",
			"DEFAULT" => "0"	,
		);
	}
	
    
//);
?>
